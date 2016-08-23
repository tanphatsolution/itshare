<?php namespace App\Data\Blog;

class GroupUser extends BaseModel
{
    const STATUS_NOT_MEMBER = 0;
    const STATUS_WAITING_APPROVE = 1;
    const STATUS_MEMBER = 2;

    const ROLE_MEMBER = 0;
    const ROLE_ADMIN = 1;
    const ROLE_OWNER = 2;

    // The database table used by the model.
    protected $table = 'group_users';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'group_id',
        'user_id',
        'status',
        'role',
    ];

    public function group()
    {
        return $this->belongsTo('App\Data\Blog\Group')->with('group_post_by_approved');
    }

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public function isMember()
    {
        return ($this->status == self::STATUS_MEMBER);
    }

    public function isWaiting()
    {
        return ($this->status == self::STATUS_WAITING_APPROVE);
    }

    public function isAdmin()
    {
        return ($this->status == self::STATUS_MEMBER && ($this->role == self::ROLE_ADMIN || $this->role == self::ROLE_OWNER));
    }

    public function isOwner()
    {
        return ($this->status == self::STATUS_MEMBER && $this->role == self::ROLE_OWNER);
    }
}
