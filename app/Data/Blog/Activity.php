<?php namespace App\Data\Blog;

use Carbon\Carbon;

class Activity extends BaseModel
{
    const ACTIVITY_LOGIN_SUCCESS = 1; // Type log is after login success
    const ACTIVITY_LOGOUT_SUCCESS = 2; // Type log is after logout success
    const ACTIVITY_KEYWORD_SEARCH = 3;  // Type log is keyword search
    const ACTIVITY_JOINED_GROUP = 4;  // Type log is joined group
    const ACTIVITY_VIEW_POST = 5;  // Type log is view post
    const SCREEN_SEARCH_RESULT = 'SCREEN_SEARCH_RESULT';
    const SCREEN_POST_DETAIL = 'SCREEN_POST_DETAIL';
    
    protected $table = 'activities';
    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];
    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['message', 'trackable_type', 'user_id', 'post_id', 'category_id', 'group_id', 'screen_code', 'action_code', 'position_click'];
}
