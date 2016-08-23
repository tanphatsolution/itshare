<?php namespace App\Data\Blog;

class GroupSetting extends BaseModel
{

    CONST PRIVACY_PUBLIC = 0;
    CONST PRIVACY_PROTECTED = 1;
    CONST PRIVACY_PRIVATE = 2;

    CONST ALL_CAN_ADD_MEMBER = 0;
    CONST ONLY_ADMIN_CAN_ADD_MEMBER = 1;
    CONST ALL_CAN_ADD_MEMBER_WITH_PERMISSION = 2;

    CONST ALL_CAN_POST = 0;
    CONST ONLY_ADMIN_CAN_POST = 1;
    CONST ALL_CAN_POST_WITH_PERMISSION = 2;

    CONST POST_NO_NEED_APPROVE = 0;
    CONST POST_NEED_APPROVE = 1;

    CONST ALL_CAN_EDIT_POST = 0;
    CONST ONLY_ADMIN_CAN_EDIT_POST = 1;
    CONST ONLY_AUTHOR_CAN_EDIT_POST = 2;

    CONST ALL_CAN_EDIT_SERIES = 0;
    CONST ONLY_ADMIN_AUTHOR_CAN_EDIT_SERIES = 1;
    CONST ONLY_AUTHOR_CAN_EDIT_SERIES = 2;

    // The database table used by the model.
    protected $table = 'group_settings';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'group_id',
        'privacy_flag',
        'add_member_flag',
        'add_post_flag',
        'approve_post_flag',
        'edit_post_flag',
        'edit_series_flag',
    ];

    public function group()
    {
        return $this->belongsTo('App\Data\Blog\Group', 'group_id');
    }

    public function isPublic()
    {
        return ($this->privacy_flag == self::PRIVACY_PUBLIC);
    }

    public function isNonePublic()
    {
        return ($this->privacy_flag == self::PRIVACY_PROTECTED);
    }

    public function isSecret()
    {
        return ($this->privacy_flag == self::PRIVACY_PRIVATE);
    }

    public function isMemberCanAddMember()
    {
        if (($this->add_member_flag == self::ALL_CAN_ADD_MEMBER) || ($this->add_member_flag == self::ALL_CAN_ADD_MEMBER_WITH_PERMISSION)) {
            return true;
        }
        return false;
    }

    public function isMemberCanAddPost()
    {
        return $this->add_post_flag;
    }

    public function isMemberCanAddMemberWithPermission()
    {
        return ($this->add_member_flag == self::ALL_CAN_ADD_MEMBER_WITH_PERMISSION);
    }
}
