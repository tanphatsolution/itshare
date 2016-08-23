<?php namespace App\Services;

use App\Facades\Authority;
use Carbon\Carbon;
use Session;
use Auth;
use Config;
use DB;
use URL;

use App\Data\Blog\Post;
use App\Data\Blog\Setting;
use App\Data\Blog\UserBan;
use App\Data\Blog\UserRelationships;
use App\Data\System\User;
use Response;

class UserService
{

    const FOLLOW_USER_PER_PAGE = 10;
    const MAX_PIECES_IN_PIE_CHART = 9;
    const POSTS_PER_PAGE = 12;
    const USERS_PER_PAGE = 10;
    const ADMIN_USERS_PER_PAGE = 200;

    const FIRST_HALF = 1;
    const SECOND_HALF = 2;

    const FILTER_ALL = 'all';
    const FILTER_FRAMGIA = 'framgia';
    const FILTER_OTHERS = 'others';

    public static function getAllEmailDomain()
    {
        $domains = [];
        $users = User::all(['email']);

        foreach ($users as $user) {
            $domain = explode('@', $user['email']);
            if (isset($domain[1]) && !empty($domains[1])) {
                $domain = '@' . $domain[1];
                array_push($domains, $domain);
            }
        }
        $domains = array_unique($domains);
        return $domains;
    }

    public static function confirm($id, $activeToken, $newUser = false)
    {
        if ($newUser) {
            $user = User::where('id', $id)
                ->where('active_token', $activeToken)
                ->first();
            if (is_null($user)) {
                return false;
            }
            $user->update([
                'active_token' => null
            ]);
        } else {
            $user = User::where('id', $id)
                ->where('active_work_email_token', $activeToken)
                ->first();
            if (is_null($user)) {
                return false;
            }

            $user->update([
                'active_work_email_token' => null
            ]);
        }
        return true;
    }

    public static function signup($input, $ajax = false, $sendEmail = true, $extraParams = [])
    {
        if ($sendEmail) {
            $input['active_token'] = str_random(100);
        }

        $defaultPostLang = '';
        if (isset($input['default_post_language'])) {
            $defaultPostLang = $input['default_post_language'];
            unset($input['default_post_language']);
        }

        $user = User::create($input);

        if (!empty($user)) {
            if (!empty($defaultPostLang)) {
                $user->setting->update([
                    'default_post_language' => $defaultPostLang,
                    'post_language_setting_flag' => Setting::DEFAULT_POST_LANG_SET
                ]);
            }

            if (isset($input['lang'])) {
                $user->setting()->update(['lang' => $input['lang']]);
            }
        }

        if ($sendEmail) {
            self::sendConfirmEmail($user->id, null, $extraParams);
        }

        if ($ajax) {
            return (bool)$user;
        }

        return $user->id;
    }

    public static function sendConfirmEmail($idOrEmail, $activeToken = null, $extraParams = [])
    {
        $field = filter_var($idOrEmail, FILTER_VALIDATE_EMAIL) ? 'email' : 'id';

        $user = User::where($field, $idOrEmail)
            ->whereNotNull('active_token')
            ->first();
        if (is_null($user)) {
            return false;
        }

        if (is_null($activeToken)) {
            $activeToken = str_random(100);
            $user->update([
                'active_token' => $activeToken,
            ]);
        }

        $data = [
            'id' => $user->id,
            'activeToken' => $user->activeToken,
            'password' => isset($extraParams['password']) ? $extraParams['password'] : '',
            'username' => isset($extraParams['username']) ? $extraParams['username'] : '',
            'lang' => LanguageService::getSystemLang(),
        ];

        $to = [
            'address' => $user->email,
            'name' => $user->name,
        ];
        
        $subject = trans('messages.user.email_confirm_subject', ['app_name' => Config::get('app.app_name')]);
        $layout = 'emails.user.confirm';
        MailService::send(Config::get('mail.from'), $to, $subject, $data, $layout, MailService::EMAIL_LOG_TYPE_CONFIRM);
        return true;
    }

