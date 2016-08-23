<?php

use App\Services\GroupService;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @param \App\Data\System\User $username
 * @param array $attributes
 * @return string
 */
function link_to_user($username, $attributes = [])
{
    if (is_object($username)) {
        $user = $username;
    } else {
        $user = \App\Data\System\User::findByUsername($username);
    }
    return link_to_action('UsersController@getShow', $user->name, ['username' => $user->username], $attributes);
}

/**
 * @param \App\Data\Blog\Post|mixed $post
 * @return string
 */
function link_to_post($post)
{
    if (isset($post->user->username)) {
        $username = $post->user->username;
    } else {
        $username = $post->username;
    }

    $param = ['username' => $username, 'encryptedId' => $post->encrypted_id];

    return route('post.detail', $param);
}

/**
 * @param string $title
 * @param array $param
 * @param array $attributes
 * @return string
 */
function link_to_post_encrypted_id($title, $param, $attributes = [])
{
    return link_to_action('PostsController@show', $title, $param, $attributes);
}

/**
 * @param Category $category
 * @param array $attributes
 * @return string
 */
function link_to_category($category, $attributes = [])
{
    return link_to_action('CategoriesController@show', $category->name, ['name' => $category->short_name], $attributes);
}

/**
 * @param User $user
 * @param int|null $size
 * @param string $class
 * @return string
 */
function user_img_tag($user, $size = null, $class = '')
{
    if ($size === null) {
        $size = 20;
    }
    return '<img alt=' . $user->username . ' class=' . $class . ' avatar src=' . $user->getAvatar($size)
    . ' width=' . $size . ' height=' . $size . '>';
}

function user_img_url($user, $size = 20)
{
    if (!empty($user)) {
        $imageUrl = $user->getAvatar($size);
        $imageUrl = (strpos($imageUrl, '/uploads/') === 0) ? URL::to('/') . $imageUrl : $imageUrl;
        return $imageUrl;
    } else {
        return URL::to('/');
    }
}

/**
 * @param User $user
 * @return string
 */
function url_to_user($user, $param = [])
{
    if (is_array($user)) {
        $username = $user['username'];
    } else {
        $username = (is_null($user) || empty($user)) ? '' : $user->username;
    }
    $param += ['username' => $username];
    return URL::action('UsersController@getShow', $param);
}

/**
 * @param \App\Data\Blog\Post $post
 * @return mixed
 */
function url_to_post($post, $param = [])
{
    if (isset($post->user->username)) {
        $username = $post->user->username;
    } else {
        $username = $post->username;
    }
    $param += ['username' => $username, 'encryptedId' => $post->encrypted_id];
    return urldecode(route('post.detail', $param));
}

/**
 * @param \App\Data\Blog\Category $category
 * @return mixed
 */
function url_to_category($category)
{
    if (is_string($category)) {
        $categoryName = $category;
    } else {
        $categoryName = $category->short_name;
    }
    return URL::action('CategoriesController@show', $categoryName);
}

/**
 * Encrypt id using Hashids
 * @param $id
 */
function encrypt_id($id)
{
    return Hashids::encode($id);
}

/**
 * Decrypt id using Hashids. If it is invalid, return 0
 * @param string $string
 * @return int $id
 */
function decrypt_id($string)
{
    if (!$string) {
        return 0;
    }

    try {
        $ids = Hashids::decode($string);
    } catch (Exception $exception) {
        return 0;
    }

    if (isset($ids[0]) && is_numeric($ids[0]) && $ids[0] > 0) {
        return $ids[0];
    }

    return 0;
}

/**
 * Return to last url entered after successful login
 * @return bool
 */
function initReturnUrl()
{
    $request = new Illuminate\Http\Request();
    if ($request->has('return')) {
        Session::put('returnUrl', $request->has('return'));
        return true;
    }
    return false;
}

/**
 * @param $text
 * @return string
 */
