<?php
namespace App\Services;

use App\Data\Blog\Group;
use App\Data\Blog\Post;
use App\Data\Blog\Wiki;
use URL;

class WikiService
{
    public static function getParentId($wiki, $arrParent = [])
    {
        $arrParent[] = $wiki->id;
        $parent = Wiki::find($wiki->parent_id);
        if (is_null($parent)) {
            return $arrParent;
        }

        return self::getParentId($parent, $arrParent);
    }

    public static function getNavBar($wiki)
    {
        $parentId = self::getParentId($wiki);
        $nav = '';

        $group = Group::find($wiki->group_id);
        for ($i = count($parentId) - 1; $i >= 0; $i--) {
            $wiki = Wiki::find($parentId[$i]);
            $postWiki = Post::find($wiki->post_id);
            $nav .= ' > <a href="' . url_to_post($postWiki) . '"">';
            $nav .= '<span>' . htmlentities($postWiki->title) . '</span>';
            $nav .= '</a>';
        }

        $root = '<a href=' . URL::action('GroupsController@show', [$group->encryptedId, GroupService::GROUP_FILTER_WIKI]) . '>';
        $root .= '<span>' . trans('labels.groups.wiki_home') . '</span>';
        $root .= '</a>';

        return $root . $nav;
    }

}
