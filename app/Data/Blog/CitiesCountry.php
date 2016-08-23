<?php namespace App\Data\Blog;

class CitiesCountry extends BaseModel
{
    // The database table used by the model.
    protected $table = 'cities_countries';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'lang',
        'place_id',
        'description',
    ];

}