function bold($text)
{
    return '<strong>' . $text . '</strong>';
}

/**
 * Set title for web page
 * @param string $title Title of page
 * @param string $brand Brand
 * @param string $prefix Prefix after title
 * @return string $title
 */
function setTitle($title = null, $brand = null, $prefix = '|')
{
    if (is_null($brand)) {
        $title = htmlentities($title) . $prefix . Config::get('app.app_name');
    } else {
        $title = htmlentities($title) . $prefix . $brand;
    }
    return $title;
}

function setDescription($description, $limit = 400, $prefix = '...')
{
    if (strlen($description) <= $limit) {
        return htmlentities($description);
    }
    return htmlentities(substr($description, 0, $limit) . $prefix);
}

function markdown_escape($content)
{
    $content = htmlentities($content);
    $htmlAllowedTags = Config::get('markdown.html_allowed_tags');
    $content = preg_replace_callback('#&lt;[/]*(' . implode('|', $htmlAllowedTags) . ')([ ].*)*&gt;#i',
        function ($matches) {
            return html_entity_decode($matches[0]);
        }, $content);
    // Youtube & Vimeo embeds
    $content = preg_replace_callback('#\[(youtube|vimeo|slideshare)(\-(\d+)x(\d+))?\]\((.*)\)#i', 'embed_markdown', $content);
    return $content;
}

function cleanHtml($content)
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Trusted', true);
    $config->set('CSS.AllowedProperties', array());
    $purifier = new \HTMLPurifier($config);
    return $purifier->purify($content);
}

function embed_markdown($matches)
{
    if (count($matches) == 6) {
        $width = empty($matches[3]) ? 688 : $matches[3];
        $height = empty($matches[4]) ? 387 : $matches[4];
        $id = $matches[5];
        switch ($matches[1]) {
            case 'youtube':
                return '<iframe src="https://www.youtube.com/embed/' . get_youtube_id($id) . '" ' .
                'width="' . $width . '" height="' . $height . '"' .
                ' frameborder="0" allowfullscreen></iframe>';
            case 'vimeo':
                return '<iframe src="https://player.vimeo.com/video/' . get_vimeo_id($id) . '" ' .
                'width="' . $width . '" height="' . $height . '"' .
                ' frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
            case 'slideshare':
                return '<iframe src="//www.slideshare.net/slideshow/embed_code/' . $id . '"' .
                ' width="' . $width . '" height="' . $height . '"' .
                ' frameborder="0" marginwidth="0" marginheight="0" scrolling="no" allowfullscreen ' .
                'style="border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%;"></iframe>';
        }
    }
    return $matches[0];
}

function get_youtube_id($url)
{
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
        return $match[1];
    }
    return $url;
}

function get_vimeo_id($url)
{
    if (preg_match('%(?:https?://)?(?:www\.)?vimeo.com/(?:channels/(?:\w+/)?|groups/([^/]*)/videos/|album/(\d+)/video/|)(\d+)(?:$|/|\?)%i', $url, $match)) {
        return $match[3];
    }
    return $url;
}

function vn_to_latin($string, $toLowerCase = false)
{
    $unicode = ['a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ'];
    foreach ($unicode as $nonUnicode => $uni) {
        $string = preg_replace('/(' . $uni . ')/i', $nonUnicode, $string);
    }
    if ($toLowerCase) {
        $string = strtolower($string);
    }
    return $string;
}

function remove_special_characters($string, $keep = null)
{
    $characters = ['!', '"', '£', '$', '`', '%', '^', '&', '*', '(', ')', '-', '+', '=',
        '\\', '/', '[', ']', '{', '}', ';', ':', '@', '#', '~', '<', ',', '>',
        '.', '?', '|', ' ', '\''];

    if ($keep != null) {
        foreach ($characters as $key => $char) {
            if (in_array($char, $keep)) {
                unset($characters[$key]);
            }
        }
    }

    return str_replace($characters, '', $string);
}

