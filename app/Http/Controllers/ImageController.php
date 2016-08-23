<?php namespace App\Http\Controllers;

use View;
use Input;
use Validator;
use Response;
use App\Facades\Authority;

use App\Data\System\User;
use App\Data\Blog\Group;
use App\Data\Blog\Image;

use App\Services\GroupService;
use App\Services\ImageService;
use App\Services\UserService;

class ImageController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function postUpload()
    {
        $imageService = new ImageService($this->currentUser);
        $input = Input::all();
        $rules = Image::getUploadRules();
        $validation = Validator::make($input, $rules);
        if ($validation->fails()) {
            return Response::json([
                'status' => 'error',
                'message' => $validation->messages()->first()
            ], 400);
        }
        $image = $imageService->saveImage(\Request::file('image', []));

        if (isset($image)) {
            if (isset($input['username'])) {
                if ($this->currentUser->avatar) {
                    $imageService->delete($this->currentUser->avatar_id);
                }

                $imageService->createAvatar($image->name);
                \Cache::forget('avatar_' . $this->currentUser->username);
                UserService::updateUserInfo($this->currentUser->id, ['avatar_id' => $image->id, 'social_avatar_type' => null]);

            } else {
                $imageService->createImgInOtherSize($image->name);
            }

            try {
                $imgWidth = \Img::make($imageService->getUploadUrlGetWidthImg() . $image->name)->width();
            } catch (\Exception $e) {
                return Response::json([
                    'status' => 'error',
                    'message' => $e,
                ], 402);
            }

            return Response::json([
                'status' => 'success',
                'image_id' => $image->id,
                'url' => $imageService->getUploadUrl() . $image->name,
                'original_name' => $image->originalName,
                'imgWidth' => $imgWidth,
            ], 200);
        } else {
            return Response::json([
                'status' => 'error',
                'message' => trans('messages.image.upload_error'),
            ], 400);
        }
    }

    public function getView()
    {
        $this->viewData['title'] = trans('titles.images_manager');
        $imageService = new ImageService($this->currentUser);
        $images = $this->currentUser->images()->get();
        $imageUrl = $imageService->getUploadUrl();
        $percentStorager = $imageService->percentSize();
        $this->viewData['percentStorager'] = $percentStorager * 100;
        $this->viewData['images'] = $images;
        $this->viewData['url'] = $imageUrl;
        return View::make('image.index', $this->viewData);
    }

    public function postDestroy($id = null)
    {
        if (is_null($id)) {
            if (is_null($this->currentUser->social_avatar_type)) {
                $response = [
                    'message' => trans('messages.image.not_exist_social'),
                    'error' => true
                ];
                return Response::json($response, 400);
            }

            UserService::updateUserInfo($this->currentUser->id, ['social_avatar_type' => null, 'social_avatar_url' => null]);
            $response = [
                'message' => trans('messages.image.has_deleted_social'),
                'error' => false
            ];
            return Response::json($response, 200);
        } else {
            $imageService = new ImageService($this->currentUser);
            list($message, $error) = $imageService->delete($id);
            $response = [
                'message' => $message,
                'error' => $error,
            ];
            if (!$error) {
                if ($id == $this->currentUser->avatar_id) {
                    \Cache::forget('avatar_' . $this->currentUser->username);
                    UserService::updateUserInfo($this->currentUser->id, ['avatar_id' => User::DEFAULT_AVATAR_ID]);
                }
                return Response::json($response, 200);
            } else {
                return Response::json($response, 400);
            }
        }
    }

    public function getCrop($hash)
    {
        $response = [
            'error' => false,
            'message' => 'Generated group crop images!'
        ];

        if ($hash == 'VibloTeam2015') {
            $groups = Group::all();

            foreach ($groups as $group) {
                $group = GroupService::makeCropImage($group, 'profile');
                $group = GroupService::makeCropImage($group, 'cover');

                $group->save();
            }
        } else {
            $response = [
                'error' => true,
                'message' => 'Denied!!!'
            ];
        }

        return Response::json($response, 200);
    }

    public function getCompress()
    {
        $response = [
            'error' => false,
            'message' => 'Generated losslessly image compression!'
        ];

        if (\Auth::check() && Authority::hasRole('admin')) {
            $relativePath = Input::get('path', '');
            $quality = Input::get('quality', 80);

            if ($relativePath && $quality) {
                $image = \Img::make($relativePath);
                $image->save(str_replace('.png', '.jpg', $relativePath), $quality);
            } else {
                $response = [
                    'error' => false,
                    'message' => 'Nothing changed!'
                ];
            }
        } else {
            $response = [
                'error' => true,
                'message' => 'Denied!!!'
            ];
        }

        return Response::json($response, 200);
    }

    public function getResize($hash)
    {
        $response = [
            'error' => false,
            'message' => 'Resize User Avatar Images!'
        ];

        if ($hash == 'VibloTeam2015') {
            $users = User::where('avatar_id', '<>', User::DEFAULT_AVATAR_ID)->get();

            foreach ($users as $user) {
                $image = Image::find($user->avatar_id);
                $imageService = new ImageService($user);
                $imageService->createAvatar($image->name);
            }

            $response['total'] = $users->count();
        } else {
            $response = [
                'error' => true,
                'message' => 'Denied!!!'
            ];
        }

        return Response::json($response, 200);
    }

}
