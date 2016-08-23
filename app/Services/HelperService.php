<?php namespace App\Services;

use App\Data\Blog\Category;
use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupSeries;
use App\Data\Blog\GroupSetting;
use App\Data\Blog\GroupUser;
use View;
use Route;
use App\Data\Blog\Social;
use App\Data\Blog\Notification;
use GrahamCampbell\Markdown\Facades\Markdown;
use App\Data\Blog\Post;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use App\Data\Blog\GroupSeriesItem;
use HTML;

class HelperService
{
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const URL = 'url';
    const LOCATION = 'location';
    const OCCUPATION = 'occupation';
    const ORGANIZATION = 'organization';
    const DESCRIPTION = 'description';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const USERNAME = 'username';
    const SOCIAL = 'social';
    const FACEBOOK = 'facebook';
    const GITHUB = 'github';
    const GOOGLE = 'google';
    const SIDEBAR_ACTIVE_CLASS = 'active';
    const WORK_EMAIL = 'work_email';

    const SOCIAL_FACEBOOK_LINK = 'https://www.facebook.com/%s';
    const SOCIAL_GOOGLE_LINK = 'https://plus.google.com/%s';
    const SOCIAL_GITHUB_LINK = 'https://github.com/%s';

    const THUMBNAIL_DEFAULT = 'img/pic-%s.png';
    const THUMBNAIL_RIGHT_SIZE = 65;

    public static function sidebarActive($expectAction)
    {
        $currentAction = Route::currentRouteAction();
        if (empty($currentAction) || empty($expectAction)) {
            return '';
        }
        return ($currentAction == $expectAction) ? self::SIDEBAR_ACTIVE_CLASS : '';
    }

    public static function snakeCaseNoSpace($str)
    {
        return snake_case(preg_replace('/\s+/', '', $str));
    }

    public static function getSettingByField($field)
    {
        switch ($field) {
            case self::EMAIL:
                return SettingService::EMAIL;
            case self::SOCIAL:
                return SettingService::SOCIAL_ACCOUNTS;
            case self::URL:
                return SettingService::URL;
            case self::LOCATION:
                return SettingService::LOCATION;
            case self::DESCRIPTION:
                return SettingService::DESCRIPTION;
            case self::ORGANIZATION:
                return SettingService::ORGANIZATION;
            case self::OCCUPATION:
                return SettingService::OCCUPATION_INFO;
            case self::PHONE:
                return SettingService::PHONE;
            case self::WORK_EMAIL:
                return SettingService::WORK_EMAIL;
        }
    }

    public static function showAll($setting, $type)
    {
        if (!empty($setting)) {
            switch ($type) {
                case SettingService::EMAIL:
                    return $setting->display_email;
                case SettingService::SOCIAL_ACCOUNTS:
                    return $setting->display_social_accounts;
                case SettingService::BASIC_PROFILE:
                    return $setting->display_basic_profile;
                case SettingService::OCCUPATION_INFO:
                    return $setting->display_occupation_info;
                case SettingService::URL:
                    return $setting->display_url_info;
                case SettingService::LOCATION:
                    return $setting->display_location_info;
                case SettingService::DESCRIPTION:
                    return $setting->display_description_info;
                case SettingService::ORGANIZATION:
                    return $setting->display_organization_info;
                case SettingService::PHONE:
                    return $setting->display_phone_info;
                case self::FACEBOOK:
                    return $setting->display_facebook_info;
                case self::GITHUB:
                    return $setting->display_github_info;
                case self::GOOGLE:
                    return $setting->display_google_info;
                case SettingService::WORK_EMAIL:
                    return $setting->display_work_email;
            }
        }
        return false;
    }

    public static function showAttribute($setting, $object, $attribute)
    {
        $type = self::getSettingByField($attribute);
        if (!self::showAll($setting, $type)) {
            return false;
        }

        if (($attribute == self::LOCATION) && !is_null($object->city_country_description)) {
            return true;
        }

        if (empty($object->$attribute)) {
            return false;
        }

        return true;
    }

    public static function getSocialLink($social, $uid)
    {
        switch ($social) {
            case Social::FACEBOOK:
                return sprintf(self::SOCIAL_FACEBOOK_LINK, $uid);
            case Social::GOOGLE:
                return sprintf(self::SOCIAL_GOOGLE_LINK, $uid);
            case Social::GITHUB:
                return sprintf(self::SOCIAL_GITHUB_LINK, $uid);
        }
        return '';
    }

