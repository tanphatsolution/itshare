<?php namespace App\Data\Blog;

class GroupSeries extends BaseModel
{

    // The database table used by the model.
    protected $table = 'group_series';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
        'group_id',
        'language_code',
        'user_id'
    ];

    const URL_TYPE_POST = 1;
    const URL_TYPE_YOUTUBE = 2;
    const URL_TYPE_IMAGE = 3;
    const URL_TYPE_QUOTE = 4;
    const URL_TYPE_HEADING = 5;
    const URL_TYPE_TEXT = 6;
    const URL_TYPE_LINK = 7;
    const URL_TYPE_OTHER = 8;

    public function groupSeriesItems()
    {
        return $this->hasMany('App\Data\Blog\GroupSeriesItem', 'group_series_id');
    }

    public function postSeries()
    {
        return $this->hasOne('App\Data\Blog\PostSeries', 'group_series_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Data\System\User')->with('avatar');
    }

    public function isBelongTo($user)
    {
        return ($this->user_id == $user->id);
    }

    public function canEditBy($groupUser) {
        if (is_null($groupUser)) {
            return false;
        }

        $group = Group::find($this->group_id);

        if ($group) {

            $settingEditSeries = isset($group->groupSetting->edit_series_flag) ? $group->groupSetting->edit_series_flag : '';

            if (($settingEditSeries == GroupSetting::ALL_CAN_EDIT_SERIES) ||
                ($settingEditSeries == GroupSetting::ONLY_ADMIN_AUTHOR_CAN_EDIT_SERIES &&
                    ($groupUser->isAdmin() || $groupUser->user_id == $this->user_id)) ||
                $settingEditSeries == GroupSetting::ONLY_AUTHOR_CAN_EDIT_SERIES &&
                    $groupUser->user_id == $this->user_id) {
                return true;
            }
        }

        return false;
    }

    public function group()
    {
        return $this->belongsTo('App\Data\Blog\Group');
    }

    public static function boot()
    {
        parent::boot();

        // Before delete
        static::deleting(function ($groupSeries) {
            $groupSeries->postSeries()->delete();
        });

        // After created
        static::created(function ($groupSeries) {
            PostSeries::create([
                'post_id' => 0,
                'group_series_id' => $groupSeries->id,
                'language_code' => $groupSeries->language_code,
                'published_at' => $groupSeries->created_at,
                'updated_at' => $groupSeries->updated_at,
                'created_at' => $groupSeries->created_at,
            ]);
        });

        // After update
        static::updated(function ($groupSeries) {
            $groupSeries->postSeries()->update([
                'language_code' => $groupSeries->language_code,
                'updated_at' => $groupSeries->updated_at,
            ]);
        });
    }
}
