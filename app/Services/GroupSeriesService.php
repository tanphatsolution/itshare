<?php namespace App\Services;

use App\Data\Blog\Group;
use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupSeriesItem;
use App\Data\Blog\GroupSetting;
use App\Data\Blog\GroupSeries;
use App\Data\Blog\Post;
use View;
use DB;
use Auth;
use Config;

class GroupSeriesService
{

    public static function genLinkYoutubePreview($url, $width = '160', $height = '90')
    {
        $data = self::crawlerUrl($url);
        parse_str(parse_url($url, PHP_URL_QUERY), $matches);
        $id = $matches['v'];
        $data['type'] = GroupSeries::URL_TYPE_YOUTUBE;
        return View::make('groupseries._a_youtube_item_edit',
            ['width' => $width, 'height' => $height, 'id' => $id, 'data' => $data])->render();
    }

    public static function isImageUrl($url)
    {
        $params = ['http' => ['method' => 'HEAD']];
        $ctx = stream_context_create($params);
        if (is_null(parse_url($url, PHP_URL_PORT))) {
            $fp = @fopen($url, 'rb', false, $ctx);
            if (!$fp) {
                return check_image_file_from($url);
            }
            $meta = stream_get_meta_data($fp);
            if ($meta === false) {
                fclose($fp);
                return false;
            }

            $wrapper_data = $meta['wrapper_data'];
            if (is_array($wrapper_data)) {
                foreach (array_keys($wrapper_data) as $hh) {
                    if (substr($wrapper_data[$hh], 0, 19) == 'Content-Type: image') {
                        fclose($fp);
                        return true;
                    }
                }
            }

            fclose($fp);
            return false;
        } else {
            return check_image_file_from($url);
        }
    }

    public static function genLinkImagePreview($url, $width = '160', $height = '90')
    {
        return View::make('groupseries._a_image_item_edit',
            ['width' => $width, 'height' => $height, 'url' => $url])->render();
    }

    public static function genLinkQuotePreview($url, $quote)
    {
        $data = [
            'url' => $url,
            'quote' => $quote,
            'type' => GroupSeries::URL_TYPE_QUOTE,
        ];

        return View::make('groupseries._a_quote_item_edit', ['data' => $data])->render();
    }

    public static function genLinkTextPreview($text)
    {
        $data = [
            'text' => $text,
            'type' => GroupSeries::URL_TYPE_TEXT,
        ];
        return View::make('groupseries._a_text_item_edit', ['data' => $data])->render();
    }

    public static function genLinkHeadingPreview($text)
    {
        $data = [
            'text' => $text,
            'type' => GroupSeries::URL_TYPE_HEADING,
        ];
        return View::make('groupseries._a_heading_item_edit', ['data' => $data])->render();
    }

    public static function genVibloLinkPreview($url, $encryptedGroupId)
    {
        $arr = explode('/posts/', $url);
        $encryptedId = $arr[1];
        $post = Post::findByEncryptedId($encryptedId);
        $groupPost = null;
        if ($post) {
            $groupPost = GroupPost::where('post_id', $post->id)->first();
        }
        $currentGroup = Group::where('encrypted_id', $encryptedGroupId)->first();
        $isNotSecretGroup = true;
        if (!is_null($groupPost) && ($groupPost->group_id != $currentGroup->id)) {
            $groupPostSetting = GroupSetting::where('group_id', $groupPost->group_id)->first();
            if ($groupPostSetting->isSecret()) {
                $isNotSecretGroup = false;
            }
        }

        if (!is_null($post) && $isNotSecretGroup) {
            $data = [
                'post_id' => $post->id,
                'image' => HelperService::getPostThumbnail($post),
                'title' => $post->title,
                'url' => $url,
                'description' => '',
                'type' => GroupSeries::URL_TYPE_POST,
            ];
        } else {
            $data = [
                'post_id' => null,
                'image' => Config::get('group.noImgDefault'),
                'url' => $url,
                'title' => 'HTTP/1.1 404 Not Found',
                'description' => '',
                'site_name' => '',
                'type' => GroupSeries::URL_TYPE_LINK,
            ];
            $data = self::getMetaDataUseApi($url, $data);
            return View::make('groupseries._an_other_url_item_edit', ['data' => $data])->render();
        }

        return View::make('groupseries._a_post_item_edit', ['data' => $data, 'post' => $post])->render();
    }

