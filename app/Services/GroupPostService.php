<?php namespace App\Services;

use App\Data\Blog\GroupPost;
use App\Data\Blog\UserPostLanguage;

class GroupPostService
{
    CONST PER_PAGE = 12;

    public static function approve($groupId, $postId)
    {
        return GroupPost::where('group_id', $groupId)
            ->where('post_id', $postId)
            ->update(['approved' => GroupPost::APPROVED]);
    }

    public static function deny($groupId, $postId)
    {
        return GroupPost::where('group_id', $groupId)
                        ->where('post_id', $postId)
                        ->delete();
    }

    public static function getCategoryPosts($group, $category, $pageCount = 0)
    {
        $offset = $pageCount * self::PER_PAGE;

        $groupPostIdQuery = GroupPost::where('group_id', $group->id)
            ->whereNotNull('post_id')
            ->whereNull('group_series_id')
            ->where('approved', GroupPost::APPROVED);

        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();
        if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $groupPostIdQuery->leftJoin('posts', 'group_posts.post_id', '=', 'posts.id')
                       ->whereIn('posts.language_code', $currentUserLanguages);
        }

        $groupPostIds = $groupPostIdQuery->lists('post_id');

        $posts = $category->publishedPosts()
            ->whereIn('posts.id', $groupPostIds)
            ->orderBy('posts.published_at', 'desc')
            ->take(self::PER_PAGE)
            ->skip($offset)
            ->get();

        return $posts;
    }
}