    public static function login($input, $field, $validator)
    {
        $result = [
            'success' => false,
            'activeEmailFlag' => false,
            'activeWorkEmailFlag' => false,
            'yesBtn' => trans('buttons.yes'),
            'noBtn' => trans('buttons.no'),
            'titleEmail' => trans('messages.send_mail.confirm'),
            'activeEmailMsg' => trans('messages.user.active_work_email_notice'),
        ];
        if ($validator->fails()) {
            $result['messages'] = $validator->messages();
            return $result;
        }
        if ($field == 'email') {
            $email = $input['email'];
            $user = User::where(function ($query) use ($email) {
                return $query->where(function ($firstQuery) use ($email) {
                    return $firstQuery->where('email', $email)
                        ->whereNotNull('active_token');
                })
                    ->orWhere(function ($secondQuery) use ($email) {
                        return $secondQuery->where('work_email', $email)
                            ->whereNotNull('active_work_email_token');
                    });
            })->first();
        } else {
            $user = User::where($field, $input[$field])
                ->whereNotNull('active_token')
                ->first();
        }
        if (!is_null($user)) {
            $result['messages'] = ['msg' => trans('messages.user.active_notice')];

            if (!is_null($user->active_token)) {
                $result['activeEmailFlag'] = true;
            }

            if (!is_null($user->active_work_email_token) && is_null($user->active_token)) {
                $result['activeWorkEmailFlag'] = true;
            }

            return $result;
        }
        if (!User::authenticate($input, $field)) {
            if (!User::authenticate($input, 'work_email')) {
                $result['messages'] = ['msg' => trans('messages.user.incorrect_login_info')];
                return $result;
            }
        }
        $isBanedUser = self::isBanedUser(Auth::user());
        if ($isBanedUser) {
            Auth::logout();
            $result['messages'] = ['msg' => trans('messages.user.user_is_ban')];
            return $result;
        }
        $result = [
            'success' => true,
            'url' => Session::pull('returnUrl', action('HomeController@getTopPage')),
        ];

        RedisService::registerUser(Auth::user()->id);
        return $result;
    }

    public static function updateUserInfo($id, $input)
    {
        $result = [
            'success' => false,
            'message' => trans('messages.user.update_fail'),
        ];
        $user = User::find($id);
        if ($user->update($input)) {
            $result = [
                'success' => true,
                'message' => trans('messages.user.update_success'),
            ];
        }
        return $result;
    }

    public static function sendPasswordChangedEmail($user, $updatedAt)
    {
        $data = [
            'user' => $user,
            'updatedAt' => $updatedAt,
        ];
        $to = [
            'address' => $user->email,
            'name' => $user->name,
        ];
        $subject = trans('messages.user.email_password_subject');
        $layout = 'emails.user.change_password';
        MailService::send(Config::get('mail.from'), $to, $subject, $data, $layout, MailService::EMAIL_LOG_TYPE_PASSWORD_CHANGE);
    }

    public static function changePassword($id, $input, $sendEmail = false)
    {
        $result = [
            'success' => false,
            'message' => trans('messages.user.change_password_fail'),
        ];
        $user = User::find($id);
        if (!\Hash::check($input['current_password'], $user->password)) {
            $result['message'] = trans('messages.user.incorrect_current_password');
            return $result;
        }
        $input = [
            'password' => $input['new_password'],
        ];
        if ($user->update($input)) {
            if ($sendEmail) {
                self::sendPasswordChangedEmail($user, Carbon::now('Asia/Bangkok'));
            }
            $result = [
                'success' => false,
                'message' => trans('messages.user.change_password_success'),
            ];
            return $result;
        }
        return $result;
    }

    public static function postTendency($user)
    {
        $publishedPosts = Post::with('categories')
            ->where('user_id', $user->id)
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->get();
        $categories = [];
        foreach ($publishedPosts as $post) {
            if (count($post->categories) === 0) {
                if (!isset($categories['Others'])) {
                    $categories['Others'] = 1;
                } else {
                    $categories['Others']++;
                }
                continue;
            }
            foreach ($post->categories as $category) {
                if (isset($categories[$category->name])) {
                    $categories[$category->name]++;
                } else {
                    $categories[$category->name] = 1;
                }
            }
        }

        if (count($categories) > self::MAX_PIECES_IN_PIE_CHART) {
            arsort($categories);
            $total = array_sum($categories);
            $categories = array_slice($categories, 0, self::MAX_PIECES_IN_PIE_CHART);
            $other = $total - array_sum($categories);
            if (isset($categories['Others'])) {
                $categories['Others'] += $other;
            } else {
                $categories['Others'] = $other;
            }
        }

        return $categories;
    }

    public static function getProfile($user)
    {
        return $user->profile;
    }

    public static function getSetting($user)
    {
        return $user->setting;
    }

    public static function getSocialList($user)
    {
        return $user->socials->lists('uid', 'type');
    }

    public static function getSocialListLink($user)
    {
        return $user->socials->lists('link', 'type');
    }

    public static function getContribution($user)
    {
        $cnt = 0;

        if (!empty($user)) {
            $cnt += $user->comments()->count();
            $cnt += $user->posts()->count();
        }

        return $cnt;
    }