    public static function genLinkPreview($url)
    {
        $data = self::crawlerUrl($url);
        $data['type'] = GroupSeries::URL_TYPE_LINK;
        $data = self::getMetaDataUseApi($url, $data);
        return View::make('groupseries._an_other_url_item_edit', ['data' => $data])->render();
    }

    public static function crawlerUrl($url)
    {
        $url = (string)filter_var($url, FILTER_SANITIZE_URL);
        $data = [
            'image' => Config::get('group.noImgDefault'),
            'site_name' => '',
            'type' => '',
            'url' => '',
            'video' => '',
            'description' => '',
        ];
        $url = trim($url);

        if (!is_null(parse_url($url, PHP_URL_PORT))) {
            $data = [
                'image' => Config::get('group.noImgDefault'),
                'url' => $url,
                'title' => trans('labels.group_series.not_preview'),
                'description' => '',
                'site_name' => '',
            ];
            return $data;
        }
        $file_header = get_headers($url);

        $html = new \DOMDocument();

        if ($file_header[0] == 'HTTP/1.1 404 Not Found' || is_null($file_header)) {
            $data = [
                'image' => Config::get('group.noImgDefault'),
                'url' => $url,
                'title' => 'HTTP/1.1 404 Not Found',
                'description' => '',
                'site_name' => '',
            ];
            return $data;
        }

        $sites_html = @file_get_contents($url);
        preg_match('/<title>([^>]*)<\/title>/si', $sites_html, $match);
        if (empty($match)) {
            try {
                $sites_html = gzdecode(@file_get_contents($url));
            } catch (\Exception $e) {
                $data = [
                    'image' => Config::get('group.noImgDefault'),
                    'url' => $url,
                    'title' => trans('labels.group_series.not_preview'),
                    'description' => '',
                    'site_name' => '',
                ];
                return $data;
            }
            preg_match('/<title>([^>]*)<\/title>/si', $sites_html, $match);
        }

        libxml_use_internal_errors(true);
        $html->loadHTML($sites_html);
        libxml_clear_errors();

        $data['title'] = (isset($match) && is_array($match) && count($match) > 0) ? strip_tags($match[1]) : '';

        foreach ($html->getElementsByTagName('meta') as $meta) {
            if ($meta->getAttribute('property') == 'og:image') {
                $data['image'] = !empty($meta->getAttribute('content')) ? $meta->getAttribute('content') : '';
            } elseif ($meta->getAttribute('property') == 'og:site_name') {
                $data['site_name'] = !empty($meta->getAttribute('content')) ? $meta->getAttribute('content') : '';
            } elseif ($meta->getAttribute('property') == 'og:type') {
                $data['type'] = !empty($meta->getAttribute('content')) ? $meta->getAttribute('content') : '';
            } elseif ($meta->getAttribute('property') == 'og:url') {
                $data['url'] = !empty($meta->getAttribute('content')) ? $meta->getAttribute('content') : '';
            } elseif ($meta->getAttribute('property') == 'og:video') {
                $data['video'] = !empty($meta->getAttribute('content')) ? $meta->getAttribute('content') : '';
            } elseif ($meta->getAttribute('property') == 'og:description') {
                $data['description'] = !empty($meta->getAttribute('content')) ? $meta->getAttribute('content') : '';
            }
        }

        $data['image'] = empty($data['image']) ? Config::get('group.noImgDefault') : $data['image'];
        $data['site_name'] = empty($data['site_name']) ? $url : $data['site_name'];
        $data['url'] = empty($data['url']) ? $url : $data['url'];
        $data['title'] = empty($data['title']) ? $url : $data['title'];

        return $data;
    }

