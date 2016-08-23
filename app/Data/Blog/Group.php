<?php namespace App\Data\Blog;

use Illuminate\Support\Facades\Auth;
use App\Data\Blog\GroupUser;
use App\Services\GroupService;

class Group extends BaseModel
{

    CONST APPROVED = 1;
    // The database table used by the model.
    protected $table = 'groups';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'shortname',
        'is_shortname',
        'description',
        'url',
        'cover_img',
        'cover_img_crop_position',
        'profile_img',
        'profile_img_crop_position',
        'active',
    ];

    public function groupUsers()
    {
        return $this->hasMany('App\Data\Blog\GroupUser', 'group_id')->where('status', GroupUser::STATUS_MEMBER)->orderBy('role', 'desc')->orderBy('status', 'desc');
    }

    public function users()
    {
        return $this->belongsToMany('App\Data\System\User', 'group_users');
    }

    public function current_user_group()
    {
        if (Auth::check()) {
            return $this->hasOne('App\Data\Blog\GroupUser', 'group_id')->where('user_id', Auth::user()->id);
        } else {
            return $this->hasOne('App\Data\Blog\GroupUser', 'group_id');
        }
    }

    public function groupSetting()
    {
        return $this->hasOne('App\Data\Blog\GroupSetting', 'group_id');
    }

    public function groupPosts()
    {
        return $this->hasMany('App\Data\Blog\GroupPost', 'group_id');
    }

    public function contentCount()
    {
        return GroupService::getGroupContentCount($this->id, false);
    }

    public function groupSeries()
    {
        return $this->hasMany('App\Data\Blog\GroupSeries');
    }

    public function group_post_by_approved()
    {
        return $this->hasMany('App\Data\Blog\GroupPost')
            ->whereNotNull('post_id')
            ->whereNull('group_series_id')
            ->where('approved', self::APPROVED);
    }

    public static function boot()
    {
        parent::boot();

        // After created
        static::created(function ($group) {
            $group->encryptedId = encrypt_id($group->id);
            $group->save();
        });
    }

    public function haveMemberIs($user)
    {
        if (Auth::check()) {
            $result = GroupUser::where('group_id', $this->id)
                                ->where('user_id', $user->id)
                                ->where('status', GroupUser::STATUS_MEMBER)
                                ->count();
            return ($result > 0);
        }

        return false;
    }

    public static function filter_by_active($active = 'all')
    {
        if ($active == 'all') {
            return self::get();
        } else {
            return self::where('active', $active);
        }
    }

    public static function findByEncryptedId($encryptedId)
    {
        return Group::where('encrypted_id', $encryptedId)->first();
    }

    public static function findByShortname($shortname)
    {
        return Group::where('shortname', $shortname)->first();
    }

    public static function findByShortnameNotInId($shortname, $id)
    {
        return Group::where('shortname', $shortname)
                    ->whereNotIn('id', [$id])
                    ->first();
    }

    public static function findBy($shortname)
    {
        $group = Group::findByEncryptedId($shortname);
        if (!$group) {
            $group = Group::findByShortname($shortname);
        }

        return $group;
    }
}
