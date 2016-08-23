<?php namespace App\Services;

use App\Data\Blog\Post;
use App\Data\Blog\UserPostLanguage;
use App\Data\System\User;
use HTML;
use Session;

class SearchService
{
    const QUICK_SEARCH_PER_PAGE = 5;
    const FULL_SEARCH_PER_PAGE = 8;

    const TYPE_QUICK_SEARCH_HEADER = 1;
    const TYPE_QUICK_SEARCH_FOOTER = 2;
    const TYPE_QUICK_SEARCH_POST = 3;
    const TYPE_QUICK_SEARCH_USER = 4;

    const TYPE_FULL_SEARCH_POST = 1;
    const TYPE_FULL_SEARCH_USER = 2;
    const TYPE_FULL_SEARCH_CATEGORY = 3;

    public static function quickSearch($keyword, $perPage = self::QUICK_SEARCH_PER_PAGE)
    {
        $result = ['data' => []];
        self::quickSearchPost($result['data'], $keyword, $perPage);
        self::quickSearchUser($result['data'], $keyword, $perPage);
        if (empty($result['data'])) {
            return $result;
        }
        $result['data'][] = [
            'id' => null,
            'href' => null,
            'text' => trans('messages.search.quicksearch_footer'),
            'type' => self::TYPE_QUICK_SEARCH_FOOTER,
            'category' => null,
            'subtext' => null,
        ];
        return $result;
    }

    public static function quickSearchPost(&$result, $keyword, $perPage)
    {
        $posts = Post::with(['user', 'categories'])
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('content', 'LIKE', '%' . $keyword . '%');
            })
            ->orderBy('published_at', 'desc');
        $lang = UserPostLanguage::getCurrentUserLanguages();
        if (!empty($lang) && !in_array('all', $lang)) {
            $posts = $posts->whereIn('language_code', UserPostLanguage::getCurrentUserLanguages());
        }
        $posts = PostService::filterByGroupSecret($posts);
        $posts = $posts->take($perPage)->get();
        if ($posts->count()) {
            $result[] = [
                'id' => null,
                'href' => null,
                'text' => trans('messages.search.quicksearch_post'),
                'type' => self::TYPE_QUICK_SEARCH_HEADER,
                'category' => null,
                'subtext' => null,
            ];
            foreach ($posts as $post) {
                $userName = isset($post->user->name) ? $post->user->name : null;
                $result[] = [
                    'id' => $post->id,
                    'href' => url_to_post($post),
                    'type' => self::TYPE_QUICK_SEARCH_POST,
                    'text' => HTML::entities($post->title),
                    'category' => self::joinAllCategories($post),
                    'subtext' => HTML::entities($userName),
                ];
            }
        }
    }

    public static function quickSearchUser(&$result, $keyword, $perPage)
    {
        $users = User::where('name', 'LIKE', '%' . $keyword . '%')
            ->take($perPage)
            ->get()
            ->toArray();
        if (!empty($users)) {
            $result[] = [
                'id' => null,
                'href' => null,
                'text' => trans('messages.search.quicksearch_user'),
                'type' => self::TYPE_QUICK_SEARCH_HEADER,
                'category' => null,
                'subtext' => null,
            ];
            foreach ($users as $user) {
                $result[] = [
                    'id' => $user['id'],
                    'href' => url_to_user($user),
                    'type' => self::TYPE_QUICK_SEARCH_USER,
                    'text' => HTML::entities($user['name']),
                    'category' => HTML::entities($user['username']),
                    'subtext' => $user['created_at'],
                ];
            }
        }
    }

    public static function joinAllCategories($post)
    {
        if (!isset($post->categories) || empty($post->categories)) {
            return null;
        }
        $categories = [];
        foreach ($post->categories as $category) {
            $categories[] = $category->name;
        }
        return implode(',', $categories);
    }

    public static function elementQuickSearchTypes()
    {
        return [
            'header' => self::TYPE_QUICK_SEARCH_HEADER,
            'footer' => self::TYPE_QUICK_SEARCH_FOOTER,
            'post' => self::TYPE_QUICK_SEARCH_POST,
            'user' => self::TYPE_QUICK_SEARCH_USER,
        ];
    }

    public static function getAllTypes()
    {
        return [
            self::TYPE_FULL_SEARCH_POST => trans('messages.search.type_post'),
            self::TYPE_FULL_SEARCH_USER => trans('messages.search.type_user'),
        ];
    }

    public static function getDefaultType()
    {
        if (Session::has('search_type')) {
            return Session::get('search_type');
        }
        return SearchService::TYPE_FULL_SEARCH_POST;
    }

    public static function getTemplateByType($type)
    {
        switch ($type) {
            case self::TYPE_FULL_SEARCH_POST:
                return [
                    'name' => 'search.post',
                    'variable' => 'posts',
                ];
            case self::TYPE_FULL_SEARCH_USER:
                return [
                    'name' => 'search.user',
                    'variable' => 'users',
                ];
        }
    }

    public static function fullSearch($keyword, $type = self::TYPE_FULL_SEARCH_POST)
    {
        switch ($type) {
            case self::TYPE_FULL_SEARCH_POST:
                $result = Post::with(['user', 'categories'])
                    ->whereNotNull('published_at')
                    ->whereNotNull('encrypted_id');
                $result = PostService::filterByGroupSecret($result);
                $result = $result->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('content', 'LIKE', '%' . $keyword . '%');
                })
                    ->orderBy('published_at', 'desc');
                $lang = UserPostLanguage::getCurrentUserLanguages();
                if (!empty($lang) && !in_array('all', $lang)) {
                    $result = $result->whereIn('language_code', UserPostLanguage::getCurrentUserLanguages());
                }
                $result = $result->paginate(self::FULL_SEARCH_PER_PAGE);
                break;

            case self::TYPE_FULL_SEARCH_USER:
                $result = User::where('name', 'LIKE', '%' . $keyword . '%')
                    ->paginate(self::FULL_SEARCH_PER_PAGE);
                break;
            default:
                $result = Post::with(['user', 'categories'])
                    ->whereNotNull('published_at')
                    ->whereNotNull('encrypted_id');
                $result = PostService::filterByGroupSecret($result);
                $result = $result->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('content', 'LIKE', '%' . $keyword . '%');
                })
                    ->paginate(self::FULL_SEARCH_PER_PAGE);
                break;
        }
        return $result;
    }
}
