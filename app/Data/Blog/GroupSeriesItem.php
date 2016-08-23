<?php namespace App\Data\Blog;

class GroupSeriesItem extends BaseModel
{

	CONST LIMIT_ITEM_ON_POST_DETAIL = 10;

    // The database table used by the model.
    protected $table = 'group_series_items';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'group_series_id',
        'post_id',
        'url',
        'thumbnail_img',
        'title',
        'description',
        'type',
        'order_item',
    ];

}