function convert_to_alias($name)
{
    $alias = Config::get('character.alias');
    $check = true;
    foreach ($alias as $key => $val) {
        if ($name == $key) {
            $name = $val;
            $check = false;
            break;
        }
    }
    if ($check) {
        $replace = Config::get('character.replace');
        foreach ($replace as $symbol => $text) {
            $pos = strrpos($name, $symbol);
            if ($pos !== false) {
                $name = str_replace($symbol, $text, $name);
            }
        }
    }

    return $name;
}

function convert_to_short_name($name, $keep = null)
{
    $shortName = vn_to_latin($name, true);
    $shortName = remove_special_characters($shortName, $keep);
    return $shortName;
}

function convert_to_japanese_date($date, $language = null, $format = null)
{
    $label = '';
    $convertFormat = trans('datetime.format.date');
    if ($language != null) {
        $label = (in_array($language, Config::get('country_language.VN'))) ? trans('labels.date_label') . ' ' : '';
    }
    if ($format != null) {
        $convertFormat = $format;
    }

    return $label . Carbon\Carbon::parse($date)->format($convertFormat);
}

function url_to_themes($themeName, $lang = null)
{
    if (empty($lang)) {
        $lang = getThemeLanguage();
    }
    return URL::to('theme/' . $lang . '/' . $themeName);
}

function url_to_sub_theme($subjectThemeName, $subThemeName)
{
    $lang = getThemeLanguage();
    return URL::to('theme/' . $lang . '/' . $subjectThemeName . '/' . $subThemeName);
}

function replace_special_characters($string, $replaceBy = '_')
{
    $characters = ['!', '"', '£', '$', '`', '%', '^', '&', '*', '(', ')', '-', '+', '=',
        '\\', '/', '[', ']', '{', '}', ';', ':', '@', '#', '~', '<', ',', '>',
        '.', '?', '|', ' ', '\''];
    $string = str_replace($characters, ' ', $string);
    $string = preg_replace('!\s+!', ' ', $string);
    $string = str_replace(' ', $replaceBy, $string);
    return $string;
}

function url_to_group_category($groupEncryptedId, $category)
{
    $categoryName = is_string($category) ? $category : $category->short_name;

    return URL::action('GroupCategoriesController@getGroupCategoryPosts', [$groupEncryptedId, $categoryName]);
}

function url_to_group($group)
{
    $groupEncryptedId = $group->shortname;
    return route('getGroupFillter', [$groupEncryptedId, GroupService::GROUP_FILTER_ALL]);
}

function full_image_link($imgLink)
{
    return URL::to($imgLink);
}

function link_to_user_with_full_name($user)
{
    if (isset($user->profile['first_name']) && $user->profile['first_name'] != null
        && isset($user->profile['last_name']) && $user->profile['last_name']
    ) {
        return $user->profile['first_name'] . ' ' . $user->profile['last_name'];
    } else {
        return isset($user->name) && $user->name != null ? $user->name : '';
    }
}

function get_full_name_of_user($user)
{
    return empty($user->profile['first_name']) ? $user->name :
        $user->profile['first_name'] . ' ' . $user->profile['last_name'];
}

function check_image_file_from($url)
{
    $imageExt = ['jpeg', 'exif', 'tiff', 'rif', 'gif',
        'bmp', 'png', 'ppm', 'pgm', 'pbm', 'pnm',
        'webp', 'hdr', 'bpg', 'jpg', 'tif', 'jif',
        'jfif', 'jp2', 'jpx', 'j2k', 'j2c'];

    $splitUrl = explode('.', $url);
    $extensionFile = end($splitUrl);

    return in_array(strtolower($extensionFile), $imageExt);
}

function set_cookie($name, $value, $expires)
{
    $defaultTime = time() + App\Services\LanguageService::COOKIE_TIME;
    $expires = !empty($expires) ? $expires : $defaultTime;
    Cache::put($name, $value, $expires);
    return true;
}

