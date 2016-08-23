<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends BaseModel
{

    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'permissions';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['resource', 'action'];

    public static function getGroup($permissions)
    {
        $uniqueResource = [];
        foreach ($permissions as $key => $permission) {
            if (!in_array($permission['resource'], $uniqueResource)) {
                $uniqueResource[$key] = $permission['resource'];
            }
        }
        return $uniqueResource;
    }
}