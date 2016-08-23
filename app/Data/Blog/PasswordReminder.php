<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReminder extends BaseModel
{
    use SoftDeletes;

    protected $table = 'password_reminders';

    protected $hidden = ['token'];

    protected $guarded = ['id'];

    protected $fillable = ['email', 'token'];

    public static $remindRule = [
        'email' => 'required|email|max:50|exists:users,email',
    ];

    public static $resetRule = [
        'email' => 'required|email|max:50|exists:users,email',
        'password' => 'required|confirmed|max:50',
        'password_confirmation' => 'required',
    ];
}