    public static function canDeleteComment($user, $comment)
    {
        if ($user->id == $comment->userId || Authority::hasRoleByUser($user, 'admin')) {
            return true;
        } else {
            return false;
        }
    }

    public static function canEditComment($user, $comment)
    {
        if ($user->id == $comment->userId) {
            return true;
        } else {
            return false;
        }
    }

    public static function searchByName($name, $withTrashed = true)
    {
        if ($withTrashed) {
            return User::withTrashed()
                ->with('ban')
                ->where(function ($query) use ($name) {
                    $query->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('username', 'LIKE', '%' . $name . '%')
                        ->orWhere('email', 'LIKE', '%' . $name . '%')
                        ->orWhere('work_email', 'LIKE', '%' . $name . '%');
                })
                ->paginate(self::ADMIN_USERS_PER_PAGE);
        } else {
            return User::with('ban', 'role')
                ->where(function ($query) use ($name) {
                    $query->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('username', 'LIKE', '%' . $name . '%')
                        ->orWhere('email', 'LIKE', '%' . $name . '%')
                        ->orWhere('work_email', 'LIKE', '%' . $name . '%');
                })
                ->paginate(self::ADMIN_USERS_PER_PAGE);
        }
    }

    public static function delete($id)
    {
        $user = User::find($id);
        $message = trans('messages.error');
        if (!$user) {
            $message = trans('messages.user.not_exist', ['item' => $id]);
            return [$message, true];
        }
        if ($user->delete()) {
            $message = trans('messages.user.has_deleted', ['item' => $user->name]);
            return [$message, false];
        }
        return [$message, true];
    }

    public static function checkCurrentUser($currentUser, $username)
    {
        if ($currentUser && $currentUser->username === $username) {
            return true;
        }

        return false;
    }

    public static function isBanedUser($user)
    {
        $ban = $user->ban;
        if (!$ban) {
            return false;
        }
        if (Carbon::now() >= $ban->lift_date) {
            return !self::unBan($ban);
        }
        return true;
    }

    public static function unBan($ban)
    {
        if ($ban && $ban->delete()) {
            return true;
        }
        return false;
    }

    public static function banUser($input, $user, $id)
    {
        if (!$user || !$user->isActived()) {
            $message = trans('messages.user.not_exist', ['item' => $id]);
            $response = [
                'message' => $message,
                'id' => $id,
                'error' => true,
            ];
            return Response::json($response, 200);
        }
        $rules = UserBan::getRules();
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $result = array();
            $result['messages'] = $validator->messages();
            $result['id'] = $id;
            $result['error'] = true;
            return $result;
        }

