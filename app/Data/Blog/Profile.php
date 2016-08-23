<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Data\Blog\BaseModel;

class Profile extends BaseModel
{
    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'profiles';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = [
        'first_name',
        'last_name',
        'url',
        'location',
        'cities_country_id',
        'city_country_description',
        'organization',
        'occupation',
        'description',
    ];

    public static $updateRules = [
        'email' => 'email',
        'first_name' => 'max:50',
        'last_name' => 'max:50',
        'url' => 'url|max:200',
        'location' => 'max:255',
        'organization' => 'max:255',
        'occupation' => 'max:255',
        'description' => 'max:500',
    ];

    public function citiesCountryIn($lang)
    {
        return CitiesCountry::where('lang', $lang)
                            ->where('place_id', $this->cities_country_id)
                            ->first();

    }
}