    private static function getMetaDataUseApi($url, $result)
    {
        if ((empty($result['image']) || $result['image'] == Config::get('group.noImgDefault'))
            && !empty(Config::get('group.urlMetaSupport'))
        ) {
            $urlSupport = Config::get('group.urlMetaSupport');
            $urlMetaSupportOk = Config::get('group.urlMetaSupportOk');
            $urlMetaSupportError = Config::get('group.urlMetaSupportError');
            $metaUrl = filter_var($urlSupport . $url, FILTER_SANITIZE_URL);
            if (!in_array($metaUrl, [], false)) {
                $data = [
                    'image' => Config::get('group.noImgDefault'),
                    'url' => $url,
                    'title' => trans('labels.group_series.not_preview'),
                    'description' => '',
                    'site_name' => '',
                ];
                return $data;
            }
            $meta = @file_get_contents($metaUrl);
            $meta = (!empty(trim($meta))) ? json_decode($meta, true) : null;
            if ((substr($url, 0, 4) == 'www.') && (isset($meta) && $meta['result']['status'] == $urlMetaSupportError)) {
                $fullUrl = [
                    str_replace('www.', 'http://', $url),
                    str_replace('www.', 'https://', $url),
                    'http://' . $url,
                    'https://' . $url,
                ];
                foreach ($fullUrl as $it) {
                    $meta = @file_get_contents($urlSupport . $it);
                    $meta = (!empty(trim($meta))) ? json_decode($meta, true) : null;
                    if (isset($meta) && $meta['result']['status'] == $urlMetaSupportOk) {
                        break;
                    }
                }
            }

            if (isset($meta) && $meta['result']['status'] == $urlMetaSupportOk) {
                $meta = $meta['meta'];
                $result['image'] = (!empty($meta['image'])) ? $meta['image'] : $result['image'];
                $result['site_name'] = (!empty($meta['title'])) ? $meta['title'] : $result['site_name'];
                $result['title'] = (!empty($meta['title'])) ? $meta['title'] : $result['title'];
                $result['description'] = (!empty($meta['description'])) ? $meta['description'] : $result['description'];
            }
        }
        return $result;
    }

    public static function save($inputs)
    {
        DB::beginTransaction();
        $group = Group::where('encrypted_id', $inputs['encryptedGroupId'])->first();
        $groupSeries = GroupSeries::create([
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'group_id' => $group->id,
            'language_code' => $inputs['language_code'],
            'user_id' => Auth::check() ? Auth::user()->id : '',
        ]);

        if ($groupSeries) {
            $urls = $inputs['url'];
            $inputs['group_series_id'] = $groupSeries->id;
            $error = false;
            foreach ($urls as $key => $url) {
                $inputs['group_series_url'] = $url;
                $saveSingleItem = self::saveSingleItem($inputs, $key);
                if (!$saveSingleItem) {
                    $error = true;
                }
            }

            $groupPost = GroupPost::create([
                'group_id' => $group->id,
                'post_id' => null,
                'group_series_id' => $groupSeries->id,
                'approved' => GroupPost::APPROVED,
            ]);
            if (!$error && $groupPost) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } else {
            DB::rollback();
        }

        return $groupSeries;
    }

    public static function update($inputs)
    {
        DB::beginTransaction();

        $groupSeries = GroupSeries::find((int)($inputs['groupSeriesId']));
        if ($groupSeries) {

            $itemIds = GroupSeriesItem::where('group_series_id', $groupSeries->id)->lists('id');

            $urls = isset($inputs['url']) ? $inputs['url'] : [];
            $ids = isset($inputs['id']) ? $inputs['id'] : [];

            $inputs['group_series_id'] = $groupSeries->id;

            $groupSeries = $groupSeries->update([
                'name' => $inputs['name'],
                'language_code' => $inputs['language_code'],
                'description' => $inputs['description'],
            ]);

            foreach ($urls as $index => $url) {
                $inputs['group_series_url'] = $url;
                if (empty($ids[$index])) {
                    self::saveSingleItem($inputs, $index);
                } else {
                    self::updateSingleItem($inputs, $index);
                }
            }

            //remove empty id
            $ids = array_filter($ids);
            foreach ($itemIds as $itemId) {
                if (!in_array($itemId, $ids)) {
                    GroupSeriesItem::where('id', $itemId)->first()->delete();
                }
            }
            if ($groupSeries) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } else {
            DB::rollback();
        }

        return $groupSeries;
    }

