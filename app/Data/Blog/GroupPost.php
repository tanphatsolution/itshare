<?php namespace App\Data\Blog;

use App\Services\GroupUserService;

class GroupPost extends BaseModel
{
    const APPROVED = 1;
    const UNAPPROVED = 0;

    CONST GROUP_POST_PUBLIC = 0;
    CONST GROUP_POST_PRIVATE = 1;
    CONST GROUP_POST_APPROVED = 1;
    CONST GROUP_POST_NOT_APPROVE = 0;
    CONST GROUP_POST_LIMIT_IN_POST_DETAIL = 10;

    protected $table = 'group_posts';

    protected $guarded = ['id'];

    protected $fillable = [
        'group_id',
        'post_id',
        'group_series_id',
        'privacy_flag',
        'approved',
    ];

    public function getAllPosts()
    {
        $postIds = GroupPost::where('group_id', $this->group_id)
            ->where('approved', self::GROUP_POST_APPROVED)
            ->limit(self::GROUP_POST_LIMIT_IN_POST_DETAIL)
            ->lists('post_id');

        $posts = Post::with('user')
            ->whereIn('id', $postIds)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'DESC')
            ->get();
        
        return $posts;
    }

    public function isPrivate()
    {
        return ($this->privacy_flag == self::GROUP_POST_PRIVATE);
    }

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post', 'post_id')->whereNotNull('published_at');
    }

    public function series()
    {
        return $this->belongsTo('App\Data\Blog\GroupSeries', 'group_series_id');
    }

    public function group()
    {
        return $this->belongsTo('App\Data\Blog\Group', 'group_id');
    }

    public static function getGroupPostData($postId, $currentUser, $post)
    {
        $groupPostData = [];
        $groupPostData['hidePostContent'] = false;
        $groupPost = GroupPost::where('post_id', $postId)->first();
        if (is_null($groupPost)) {
            return $groupPostData;
        }
        $group = Group::where('id', $groupPost->group_id)->first();
        $groupsetting = $group->groupSetting()->first();
        if ($groupsetting && $groupsetting->isSecret() && !$group->haveMemberIs($currentUser)) {
            //return redirect to 404 view
            return response()->view('errors.404', [], 404)->send();
        } elseif ($groupsetting && $groupsetting->isNonePublic()) {
            $groupPostData['hidePostContent'] = (!$group->haveMemberIs($currentUser) && $groupPost->isPrivate()) ? true : false;
        }
        $groupPostData['group'] = $group;
        $groupPostData['canEditPost'] = $group->haveMemberIs($currentUser) ? $post->canEditedBy($currentUser) : false;
        $groupPostData['postsInGroup'] = $groupPost->getAllPosts();
        $groupPostData['groupUser'] = GroupUserService::getCurrentUserRole($group->id);
        return $groupPostData;
    }
}
