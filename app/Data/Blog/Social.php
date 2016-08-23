<?php namespace App\Data\Blog;

use App\Data\System\User;
use App\Services\UserService;
use App\Services\RedisService;
use Auth;

class Social extends BaseModel
{
    const FACEBOOK = 'facebook';
    const GOOGLE = 'google';
    const GITHUB = 'github';

    protected $table = 'socials';

    protected $guarded = ['id'];

    protected $fillable = ['user_id', 'type', 'email', 'uid', 'link', 'avatar_url'];

    public static $createRules = [
        'user_id' => 'required',
        'type' => 'required',
        'uid' => 'required',
        'email' => 'required|email|max:50|unique:users,email'
    ];

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public static function authenticate($type, $uid)
    {
        $social = Social::where('type', $type)->where('uid', $uid)->first();
        if ($social) {
            $user = User::where('id', $social->user_id)->whereNull('active_token')->first();
            if ($user && !UserService::isBanedUser($user)) {
                Auth::loginUsingId($user->id);
                RedisService::registerUser(Auth::user()->id);
            }
            return $social->user_id;
        }
        return false;
    }
}