    public static function saveSingleItem($data, $key)
    {
        return GroupSeriesItem::create([
            'group_series_id' => $data['group_series_id'],
            'post_id' => $data['group_post_id'][$key],
            'url' => $data['group_series_url'],
            'thumbnail_img' => $data['group_series_thumbnail'][$key],
            'title' => $data['group_series_title'][$key],
            'description' => $data['group_series_description'][$key],
            'type' => $data['type'][$key],
            'order_item' => $key,
        ]);
    }

    public static function updateSingleItem($data, $key)
    {
        $groupSeriesItem = GroupSeriesItem::find((int)$data['id'][$key]);
        if ($groupSeriesItem) {
            return $groupSeriesItem->update([
                'post_id' => $data['group_post_id'][$key],
                'url' => $data['group_series_url'],
                'thumbnail_img' => $data['group_series_thumbnail'][$key],
                'title' => $data['group_series_title'][$key],
                'description' => $data['group_series_description'][$key],
                'type' => $data['type'][$key],
                'order_item' => $key,
            ]);
        }
        return false;
    }

    public static function validation($input)
    {
        $errors = '';
        $loadingPreviewFinished = true;

        if (empty(self::formatInput($input['name']))) {
            $errors .= trans('messages.series.empty_title') . ' ';
        }
        if (!isset($input['url'])) {
            $errors .= trans('messages.series.empty_list') . ' ';
        } else {
            $emptyInput = false;
            foreach ($input['url'] as $index => $url) {
                if (empty($url) &&
                    isset($input['type'][$index]) &&
                    ($input['type'][$index] == GroupSeries::URL_TYPE_LINK ||
                        $input['type'][$index] == GroupSeries::URL_TYPE_IMAGE ||
                        $input['type'][$index] == GroupSeries::URL_TYPE_YOUTUBE)
                ) {
                    $emptyInput = true;
                }
                if (isset($input['group_series_description'][$index]) &&
                    empty($input['group_series_description'][$index]) &&
                    ($input['type'][$index] == GroupSeries::URL_TYPE_QUOTE ||
                        $input['type'][$index] == GroupSeries::URL_TYPE_TEXT ||
                        $input['type'][$index] == GroupSeries::URL_TYPE_HEADING)
                ) {
                    $emptyInput = true;
                }
                if (empty($url) && !isset($input['type'][$index])) {
                    $emptyInput = true;
                }
                if (!empty($url) && !isset($input['type'][$index])) {
                    $loadingPreviewFinished = false;
                }
            }
            if ($emptyInput) {
                $errors .= trans('messages.series.empty_input') . ' ';
            }
        }
        if (isset($input['incorrectFlag'])) {
            $errors .= trans('messages.series.incorrect_link') . ' ';
        } elseif (!$loadingPreviewFinished) {
            $errors .= trans('messages.series.not_finish_load_preview') . ' ';
        }
        return $errors;
    }

    public static function formatInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function checkFormatURL($url)
    {
        $url = self::formatInput($url);
        if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
            return true;
        }
        return false;
    }

    public static function getLinkTypeOption()
    {
        return [
            GroupSeries::URL_TYPE_LINK => trans('labels.group_series.link'),
            GroupSeries::URL_TYPE_YOUTUBE => trans('labels.group_series.youtube'),
            GroupSeries::URL_TYPE_IMAGE => trans('labels.group_series.image'),
            GroupSeries::URL_TYPE_QUOTE => trans('labels.group_series.quote'),
            GroupSeries::URL_TYPE_HEADING => trans('labels.group_series.heading'),
            GroupSeries::URL_TYPE_TEXT => trans('labels.group_series.text'),
        ];
    }

    public static function filterByGroupSecret($group_series)
    {
        $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        $secretPostsId = GroupSeries::whereIn('group_id', $secretGroupsId)
                                            ->lists('id');
        $group_series = $group_series->whereNotIn('id', $secretPostsId);
        return $group_series;
    }

}