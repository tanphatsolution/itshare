<?php namespace App\Data\Blog;


class UserBan extends BaseModel
{
    //use SoftDeletingTrait;

    // The database table used by the model.
    protected $table = 'user_bans';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    public static function getRules()
    {
        $rules = [
            'relason' => 'required',
            'lift_date' => 'required'
        ];
        return $rules;
    }
}