        if (UserBan::create($input)) {
            $result = [
                'id' => $id,
                'success' => true,
                'url' => URL::action('UsersController@getView'),
            ];
            return $result;
        }
        $message = trans('messages.user.not_ban', ['item' => $id]);
        $result = [
            'id' => $id,
            'message' => $message,
            'error' => true,
        ];
        return $result;
    }

    public static function statisticAll($filter)
    {
        if ($filter != self::FILTER_ALL) {
            $usersIdFramgia = self::getUsersIdFramgia();
        }

        switch ($filter) {
            case self::FILTER_ALL:
                $blockedUsers = UserBan::all()->lists('user_id');
                $results = [
                    'deleted' => User::withTrashed()
                        ->whereNotNull('deleted_at')
                        ->count(),
                    'actived' => User::whereNull('active_token')
                        ->whereNotIn('id', $blockedUsers)
                        ->count(),
                    'notActive' => User::withTrashed()
                        ->whereNotNull('active_token')
                        ->whereNull('deleted_at')
                        ->count(),
                    'blocked' => count($blockedUsers),
                    'total' => User::withTrashed()
                        ->count(),
                ];
                break;
            case self::FILTER_FRAMGIA:
                $blockedUsers = UserBan::whereIn('user_id', $usersIdFramgia)
                    ->lists('user_id');
                $results = [
                    'deleted' => User::withTrashed()
                        ->whereIn('id', $usersIdFramgia)
                        ->whereNotNull('deleted_at')
                        ->count(),
                    'actived' => User::whereNull('active_token')
                        ->whereIn('id', $usersIdFramgia)
                        ->whereNotIn('id', $blockedUsers)
                        ->count(),
                    'notActive' => User::withTrashed()
                        ->whereIn('id', $usersIdFramgia)
                        ->whereNotNull('active_token')
                        ->whereNull('deleted_at')
                        ->count(),
                    'blocked' => count($blockedUsers),
                    'total' => User::withTrashed()
                        ->whereIn('id', $usersIdFramgia)
                        ->count(),
                ];
                break;
            case self::FILTER_OTHERS:
                $blockedUsers = UserBan::whereNotIn('id', $usersIdFramgia)
                    ->lists('user_id');
                $results = [
                    'deleted' => User::withTrashed()
                        ->whereNotIn('id', $usersIdFramgia)
                        ->whereNotNull('deleted_at')
                        ->count(),
                    'actived' => User::whereNull('active_token')
                        ->whereNotIn('id', $usersIdFramgia)
                        ->whereNotIn('id', $blockedUsers)
                        ->count(),
                    'notActive' => User::withTrashed()
                        ->whereNotIn('id', $usersIdFramgia)
                        ->whereNotNull('active_token')
                        ->whereNull('deleted_at')
                        ->count(),
                    'blocked' => count($blockedUsers),
                    'total' => User::withTrashed()
                        ->whereNotIn('id', $usersIdFramgia)
                        ->count(),
                ];
                break;

            default:
                $blockedUsers = UserBan::all()->lists('user_id');
                $results = [
                    'deleted' => User::withTrashed()
                        ->whereNotNull('deleted_at')
                        ->count(),
                    'actived' => User::whereNull('active_token')
                        ->whereNotIn('id', $blockedUsers)
                        ->count(),
                    'notActive' => User::withTrashed()
                        ->whereNotNull('active_token')
                        ->whereNull('deleted_at')
                        ->count(),
                    'blocked' => count($blockedUsers),
                    'total' => User::withTrashed()
                        ->count(),
                ];
                break;
        }

        return $results;
    }

    public static function statisticInMonth($month, $year, $filter)
    {
        $usersIdFramgia = [];

        if ($filter != self::FILTER_ALL) {
            $usersIdFramgia = self::getUsersIdFramgia();
        }

        $users = User::withTrashed()
            ->select(DB::raw('extract(day from created_at) as day'),
                DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month);

        switch ($filter) {
            case self::FILTER_ALL:
                $users = $users->groupBy(DB::raw('extract(day from created_at)'))
                    ->lists('total', 'day');
                break;
            case self::FILTER_FRAMGIA:
                $users = $users->whereIn('id', $usersIdFramgia)
                    ->groupBy(DB::raw('extract(day from created_at)'))
                    ->lists('total', 'day');
                break;
            default:
                $users = $users->groupBy(DB::raw('extract(day from created_at)'))
                    ->lists('total', 'day');
                break;
        }

        return $users;
    }

    public static function statisticByWeek($weeks, $year, $filter)
    {
        $usersIdFramgia = [];
        if ($weeks == self::FIRST_HALF) {
            $weeks_start = 1;
            $weeks_end = 26;
        } else {
            $weeks_start = 27;
            $weeks_end = 52;
        }

        if ($filter != self::FILTER_ALL) {
            $usersIdFramgia = self::getUsersIdFramgia();
        }
        $users = User::withTrashed()
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
                DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'),
                DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 8-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_end'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->groupBy('week');

        switch ($filter) {
            case self::FILTER_ALL:
                $users = $users->get();
                break;
            case self::FILTER_FRAMGIA:
                $users = $users->whereIn('id', $usersIdFramgia)
                    ->get();
                break;
            case self::FILTER_FRAMGIA:
                $users = $users->whereNotIn('id', $usersIdFramgia)
                    ->get();
                break;
            default:
                $users = $users->get();
                break;
        }

        return $users;
    }

    public static function getWeeksOptions()
    {
        return [
            self::FIRST_HALF => trans('messages.user.weeks') . ' 1 - 26',
            self::SECOND_HALF => trans('messages.user.weeks') . ' 27 - 54',
        ];
    }

    public static function follower($currentUser)
    {
        return UserRelationships::where('follower_id', $currentUser->id);
    }

    public static function getDeletedUsers()
    {
        return User::withTrashed()
            ->whereNotNull('deleted_at')
            ->orWhereIn('id', UserBan::where('lift_date', '>=', Carbon::now())->lists('user_id'))
            ->get();
    }

    public static function validatorWorkEmail($email, $currentUser)
    {
        $errors = [];
        $email['work_email'] = trim($email['work_email']);
        $emailRule = ['work_email' => 'email'];
        $isEmail = \Validator::make($email, $emailRule);

        if ($isEmail->fails()) {
            $errors['not_email'] = trans('messages.user.not_email');
        } elseif (!empty($email['work_email'])) {
            $users = User::where('id', '<>', $currentUser->id)
                ->where('email', $email['work_email'])
                ->orWhere('work_email', $email['work_email'])
                ->where('id', '<>', $currentUser->id)
                ->get();
            if ($users->count() > 0) {
                $errors['exist_work_email'] = trans('messages.user.exist_work_email');
            }
        }

        return $errors;
    }

    public static function confirmWorkEmail($id, $activeToken)
    {
        if (is_null($id) || is_null($activeToken)) {
            return false;
        }

        $user = User::where('id', $id)
            ->where('active_work_email_token', $activeToken)
            ->first();
        if (is_null($user)) {
            return false;
        }

        $user->update([
            'active_work_email_token' => null,
        ]);

        return true;
    }

    public static function getFilterOptions()
    {
        return [
            self::FILTER_ALL => trans('messages.user.filter_all'),
            self::FILTER_FRAMGIA => trans('messages.user.filter_framgia'),
            self::FILTER_OTHERS => trans('messages.user.filter_others'),
        ];
    }

    public static function getUsersIdFramgia()
    {
        $usersIdFramgia = User::withTrashed()
            ->where(function ($query) {
                return $query->where('work_email', 'like', Config::get('mail.extension'))
                    ->orWhere('email', 'like', Config::get('mail.extension'));
            })
            ->lists('id');

        return $usersIdFramgia;
    }

    public static function getUsersMagazine()
    {
        return User::with('profile', 'setting')->whereIn('email', ['itshare.asia@gmail.com']);
    }

    public static function getUserByContest($contest, $domains = [], $categories = [])
    {
        $startDate = Carbon::parse($contest->term_start)->format('Y-m-d');
        $endDate = Carbon::parse($contest->term_end)->format('Y-m-d');

        $scoreEndDate = Carbon::parse($contest->term_score_end)->format('Y-m-d');

        $userQuery = User::select('users.id', 'name', 'username', 'email',
            DB::raw('
                (SELECT COUNT(*) FROM comments 
                 WHERE comments.post_id IN (SELECT id FROM posts WHERE posts.user_id = users.id AND DATE_FORMAT(published_at, "%Y-%m-%d") >= "' . $startDate . '" AND DATE_FORMAT(published_at, "%Y-%m-%d") <= "' . $endDate . '")
                 AND comments.user_id != users.id 
                 AND DATE_FORMAT(comments.created_at, "%Y-%m-%d") <= "' . $scoreEndDate . '") + 
                
                (SELECT COUNT(*) FROM stocks 
                 WHERE stocks.post_id IN (SELECT id FROM posts WHERE posts.user_id = users.id AND DATE_FORMAT(published_at, "%Y-%m-%d") >= "' . $startDate . '" AND DATE_FORMAT(published_at, "%Y-%m-%d") <= "' . $endDate . '")
                 AND stocks.user_id != users.id
                 AND DATE_FORMAT(stocks.created_at, "%Y-%m-%d") <= "' . $scoreEndDate . '") + 
            
                (SELECT COUNT(*) FROM post_helpfuls 
                 WHERE post_helpfuls.post_id IN (SELECT id FROM posts WHERE posts.user_id = users.id AND DATE_FORMAT(published_at, "%Y-%m-%d") >= "' . $startDate . '" AND DATE_FORMAT(published_at, "%Y-%m-%d") <= "' . $endDate . '")
                 AND post_helpfuls.user_id != users.id
                 AND DATE_FORMAT(post_helpfuls.created_at, "%Y-%m-%d") <= "' . $scoreEndDate . '") AS score'),

            DB::raw('count(posts.id) as total_post'))
            ->join('posts', function ($query) use ($startDate, $endDate) {
                $query->on('posts.user_id', '=', 'users.id');
                $query->where(DB::raw('DATE_FORMAT(published_at, "%Y-%m-%d")'), '>=', $startDate);
                $query->where(DB::raw('DATE_FORMAT(published_at, "%Y-%m-%d")'), '<=', $endDate);
            })
            ->groupBy('users.id')
            ->orderBy('score', 'DESC');
        if ($categories != null) {
            $postIds = \App\Data\Blog\PostCategory::whereIn('category_id', $categories)->lists('post_id');
            if ($postIds != null) {
                $userQuery->whereIn('posts.id', $postIds);
            }
        }

        if ($domains != null) {
            $userQuery->whereIn(DB::raw("(SUBSTRING_INDEX(SUBSTR(users.email, INSTR(users.email, '@') + 0), '.', (length(users.email) - 1)))"), $domains);
        }

        return $userQuery;
    }
}