function get_cookie($name)
{
    return Cache::has($name) ? Cache::get($name) : null;
}

function delete_cookie($name)
{
    Cache::forget($name);
    return true;
}

function group_img_link($group, $type)
{
    $imageField = $type . '_img';
    $imageCropField = $imageField . '_crop';
    $blankImage = $type == 'profile' ? Config::get('image.group_image.profile_no_image') :
        Config::get('image.group_image.group_cover_default');

    return asset(!empty($group[$imageCropField]) ? $group[$imageCropField] :
        (!empty($group[$imageField]) ? $group[$imageField] : $blankImage));
}

function version($path)
{
    $file = public_path($path);
    if (file_exists($file)) {
        return $path . '?version=' . filemtime($file);
    } else {
        throw new Exception('The file "' . $path . '" cannot be found in the public folder');
    }
}

function loadCSS($path, $withVersion, $asynchronous = false, $extraOptions = [])
{
    $asynchronousOptions = $asynchronous ? ['media' => 'none', 'onload' => "if(media!='all')media='all'"] : [];
    $styleStr = '';

    if (is_array($path)) {
        foreach ($path as $singlePath) {
            $styleStr .= HTML::style(
                $withVersion ? version($singlePath) : $singlePath,
                array_merge($extraOptions, $asynchronousOptions)
            );
        }
    } else {
        $styleStr = HTML::style(
            $withVersion ? version($path) : $path,
            array_merge($extraOptions, $asynchronousOptions)
        );
    }

    return $styleStr;
}

function is_url($url)
{
    $url = trim($url);
    $url = stripslashes($url);
    $url = htmlspecialchars($url);

    if (preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $url)) {
        return true;
    }
    return false;
}


/**
 * get theme page lang
 * @return  string
 */
function getThemeLanguage()
{
    if (\Cache::has('theme_short_name')) {
        return \Cache::get('theme_short_name');
    }
    $user = \App\Facades\Authority::getCurrentUser();
    if (!empty($user)) {
        $currentUserSystemLang = $user->setting->lang;
    } else if (Request::segment(1) == 'theme' && in_array(Request::segment(2),
        array_keys(\App\Services\LanguageService::getSystemLangMinOptions()))) {
        $currentUserSystemLang = Request::segment(2);
    } else {
        $currentUserSystemLang = get_cookie('systemLang');
        if (empty($currentUserSystemLang)) {
            $detectLanguage = \App\Services\LanguageService::getDetectedCountryAndLang();
            $currentUserSystemLang = empty(head($detectLanguage['language']))
                ? \App\Services\LanguageService::getSystemLang() : head($detectLanguage['language']);
        }
    }
    Cache::put('theme_short_name', $currentUserSystemLang, 10080);
    return $currentUserSystemLang;
}

/**
 * @param $ret - content of the post
 * @return string
 */
function makeClickableLinks($ret)
{
    $ret = ' ' . $ret;
    $ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
    $ret = preg_replace('#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', '$1$3</a>', $ret);
    $ret = trim($ret);
    return $ret;
}

/**
 * change link if true
 * @param $matches
 * @return string|url
 */
function _make_url_clickable_cb($matches)
{
    $ret = '';
    $url = $matches[2];
    if (empty($url))
        return $matches[0];
    if (in_array(substr($url, -1), array('.', ',', ';', ':')) === true) {
        $ret = substr($url, -1);
        $url = substr($url, 0, strlen($url) - 1);
    }
    return $matches[1] . '<a href=' . $url . ' rel="nofollow">' . $url . '</a>' . $ret;
}

/**
 * check slug exist
 * @param $title
 * @param $locale
 * @return string
 */
function uniquePostSlug($title, $locale)
{
    $helper = new \App\Services\SlugService();
    $slug = $helper->sanitize_title($title, $locale);
    return $slug;
}
