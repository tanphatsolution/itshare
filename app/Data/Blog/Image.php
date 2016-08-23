<?php namespace App\Data\Blog;

use Carbon\Carbon;
use Config;

class Image extends BaseModel
{

    protected $table = 'images';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public function scopeThisMonth($query)
    {
        $first = Carbon::now()->firstOfMonth();
        return $query->where('created_at', '>=', $first);
    }

    public static function getUploadRules()
    {
        $config = Config::get('image');
        $maxSize = $config['max_image_size'];
        return [
            'image' => 'max:' . $maxSize . ' | mimes:jpg,jpeg,png,gif',
        ];
    }

}