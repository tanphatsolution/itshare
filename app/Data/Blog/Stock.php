<?php namespace App\Data\Blog;

use Validator;

class Stock extends BaseModel
{

    // Database table used by the model
    protected $table = 'stocks';

    protected $guarded = ['id'];

    /**
     * Validate input data for stock
     *
     * @return Validator
     */
    public static function validateStock($inputData = [])
    {

        // Rules for validation of data
        $rules = [
            'user_id' => 'required',
            'stock_id' => 'required',
        ];

        $validator = Validator::make($inputData, $rules);
        return $validator;
    }

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }
}
