<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends BaseModel
{
    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'user_roles';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['user_id', 'role_id'];

    public static function createOrUpdate($userId, $roleId = Role::MEMBER)
    {
        $userRole = UserRole::where('user_id', $userId)->first();
        if (is_null($userRole)) {
            return UserRole::create([
                'user_id' => $userId,
                'role_id' => $roleId,
            ]);
        }
        return $userRole->update([
            'role_id' => $roleId,
        ]);
    }

    public function role()
    {
        return $this->belongsTo('App\Data\Blog\Role');
    }

}