    public static function getSkillBarchart($userSkills)
    {
        $data = [
            'labels' => [],
            'values' => [],
        ];
        if (empty($userSkills)) {
            return json_encode($data);
        }
        foreach ($userSkills as $userSkill) {
            if (isset($userSkill->skill)) {
                $data['labels'][] = $userSkill->skill . ' (' . $userSkill->category . ')';
                $data['values'][] = floatval($userSkill->year);
            }
        }
        return json_encode($data);
    }

    public static function getSkillList($user)
    {
        $data = [];
        $userSkills = $user->skills;
        if ($userSkills->isEmpty()) {
            return $data;
        }
        foreach ($userSkills as $userSkill) {
            if ($userSkill->skill != null) {
                $data[] = [
                    'category' => $userSkill->skill->skillCategory->name,
                    'skill' => $userSkill->skill->name,
                    'year' => floatval($userSkill->year)
                ];
            }
        }
        return $data;
    }

    public static function notifyMessage($type, $notification)
    {
        $sender = $notification->sender;
        $linkToSender = 'User locked';
        if ($sender) {
            $linkToSender = link_to_user($sender);
        }

        $post = $notification->post;
        $linkToPost = null;
        if ($post) {
            $linkToPost = link_to_post($post);
        }

        $linkToGroup = null;
        $group = $notification->group;
        if ($group) {
            $linkToGroup = '<a href="' . url_to_group($group) . '">' . mb_strimwidth($group->name, 0, 38, '...') . '</a>';
        }
        (new Category())->withTrashed();
        return self::getNotifyMessage($type, $linkToSender, $linkToPost, $linkToGroup, $notification, $group);
    }

    public static function getNotifyMessage($type, $linkToSender, $linkToPost, $linkToGroup, $notification, $group)
    {
        switch ($type) {
            case Notification::TYPE_FOLLOW:
                return trans('messages.notification.follow', ['sender' => $linkToSender]);
            case Notification::TYPE_STOCK:
                return trans('messages.notification.stock', ['sender' => $linkToSender, 'link' => $linkToPost]);
            case Notification::TYPE_COMMENT:
                return trans('messages.notification.comment', ['sender' => $linkToSender, 'link' => $linkToPost]);
            case Notification::TYPE_MENTION:
                return trans('messages.notification.mention', ['sender' => $linkToSender, 'link' => $linkToPost]);
            case Notification::TYPE_ADD_MEMBER_TO_GROUP:
                return trans('messages.notification.added_to_group', ['sender' => $linkToSender, 'link' => $linkToGroup]);
            case Notification::TYPE_APPROVE_POST_IN_GROUP:
                $postNumberNeedApproval = GroupPost::where('group_id', $group->id)
                    ->whereNotNull('post_id')
                    ->where('approved', GroupPost::GROUP_POST_NOT_APPROVE)
                    ->count();
                if ($postNumberNeedApproval > 0) {
                    return trans('messages.notification.new_post_group_need_approval',
                        ['sender' => $linkToSender, 'total' => $postNumberNeedApproval, 'link' => $linkToGroup]);
                }
                return trans('messages.notification.no_post_request', ['link' => $linkToGroup]);
            case Notification::TYPE_APPROVE_MEMBER_IN_GROUP:
                $userNumberNeedApproval = GroupUser::where('group_id', $group->id)
                    ->where('status', GroupUser::STATUS_WAITING_APPROVE)
                    ->count();
                if ($userNumberNeedApproval > 0) {
                    return trans('messages.notification.new_member_group_need_approval', ['sender' => $linkToSender,
                        'total' => $userNumberNeedApproval, 'link' => $linkToGroup]);
                }
                return trans('messages.notification.no_member_request', ['link' => $linkToGroup]);
            case Notification::TYPE_POST_IN_GROUP:
                return trans('messages.notification.posted_in_group',
                    ['sender' => $linkToSender, 'post' => $linkToPost, 'link' => $linkToGroup]);
            case Notification::TYPE_REPORT_POST:
                return trans('messages.notification.has_reported_post',
                    ['sender' => $linkToSender, 'post' => $linkToPost]);
            case Notification::TYPE_FEEDBACK:
                $linkToFeedback = '<a href ="' . URL::action('FeedbacksController@index') . '" >feedback</a>';
                if ($notification->sender_id != 0) {
                    return trans('messages.notification.had_feedback',
                        ['sender' => $linkToSender, 'feedback' => $linkToFeedback]);
                }
                return trans('messages.notification.had_feedback',
                    ['sender' => trans('labels.not_registered_user'), 'feedback' => $linkToFeedback]);
            case Notification::TYPE_FOLLOWING_POST:
                return trans('messages.notification.following_posted',
                    ['sender' => $linkToSender, 'link' => $linkToPost]);
            default:
                return '';
        }
    }


