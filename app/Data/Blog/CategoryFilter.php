<?php namespace App\Data\Blog;

class CategoryFilter extends BaseModel
{
    protected $table = 'category_filters';
    protected $guarded = ['id'];
    protected $fillable = ['category_id'];

    public function category()
    {
        return $this->belongsTo('App\Data\Blog\Category');
    }

    public static function getRules()
    {
        $rules = [
            'category_id' => 'required|unique:category_filters,category_id|exists:categories,id',
        ];
        return $rules;
    }
}
