<?php namespace App\Data\Blog;

use Validator;

class Feedback extends BaseModel
{

    // Database table used by the model
    protected $table = 'feedbacks';

    public static function validateFeedback($inputData = [])
    {

        // Rules for validation of data
        $rules = [
            'title' => 'required',
            'message' => 'required',
            'name' => 'required',
            'email' => ['required', 'email'],
        ];

        $validator = Validator::make($inputData, $rules);
        return $validator;
    }
}