    public static function notifyRawMessage($notification)
    {
        $type = $notification->type;
        $sender = $notification->sender;
        $username = null;
        if ($sender) {
            $username = '<span class="author-name">' . $sender->username . '</span>';
        }
        $post = $notification->post;
        $postTitle = null;
        if ($post) {
            $postTitle = mb_strimwidth($post->title, 0, 38, '...');
            $postTitle = HTML::entities($postTitle);
            $postTitle = '<span class="post-name">' . $postTitle . '</span>';
        }

        $group = $notification->group;
        $groupName = null;
        if ($group) {
            $groupName = mb_strimwidth($group->name, 0, 38, '...');
            $groupName = HTML::entities($groupName);
            $groupName = '<span class="post-name break-word">' . $groupName . '</span>';
        }
        return self::getNotifyRawMessage($type, $username, $postTitle, $groupName, $notification);
    }

    public static function getNotifyRawMessage($type, $username, $postTitle, $groupName, $notification)
    {
        switch ($type) {
            case Notification::TYPE_FOLLOW:
                return trans('messages.notification.follow', ['sender' => $username]);
            case Notification::TYPE_STOCK:
                return trans('messages.notification.stock', ['sender' => $username, 'link' => $postTitle]);
            case Notification::TYPE_COMMENT:
                return trans('messages.notification.comment', ['sender' => $username, 'link' => $postTitle]);
            case Notification::TYPE_MENTION:
                return trans('messages.notification.mention', ['sender' => $username, 'link' => $postTitle]);
            case Notification::TYPE_ADD_MEMBER_TO_GROUP:
                return trans('messages.notification.added_to_group', ['sender' => $username, 'link' => $groupName]);
            case Notification::TYPE_APPROVE_POST_IN_GROUP:
                return trans('messages.notification.new_post_group_need_approval',
                    ['sender' => $username, 'link' => $groupName]);
            case Notification::TYPE_APPROVE_MEMBER_IN_GROUP:
                return trans('messages.notification.new_member_group_need_approval',
                    ['sender' => $username, 'link' => $groupName]);
            case Notification::TYPE_POST_IN_GROUP:
                return trans('messages.notification.new_post_in_group',
                    ['sender' => $username, 'link' => $groupName]);
            case Notification::TYPE_REPORT_POST:
                return trans('messages.notification.reported_post',
                    ['sender' => $username, 'link' => $postTitle]);
            case Notification::TYPE_FEEDBACK:
                if ($notification->sender_id != 0) {
                    return trans('messages.notification.has_send_feedback', ['sender' => $username]);
                }
                return trans('messages.notification.has_send_feedback',
                    ['sender' => trans('labels.not_registered_user')]);

            case Notification::TYPE_FOLLOWING_POST:
                return trans('messages.notification.following_posted', ['sender' => $username, 'link' => $postTitle]);
            default:
                return null;
        }
    }

    public static function notifyLink($notification)
    {
        $type = $notification->type;
        $sender = $notification->sender;
        $linkToSender = '#';
        if ($sender) {
            $linkToSender = url_to_user($sender);
        }
        $post = $notification->post;
        $linkToPost = null;
        if ($post) {
            $linkToPost = url_to_post($post);
        }
        $linkToGroup = '#';
        $group = $notification->group;
        if ($group) {
            $linkToGroup = url_to_group($group);
        }
        return self::getLink($type, $linkToSender, $linkToPost, $linkToGroup);
    }

    public static function getLink($type, $linkToSender, $linkToPost, $linkToGroup)
    {
        switch ($type) {
            case Notification::TYPE_FOLLOW:
                return $linkToSender;
            case Notification::TYPE_STOCK:
            case Notification::TYPE_COMMENT:
            case Notification::TYPE_MENTION:
            case Notification::TYPE_POST_IN_GROUP:
            case Notification::TYPE_FOLLOWING_POST:
                return $linkToPost;
            case Notification::TYPE_ADD_MEMBER_TO_GROUP:
            case Notification::TYPE_APPROVE_POST_IN_GROUP:
            case Notification::TYPE_APPROVE_MEMBER_IN_GROUP:
                return $linkToGroup;
            case Notification::TYPE_REPORT_POST:
                return URL::action('ReportsController@index');
            case Notification::TYPE_FEEDBACK:
                return URL::action('FeedbacksController@index');
            default:
                return '#';
        }
    }

