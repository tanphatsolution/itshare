<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryRequest extends BaseModel
{
    CONST CATEGORIES_HINT_LIMIT = 5;
    CONST LIMIT_CATEGORIES_PER_PAGE = 15;
    CONST ADMIN_LIMIT_CATEGORIES_PER_PAGE = 200;
    CONST LIMIT_SEARCH_ITEMS = 8;
    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'category_requests';
    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];
    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['name', 'img', 'short_name', 'status'];

    /**
     * Define posts relations. A Category has many posts
     * @return mixed
     */

    public static function getRules($id = null)
    {
        $rules = [
            'name' => 'required|unique:categories,name,' . $id,
            'short_name' => 'required|unique:categories,short_name,' . $id
        ];
        return $rules;
    }

    public static function findByShortName($shortName)
    {
        return CategoryRequest::where('short_name', $shortName)->first();
    }

    public static function searchByName($name)
    {
        return CategoryRequest::withTrashed()
            ->where('name', 'LIKE', '%$' . $name . '%')
            ->orWhere('short_name', 'LIKE', '%$' . $name . '%')
            ->paginate(self::LIMIT_SEARCH_ITEMS);
    }
}
