<?php namespace App\Data\Blog;

use Validator;

class UserSkill extends BaseModel
{
    const MAX_SKILL_NUMBER = 10;
    const MAX_SKILL_YEAR = 10;
    const STEP_SKILL_YEAR = 0.5;
    const SKILL_DELETE_FLAG = -1;

    // The database table used by the model.
    protected $table = 'user_skills';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['user_id', 'skill_id', 'year'];

    public static $rules = [
        'skill_id' => 'required',
        'year' => 'required',
    ];

    public static function ruleMessages()
    {
        return [
            'skill_id.required' => trans('messages.skill.required'),
            'year.required' => trans('messages.skill.required'),
        ];
    }

    public static function isPassValidate($input)
    {
        if (isset($input['skill_name'])) {
            foreach ($input['skill_name'] as $key => $value) {
                $newInput = [
                    'skill_id' => $input['skill_name'][$key],
                    'year' => $input['skill_year'][$key],
                ];
                $validator = Validator::make($newInput, Skill::$rules, Skill::ruleMessages());
                if ($validator->fails()) {
                    return $validator;
                }
            }
        }
        return true;
    }

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public function skill()
    {
        return $this->belongsTo('App\Data\Blog\Skill');
    }
}