    public static function notifyTopPostMessage($post)
    {
        $linkToAuthor = link_to_user($post->username);
        $linkToPost = link_to_post($post);
        $view = trans('messages.notification.post', ['author' => $linkToAuthor, 'post' => $linkToPost]);
        return $view;
    }

    public static function getHeaderCategories()
    {
        return CategoryService::getHeaderCategories();
    }

    public static function showCategoryLabel($category)
    {
        $classCategories = ['js', 'html', 'css', 'ios', 'android', 'php', 'ruby', 'java', 'objective-c', 'vi',
            'python', 'git', 'shell', 'cpp', 'jquery', 'github', 'c', 'linux', 'cs', 'mac', 'mysql', 'emac', 'nodejs',
            'rails', 'chrome', 'pear', 'regex', 'apache', 'centos', 'csharp', 'ssh', 'nginx', 'ubuntu', 'mongodb',
            'swift', 'agile', 'scheme', 'heroku', 'win', 'unity', 'net', 'sql', 'no-sql', 'css3', 'html5', 'other'];
        if (in_array(strtolower($category->short_name), $classCategories)) {
            return $category->short_name;
        }
        return $classCategories[count($classCategories) - 1];
    }

    public static function getPostThumbnail($post, $size = null)
    {
        if (!empty($post->thumbnail) && file_exists(public_path() . $post->thumbnail)) {
            return (strpos($post->thumbnail, '/uploads/') === 0) ? URL::to('/') . $post->thumbnail : $post->thumbnail;
        }
        $categories = $post->categories;
        $config = Config::get('image');
        $image = $config['group_image']['post_thumbnail_default'];
        $img_size = $config['category']['upload_dir_320'];
        if ($size == self::THUMBNAIL_RIGHT_SIZE) {
            $img_size = $config['category']['upload_dir_65'];
        }
        foreach ($categories as $category) {
            if (!is_null($category->img)) {
                $image = $category->img;
            } else {
                $img = $img_size . '/' . $category->shortName . '.png';
                if (file_exists($img)) {
                    return URL::to('/') . '/' . $img;
                }
            }
        }
        return URL::to('/') . '/' . $image;
    }

    public static function getGroupPostThumbnail($post)
    {
        if (!empty($post->thumbnail)) {
            return $post->thumbnail;
        }

        $categories = $post->categories;
        $config = Config::get('image');
        $image = $config['group_image']['post_thumbnail_default'];

        foreach ($categories as $category) {
            if (!is_null($category->img)) {
                $image = $category->img;
            } else {
                $img = $config['category']['upload_dir'] . '/' . $category->shortName . '.png';
                if (file_exists($img)) {
                    return URL::to('/') . '/' . $img;
                }
            }
        }
        return URL::to('/') . '/' . $image;
    }

    public static function getSlidePostThumbnail($post)
    {
        if (!empty($post->thumbnail)) {
            return $post->thumbnail;
        }
        $categories = $post->categories;
        $config = Config::get('image');
        $image = $config['theme_thumb']['slide_thumbnail_default'];
        foreach ($categories as $category) {
            if (!is_null($category->img)) {
                $image = $category->img;
            } else {
                $img = $config['category']['upload_dir'] . '/' . $category->shortName . '.png';
                if (file_exists($img)) {
                    return URL::to('/') . '/' . $img;
                }
            }
        }
        return URL::to('/') . '/' . $image;
    }

    public static function getGroupCoverImage($group)
    {
        if (!empty($group->cover_img)) {
            return $group->cover_img;
        } else {
            $config = Config::get('image');
            $image = $config['group_image']['group_cover_default'];
            return $image;
        }
    }

    public static function myPluralizer($text, $count, $locale)
    {
        if ($locale == 'en') {
            return $count > 1 ? str_plural($text) : $text;
        } else {
            return $text;
        }
    }

