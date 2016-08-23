<?php namespace App\Services;

use App\Data\Blog\Image;
use App\Data\System\User;
use Illuminate\Support\Facades\Config;
use File;
use Intervention\Image\Facades\Image as Img;

class ImageService
{
    private $user;
    private $image;
    private $config;

    const UPLOAD_DIR = '/uploads/images/';
    const UPLOAD_DIR_INTERVENTION = 'uploads/images/';
    const CROP_WIDTH = 147;
    const CROP_HEIGHT = 147;
    const UPLOAD_DIR_MOBILE = 'mobile';
    const UPLOAD_DIR_TABLET = 'tab';
    const UPLOAD_DIR_PC = 'pc';
    const UPLOAD_DIR_THUMB = 'thumb';
    const UPLOAD_DIR_AVATAR = 'avatar';
    const WIDTH_MOBILE = 600;
    const WIDTH_TABLET = 800;
    const WIDTH_PC = 1024;
    const WIDTH_THUMB = 300;
    const WIDTH_AVATAR = 400;
    const DEVICE_MOBILE = 1;
    const DEVICE_TABLET = 2;
    const DEVICE_PC = 3;
    const AVATAR = 4;

    public function __construct($user, $image = null)
    {
        $this->user = $user;
        $this->image = $image;
        $this->config = Config::get('image');
    }

    /**
     * get config about image upload
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get max file size of an image
     * @return string
     */
    public function getMaxFileSize()
    {
        return $this->config['max_image_size'];
    }

