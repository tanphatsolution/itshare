<?php namespace App\Data\System;

use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupSetting;
use App\Data\Blog\Notification;
use App\Data\Blog\Role;
use App\Data\Blog\UserRole;
use Event;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Data\Blog\BaseModel;
use DB;
use Auth;
use App\Data\Blog\Profile;
use App\Services\ImageService;
use App\Services\HelperService;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Services\GroupService;
use App\Data\Blog\GroupUser;
use App\Data\Blog\Stock;
use App\Events\UserFollowNotificationHandler;
use App\Data\Blog\Setting;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use SoftDeletes, Authenticatable, CanResetPassword;

    CONST TOP_USERS_LIMIT = 5;
    CONST TOP_USERS_FOOTER_LIMIT = 12;
    CONST TOP_RANDOM_USERS = 12;
    CONST DEFAULT_AVATAR_ID = 0;
    CONST USER_REQUEST_LIMIT = 4;
    CONST USER_BEST_ANSWER = 10;
    CONST USER_HELPFUL = 20;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'forget_token', 'active_token'];

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'username',
        'email',
        'work_email',
        'password',
        'phone',
        'avatar_id',
        'social_avatar_type',
        'social_avatar_url',
        'active_token',
        'active_work_email_token',
    ];

    public static $createRules = [
        'name' => 'required|min:5|max:50',
        'username' => 'required|regex:/^[a-z0-9][a-z0-9_\.]+$/i|min:3|max:50|unique:users,username',
        'password' => 'required|confirmed|max:50',
        'password_confirmation' => 'required',
        'email' => 'required|email|max:50|unique:users,email',
        'phone' => 'regex:/[0-9]{10,15}/',
    ];

    public static $updateRules = [
        'phone' => 'regex:/[0-9]{10,15}/',
        'email' => 'required_without_all:work_email|email|max:50|unique:users,email,:id',
        'work_email' => 'required_without_all:email|email',
    ];

    public static $messagesCreate = [
        'username.regex' => 'You can only input Alphabet and "." in Username form',
        'username.unique' => 'This user name is already used',
    ];

    public static $validateEmail = [
        'email' => 'required|email',
    ];

    public static function authRules($field)
    {
        $validations = [
            'username' => 'required|regex:/^[a-z0-9][a-z0-9_\.]+$/i|min:3|max:50',
            'email' => 'required|email|max:50',
        ];
        return [
            $field => $validations[$field],
            'password' => 'required|max:50',
        ];
    }

    public static $emailRule = [
        'email' => 'required|email|max:50',
    ];

    public static $updateInfoRule = [
        'name' => 'required|min:5|max:50',
        'phone' => 'regex:/[0-9]{10,15}/',
    ];

    public static $changePasswordRule = [
        'current_password' => 'required',
        'new_password' => 'required|different:current_password|confirmed|max:50',
        'new_password_confirmation' => 'required',
    ];

    /**
     * Make password HASH encrypted when save user info
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    /**
     * Authenticate user
     * @param array $input
     * @param string $field
     * @return int|boolean
     */
    public static function authenticate($input, $field)
    {
        $user = [
            $field => $input['username'],
            'password' => $input['password'],
        ];
        $rememberMe = isset($input['remember']) ? $input['remember'] : false;
        // Validation and authenticate successful and remembering.
        if (Auth::attempt($user, $rememberMe)) {
            $id = Auth::id();
            return $id;
        }
        return false;
    }

    public static function validate($input, $field)
    {
        $user = [
            $field => $input[$field],
            'password' => $input['password'],
        ];
        return Auth::validate($user);
    }

    public function socials()
    {
        return $this->hasMany('App\Data\Blog\Social')->whereNull('socials.deleted_at');
    }

    public function setting()
    {
        return $this->hasOne('App\Data\Blog\Setting');
    }

    public function userRole()
    {
        return $this->hasOne('App\Data\Blog\UserRole');
    }

    public function role()
    {
        return $this->hasOne('App\Data\Blog\UserRole');
    }

    public function ban()
    {
        return $this->hasOne('App\Data\Blog\UserBan');
    }

    public function profile()
    {
        return $this->hasOne('App\Data\Blog\Profile');
    }

    public function posts()
    {
        return $this->hasMany('App\Data\Blog\Post')->whereNotNull('encrypted_id');
    }

    public function post()
    {
        return $this->hasMany('App\Data\Blog\Post')->whereNotNull('published_at')->whereNotNull('encrypted_id');
    }

    public function publishedPosts()
    {
        return $this->hasMany('App\Data\Blog\Post')->whereNotNull('published_at')->whereNotNull('encrypted_id');
    }

    public function publishedQuestion()
    {
        return $this->hasMany('App\Data\Faq\Question')->whereNotNull('published_at')->whereNotNull('encrypted_id');
    }

    public function images()
    {
        return $this->hasMany('App\Data\Blog\Image')->where('id', '!=', $this->avatar_id);
    }

    public function avatar()
    {
        return $this->hasOne('App\Data\Blog\Image', 'id', 'avatar_id');
    }

    public function userPostLanguages()
    {
        return $this->hasMany('App\Data\Blog\UserPostLanguage');
    }

    public function groupUser()
    {
        return $this->hasMany('App\Data\Blog\GroupUser');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Data\Blog\Group', 'group_users', 'user_id', 'group_id')
            ->with('groupUsers', 'group_post_by_approved')
            ->where('active', GroupService::ACTIVE)
            ->where('status', GroupUser::STATUS_MEMBER)
            ->orderBy('created_at', 'DESC');
    }

    public function wiki()
    {
        return $this->hasMany('App\Data\Blog\Wiki');
    }

    public function contests()
    {
        return $this->hasMany('App\Data\Blog\Contest');
    }

    public static function boot()
    {
        parent::boot();

        // After create
        static::created(function ($user) {
            $user->setting()->save(new Setting());
            UserRole::createOrUpdate($user->id, Role::MEMBER);
            $user->profile()->save(new Profile());
        });

        // After update
        static::updated(function () {

        });

        // Before delete
        static::deleting(function ($user) {
            $user->userRole()->delete();
            $user->profile()->delete();
            $user->posts()->delete();
            $user->comments()->delete();
            //Unfollow
            $user->following()->delete();
            $user->followers()->delete();
            if ($user->ban) {
                $user->ban->delete();
            }
            //Notification
            $user->notifications()->delete();
            $user->activities()->delete();
        });
    }

    /**
     * Get a Gravatar URL for a specified email address.
     *
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @return String containing URL
     */
    function getGravatar($s = 20, $d = 'mm', $r = 'g')
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->email)));
        $url .= '?s=' . $s . '&d=' . $d . '&r=' . $r;
        return $url;
    }

    /**
     * Get user avatar from either Gravatar or user uploaded image.
     *
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     */
    function getAvatar($s = 20, $d = 'mm', $r = 'g', $img = false, $atts = [])
    {
        if ($this->avatar) {
            $imageService = new ImageService($this);
            $imageUrl = $imageService->getUploadUrl();
            $url = HelperService::getImageBy($imageUrl . $this->avatar->name, ImageService::AVATAR);
        } else if (!is_null($this->social_avatar_type) && !is_null($this->social_avatar_url)) {
            $url = $this->social_avatar_url;
        } else {
            $url = $this->getGravatar($s, $d, $r);
        }

        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }

    public function following()
    {
        return $this->hasMany('App\Data\Blog\UserRelationships', 'follower_id');
    }

    public function followers()
    {
        return $this->hasMany('App\Data\Blog\UserRelationships', 'followed_id');
    }

    public function follow($user)
    {
        if ($user->id == $this->id) {
            return false;
        }
        if ($this->following()->where('followed_id', $user->id)->count() == 0) {
            $userRelationship = $this->following()->create(['followed_id' => $user->id]);
            Event::fire(UserFollowNotificationHandler::EVENT_NAME, $userRelationship);
        }
    }

    public function unfollow($user)
    {
        if ($user->id == $this->id) {
            return false;
        }
        return $this->following()->where('followed_id', $user->id)->delete();
    }

    public function isFollowing($user)
    {
        return ($this->following()->where('followed_id', $user['id'])->count() > 0);
    }

    public function stocks()
    {
        return $this->hasMany('Stock');
    }

    public function clips()
    {
        return $this->hasMany('App\Data\System\User');
    }

    public function questions()
    {
        return $this->hasMany('App\Data\Faq\Question');
    }

    public function answers()
    {
        return $this->hasMany('App\Data\Faq\Answer');   
    }

    public function stockPosts()
    {
        if (Auth::check()) {
            $groupUsers = GroupUser::where('user_id', Auth::id())->lists('group_id');
            $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)
                ->whereNotIn('group_id', $groupUsers)
                ->lists('group_id');
        } else {
            $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        }
        $secretPostsId = GroupPost::whereIn('group_id', $secretGroupsId)
            ->whereNotNull('post_id')
            ->lists('post_id');
        return $this->belongsToMany('App\Data\Blog\Post', 'stocks', 'user_id', 'post_id')->whereNotNull('posts.id')->whereNotIn('posts.id', $secretPostsId);
    }

    public function reports()
    {
        return $this->hasMany('Report');
    }

    public function postReports()
    {
        return $this->belongsToMany('App\Data\Blog\Post', 'reports');
    }

    public function isBaned()
    {
        return ($this->ban()->count() > 0);
    }

    public static function getTopUsers()
    {
        $topUsers = User::with('avatar')
            ->select(DB::raw('count(stocks.id) as stocked_number'), 'users.*')
            ->whereNull('users.active_token')
            ->groupBy('users.id')
            ->leftJoin('posts', 'posts.user_id', '=', 'users.id')
            ->leftJoin('stocks', 'stocks.post_id', '=', 'posts.id')
            ->orderBy('stocked_number', 'desc')
            ->take(User::TOP_RANDOM_USERS)
            ->get();

        return $topUsers;
    }

    public function followingCategories()
    {
        return $this->belongsToMany('App\Data\Blog\Category', 'follow_categories', 'user_id', 'category_id');
    }

    /**
     * Relationship with Comment model
     *
     * @return relationship
     */
    public function comments()
    {
        return $this->hasMany('App\Data\Blog\Comment');
    }

    public function skills()
    {
        return $this->hasMany('App\Data\Blog\UserSkill');
    }

    public function userSkills()
    {
        return $this->belongsToMany('App\Data\Blog\Skill', 'user_skills', 'user_id', 'skill_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Data\Blog\Notification', 'recipient_id');
    }

    public function activities()
    {
        return $this->hasMany('App\Data\Blog\Notification', 'sender_id');
    }

    public function scopeLatestStock($query)
    {
        $stockTableName = Stock::getTableName();
        return $query->orderBy($stockTableName . '.created_at', 'desc');
    }

    public function getNotificationsCount()
    {
        $result = $this->notifications()->unread()->groupBy(['type', 'post_id'])->get();
        return $result->count();
    }

    public function notificationsUnreadIsNotSentMail()
    {
        $notifications = $this->notifications()
            ->where('status', Notification::STATUS_UNREAD)
            ->where('is_sent_mail', '!=', Notification::IS_SENT_MAIL);
        return $notifications;
    }

    public static function findByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    public function isDeleted()
    {
        return !is_null($this->deleted_at);
    }

    public function isActived()
    {
        return is_null($this->active_token);
    }

    public function isCurrent()
    {
        return Auth::id() ? ($this->id == Auth::id()) : false;
    }

    public function groupUsers()
    {
        return $this->hasMany('App\Data\Blog\GroupUser', 'user_id');
    }

    public function isAdminOf($group)
    {
        $result = $group->groupUsers()
            ->where('user_id', $this->id)
            ->where('role', '!=', GroupUser::ROLE_MEMBER)
            ->count();
        if ($result > 0) {
            return true;
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Data\Blog\Role', 'user_roles');
    }

    public function hightLight() {
        if ($this->total_best_answer >= User::USER_BEST_ANSWER || $this->total_helpful_answer >= User::USER_HELPFUL) {
            return ' comment-highlight';
        }
        return '';
    }
}