    public static function getSeriesListItems($groupSeriesId, $edit = true)
    {
        $urls = GroupSeriesItem::where('group_series_id', $groupSeriesId)->orderBy('order_item', 'asc')->get()->toArray();
        $result = '';

        foreach ($urls as $urlDetail) {
            $data = [
                'id' => $urlDetail['id'],
                'image' => $urlDetail['thumbnail_img'],
                'title' => $urlDetail['title'],
                'url' => $urlDetail['url'],
                'description' => $urlDetail['description'],
                'type' => $urlDetail['type'],
                'post_id' => $urlDetail['post_id'],
            ];
            $result .= self::getSeriesListViewItems($urlDetail, $edit, $data);
        }

        return $result;
    }

    public static function getSeriesListViewItems($urlDetail, $edit, $data)
    {
        switch ($urlDetail['type']) {
            case GroupSeries::URL_TYPE_POST:
                $post = Post::with('user')->where('id', $urlDetail['post_id'])->first();
                return View::make('groupseries._a_post_item', ['edit' => $edit, 'data' => $data, 'post' => $post])->render();
            case GroupSeries::URL_TYPE_LINK:
                return View::make('groupseries._an_other_url_item', ['edit' => $edit, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_IMAGE:
                return View::make('groupseries._a_image_item', ['edit' => $edit, 'url' => $urlDetail['url'], 'id' => $urlDetail['id']])->render();
            case GroupSeries::URL_TYPE_YOUTUBE:
                parse_str(parse_url($data['url'], PHP_URL_QUERY), $matches);
                return View::make('groupseries._a_youtube_item', ['edit' => $edit, 'id' => $matches['v'], 'data' => $data])->render();
            case GroupSeries::URL_TYPE_QUOTE:
                $data['quote'] = $urlDetail['description'];
                return View::make('groupseries._a_quote_item', ['edit' => $edit, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_TEXT:
                $data['text'] = $urlDetail['description'];
                return View::make('groupseries._a_text_item', ['edit' => $edit, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_HEADING:
                $data['text'] = $urlDetail['description'];
                return View::make('groupseries._a_heading_item', ['edit' => $edit, 'data' => $data])->render();
            default:
                return null;
        }
    }

    public static function getSeriesListItemsToEdit($groupSeriesId)
    {
        $urls = GroupSeriesItem::where('group_series_id', $groupSeriesId)->orderBy('order_item', 'asc')->get()->toArray();
        $result = '';
        foreach ($urls as $urlDetail) {
            $data = [
                'id' => $urlDetail['id'],
                'image' => $urlDetail['thumbnail_img'],
                'title' => $urlDetail['title'],
                'url' => $urlDetail['url'],
                'description' => $urlDetail['description'],
                'type' => $urlDetail['type'],
                'post_id' => $urlDetail['post_id'],
            ];
            $result .= self::getSeriesListViewItemsToEdit($urlDetail, $data);
        }
        return $result;
    }

    public static function getSeriesListViewItemsToEdit($urlDetail, $data)
    {
        switch ($urlDetail['type']) {
            case GroupSeries::URL_TYPE_POST:
                $post = Post::with('user')->where('id', $urlDetail['post_id'])->first();
                $preview = View::make('groupseries._a_post_item_edit', ['data' => $data, 'post' => $post])->render();
                return View::make('groupseries.elements._an_input_link', ['preview' => $preview, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_LINK:
                $preview = View::make('groupseries._an_other_url_item_edit', ['data' => $data])->render();
                return View::make('groupseries.elements._an_input_link', ['preview' => $preview, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_IMAGE:
                $preview = View::make('groupseries._a_image_item_edit', ['url' => $urlDetail['url'], 'id' => $urlDetail['id']])->render();
                return View::make('groupseries.elements._an_input_image', ['preview' => $preview, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_YOUTUBE:
                parse_str(parse_url($data['url'], PHP_URL_QUERY), $matches);
                $id = $matches['v'];
                $preview = View::make('groupseries._a_youtube_item_edit', ['id' => $id, 'data' => $data])->render();
                return View::make('groupseries.elements._an_input_link', ['preview' => $preview, 'data' => $data])->render();
            case GroupSeries::URL_TYPE_QUOTE:
                $data['quote'] = $urlDetail['description'];
                return View::make('groupseries.elements._an_input_quote', ['data' => $data])->render();
            case GroupSeries::URL_TYPE_TEXT:
                $data['text'] = $urlDetail['description'];
                return View::make('groupseries.elements._an_input_text', ['data' => $data])->render();
            case GroupSeries::URL_TYPE_HEADING:
                $data['text'] = $urlDetail['description'];
                return View::make('groupseries.elements._an_input_heading', ['data' => $data])->render();
            default:
                return null;
        }
    }

    public static function formatURL($url)
    {
        $http = substr($url, 0, 4);
        if ($http == 'http') {
            return $url;
        } else {
            return 'http://' . $url;
        }
    }

    public static function getSkillListColour($index = 0)
    {
        $colours = ['#776B19', '#e81c6e', '#7c7c7c', '#00aff2', '#aaaaaa', '#611bc9', '#829813', '#364f8a', '#60cb94',
            '#cf263b', '#2471bb', '#7fc398', '#d2c66a', '#2109dc', '#66ad29', '#9a9754', '#640cdf', '#257683', '#d51e05'
            , '#4bb36e', '#e7408a', '#1ef173', '#1756bc', '#cff215', '#15c2fb', '#f010ab', '#844a0', '#c34021',
            '#3e4cf2', '#a28f5c', '#a9d528', '#7b1e43', '#a5401c'];
        return $colours[$index % count($colours)];
    }

    public static function getDefaultPrivacy($groupId)
    {
        $groupSetting = GroupSetting::where('group_id', $groupId)->first();
        $groupPrivacy = null;
        if ($groupSetting) {
            switch ($groupSetting->privacy_flag) {
                case GroupSetting::PRIVACY_PUBLIC:
                    $groupPrivacy = GroupPost::GROUP_POST_PUBLIC;
                    break;
                case GroupSetting::PRIVACY_PROTECTED:
                    $groupPrivacy = null;
                    break;
                case GroupSetting::PRIVACY_PRIVATE:
                    $groupPrivacy = GroupPost::GROUP_POST_PRIVATE;
                    break;
                default:
                    $groupPrivacy = null;
                    break;
            }
        }
        return $groupPrivacy;
    }

    public static function getPostDescription($content, $limit = 700)
    {
        $content = strip_tags($content);

        if (mb_strlen($content) >= $limit) {
            return mb_substr($content, 0, strpos($content, ' ', $limit)) . '...';
        }

        return $content;
    }

    public static function getStripTagGroupDescription($description)
    {
        $content = Markdown::convertToHtml(markdown_escape($description));
        if (mb_strlen($content) >= 90) {
            return mb_substr($content, 0, strpos($content, ' ', 90)) . '...';
        }
        return $content;
    }

    public static function getImageBy($link, $type = ImageService::DEVICE_MOBILE)
    {
        $explodeLink = explode('/', $link);
        $dir = self::getDirImgStore($type);
        if (!in_array($explodeLink[0], ['http:', 'https:'])) {
            $newLink = '';
            foreach ($explodeLink as $key => $val) {
                if ($key == (count($explodeLink) - 1)) {
                    $newLink .= $dir . '/' . $val;
                } else {
                    $newLink .= $val . '/';
                }
            }
            $link = file_exists(public_path() . $newLink) ? $newLink : $link;
        }
        return $link;
    }

    public static function getDirImgStore($type)
    {
        switch ($type) {
            case ImageService::DEVICE_MOBILE:
                $dir = ImageService::UPLOAD_DIR_MOBILE;
                break;
            case ImageService::DEVICE_TABLET:
                $dir = ImageService::UPLOAD_DIR_TABLET;
                break;
            case ImageService::DEVICE_PC:
                $dir = ImageService::UPLOAD_DIR_PC;
                break;
            case ImageService::AVATAR:
                $dir = ImageService::UPLOAD_DIR_AVATAR;
                break;
            default:
                $dir = '';
                break;
        }
        return $dir;
    }

    public static function formatAddress($profile, $lang = null)
    {
        $location = $profile->location;

        if (!is_null($lang)) {
            $cityCountryInLang = $profile->citiesCountryIn($lang);
            $cityDescription = is_null($cityCountryInLang) ? '' : $cityCountryInLang->description;
        }

        if (empty($location)) {
            if (is_null($lang)) {
                $address = $profile->city_country_description;
            } else {
                $address = isset($cityDescription) ? $cityDescription : '';
            }
        } else {
            $address = $location;
            if (is_null($lang)) {
                $address .= ', ' . $profile->city_country_description;
            } elseif (!empty($cityDescription)) {
                $address .= ', ' . $cityDescription;
            }
        }

        return $address;
    }

    public static function getStockedPostThumbnail($post)
    {
        $stockedPost = Post::find($post->id);
        $thumbnail = self::getPostThumbnail($stockedPost);
        return $thumbnail;
    }
}