    /**
     * Check whether user can upload current image or not
     * @return bool
     */
    public function checkCurrentQuota()
    {
        if (isset($this->config['images_per_month'])) {
            $count = $this->user->images()->thisMonth()->count();
            if ($count >= $this->config['images_per_month']) {
                return false;
            }
        }

        if (isset($this->config['storage_per_month'])) {
            $totalSize = $this->user->images()->thisMonth()->sum('size');
            if ($totalSize >= $this->config['storage_per_month'] * 1000 * 1000) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Upload url
     * @return string
     */
    public function getUploadUrl()
    {
        return self::UPLOAD_DIR . sha1($this->user->username) . '/';
    }

    public function getUploadUrlGetWidthImg()
    {
        return self::UPLOAD_DIR_INTERVENTION . sha1($this->user->username) . '/';
    }

    /**
     * Upload dir
     * @return string
     */
    public function getUploadDir()
    {
        return public_path() . self::UPLOAD_DIR . sha1($this->user->username) . '/';
    }

    public function getUploadDirBy($type = '')
    {
        switch ($type) {
            case self::UPLOAD_DIR_PC:
                $dir = self::UPLOAD_DIR_PC;
                break;
            case self::UPLOAD_DIR_TABLET:
                $dir = self::UPLOAD_DIR_TABLET;
                break;
            case self::UPLOAD_DIR_MOBILE:
                $dir = self::UPLOAD_DIR_MOBILE;
                break;
            case self::UPLOAD_DIR_THUMB:
                $dir = self::UPLOAD_DIR_THUMB;
                break;
            case self::UPLOAD_DIR_AVATAR:
                $dir = self::UPLOAD_DIR_AVATAR;
                break;

            default:
                $dir = '';
                break;
        }

        $uploadDir = self::UPLOAD_DIR_INTERVENTION . sha1($this->user->username) . '/' . $dir;
        $publicDir = public_path() . '/' . $uploadDir;
        if (!file_exists($publicDir) && !empty($dir)) {
            mkdir($publicDir, 0777, true);
            chmod($publicDir, 0777);
        }

        return $uploadDir;
    }

    public function copyAndResizeImg($originalImage, $image, $width)
    {
        try {
            if (file_exists(public_path() . '/' . $originalImage) && !file_exists(public_path() . '/' . $image)) {
                $original = Img::make($originalImage);

                if ($original->width() >= $width) {
                    copy(public_path() . '/' . $originalImage, public_path() . '/' . $image);

                    $img = Img::make($image);
                    $img->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($image);
                }
            }

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function createImgInOtherSize($image)
    {
        $originalImage = self::getUploadDirBy('') . '/' . $image;
        $imageMobile = self::getUploadDirBy(self::UPLOAD_DIR_MOBILE) . '/' . $image;
        $imageTablet = self::getUploadDirBy(self::UPLOAD_DIR_TABLET) . '/' . $image;
        $imagePC = self::getUploadDirBy(self::UPLOAD_DIR_PC) . '/' . $image;

        self::copyAndResizeImg($originalImage, $imageMobile, self::WIDTH_MOBILE);
        self::copyAndResizeImg($originalImage, $imageTablet, self::WIDTH_TABLET);
        self::copyAndResizeImg($originalImage, $imagePC, self::WIDTH_PC);
    }

    public function createPostThumb($image)
    {
        $originalImage = self::getUploadDirBy('') . '/' . $image;
        $imageThumb = self::getUploadDirBy(self::UPLOAD_DIR_THUMB) . '/' . $image;

        $createThumb = self::copyAndResizeImg($originalImage, $imageThumb, self::WIDTH_THUMB);

        return ($createThumb) ? '/' . $imageThumb : '';
    }

    public function createAvatar($image)
    {
        $originalImage = self::getUploadDirBy('') . '/' . $image;
        $avatar = self::getUploadDirBy(self::UPLOAD_DIR_AVATAR) . '/' . $image;

        self::copyAndResizeImg($originalImage, $avatar, self::WIDTH_AVATAR);
    }

    /**
     * @param  $image
     * @return bool|Image
     */
    public function saveImage($image)
    {
        if (!empty($image)) {
            $this->image = $image;
        }

        if (!$this->checkCurrentQuota()) {
            return false;
        }

        $originalName = $this->image->getClientOriginalName();
        $extension = $this->image->getClientOriginalExtension();
        $filename = sha1(time() . time()) . '.' . $extension;
        $fileSize = $this->image->getSize();
        if ($this->image->move($this->getUploadDir(), $filename)) {
            $image = new Image([
                'user_id' => $this->user->id,
                'name' => $filename,
                'original_name' => $originalName,
                'size' => $fileSize,
            ]);
            $this->user->images()->save($image);
            return $image;
        }

        return false;
    }

    public function percentSize()
    {
        $totalSize = $this->user->images()->sum('size');
        $limitStorage = $this->config['limited_storage'] * 1000 * 1000;
        return (int)$totalSize / $limitStorage;
    }

    public function deleteBy($type, $image)
    {
        $file = public_path() . '/' . self::getUploadDirBy($type) . '/' . $image;
        if (File::exists($file)) {
            File::delete($file);
        }
    }

    public function delete($id)
    {
        $image = Image::find($id);
        $message = trans('messages.error');
        if (!$image) {
            $message = trans('messages.image.not_exist', ['id' => $id]);
            return [$message, true];
        }
        $imageDir = $this->getUploadDir();
        $directory = $imageDir . $image->name;
        if (File::delete($directory) || !File::exists($directory)) {
            self::deleteBy(self::UPLOAD_DIR_AVATAR, $image->name);
            self::deleteBy(self::UPLOAD_DIR_PC, $image->name);
            self::deleteBy(self::UPLOAD_DIR_TABLET, $image->name);
            self::deleteBy(self::UPLOAD_DIR_MOBILE, $image->name);

            if ($image->delete()) {
                $message = trans('messages.image.has_deleted', ['name' => $image->original_name]);
                return [$message, false];
            }
        }
        return [$message, true];
    }

    public static function crop($imageUrl, $zoom, $offset, $cropWidth = ImageService::CROP_WIDTH,
                                $cropHeight = ImageService::CROP_HEIGHT, $type = 'profile')
    {
        $imageObj = Img::make($imageUrl);

        if (!empty($imageObj)) {
            $imageWidth = $imageObj->width();
            $imageHeight = $imageObj->height();

            // Zoom Original Image
            $imageWidthAfterZoom = round($imageWidth * floatval($zoom), 0);
            $imageHeightAfterZoom = round($imageHeight * floatval($zoom), 0);

            // Create background maske in case of small image
            $background = Img::canvas($cropWidth, $cropHeight);

            $imageCropped = $imageObj->resize($imageWidthAfterZoom, $imageHeightAfterZoom);

            $background->insert($imageCropped, 'top-left', intval($offset[0]), intval($offset[1]));

            $config = Config::get('image');
            if ($type == 'cover') {
                $destinationPath = $config['group_image']['cover_upload_dir'];
            } else {
                $destinationPath = $config['group_image']['profile_upload_dir'];
            }

            // Save cropped image
            $imageCroppedPath = $destinationPath . '/' . sha1(time() . time() . mt_rand()) . '.' . $imageObj->extension;
            $background->save($imageCroppedPath);

            return $imageCroppedPath;
        }

        return false;

    }

    public static function getYoutubeEmbedLink($youtubeUrl)
    {
        $youtubeRegEx = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';

        preg_match($youtubeRegEx, $youtubeUrl, $youtubeVideoId);

        if (isset($youtubeVideoId[1])) {
            return 'https://www.youtube.com/embed/' . $youtubeVideoId[1] . '?showinfo=0&rel=0&loop=1&autoplay=0&enablejsapi=1';
        }

        return null;
    }

    public function saveImageByUrl($url)
    {
        try {
            if ($content = file_get_contents($url)) {
                if (!$this->checkCurrentQuota()) {
                    return false;
                }
                $originalName = basename($url);
                $extension = pathinfo($url, PATHINFO_EXTENSION);
                $filename = sha1(time() . time()) . '.' . $extension;
                $fileSize = getimagesize($url);
                $file_size = $fileSize[0] * $fileSize[1] * $fileSize['bits'];
                if (file_put_contents($this->getUploadDir() . '/' . $filename, $content)) {
                    $image = new Image([
                        'user_id' => $this->user->id,
                        'name' => $filename,
                        'original_name' => $originalName,
                        'size' => $file_size,
                    ]);
                    $this->user->images()->save($image);
                    return $image;
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    public function saveImageBase64($data, $name)
    {
        if ($content = base64_decode($data)) {
            if (!$this->checkCurrentQuota()) {
                return false;
            }
            $originalName = basename('base64_img_' . str_random(5));
            $extension = substr($name, 11, strpos($name, ';') - 11);
            $filename = sha1(time() . time()) . '.' . $extension;
            $fileSize = strlen($content);
            if (file_put_contents($this->getUploadDir() . '/' . $filename, $content)) {
                $image = new Image([
                    'user_id' => $this->user->id,
                    'name' => $filename,
                    'original_name' => $originalName,
                    'size' => $fileSize,
                ]);
                $this->user->images()->save($image);
                return $image;
            }
        }
        return false;
    }
}
