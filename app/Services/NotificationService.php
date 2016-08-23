<?php namespace App\Services;

use App\Data\Blog\Feedback;
use App\Data\Blog\MonthlyThemeSubject;
use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Data\Blog\PostCategory;
use App\Data\Blog\PostHelpful;
use App\Data\Blog\Report;
use App\Data\Blog\Role;
use App\Data\Blog\Setting;
use App\Data\Blog\UserPostLanguage;
use App\Data\System\User;
use Carbon\Carbon;
use Config;
use File;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    CONST PER_PAGE = 20;
    CONST PER_POPUP = 5;
    CONST NO_NOTIFICATION = 0;

    public static function getAllNotifications($user, $paginate = self::PER_PAGE)
    {
        self::readAllNotifications($user);
        $notifications = $user->notifications()
            ->orderBy('updated_at', 'desc')
            ->paginate($paginate);

        return $notifications;
    }

    public static function getNotificationsBy($type, $user, $paginate = self::PER_PAGE)
    {
        self::readAllNotifications($user);
        $types = self::getTypeNotify();
        $notifications = $user->notifications();

        switch ($type) {
            case Notification::NOTIFY_TYPE:
                $notifications = $notifications->whereIn('type', $types[Notification::NOTIFY_TYPE]);
                break;
            case Notification::REQUEST_TYPE:
                $notifications = $notifications->whereIn('type', $types[Notification::REQUEST_TYPE]);
                break;

            default:
                break;
        }

        $notifications = $notifications->orderBy('updated_at', 'desc')
            ->paginate($paginate);

        return $notifications;
    }

    /**
     * @param User $user
     * @return int $count
     */
    public static function updateNotificationsCount($user)
    {
        $count = $user->getNotificationsCount();
        RedisService::setNotificationsCount($user, $count);
        return $count;
    }

    public static function readAllNotifications($user)
    {
        $user->notifications()->unread()->update([
            'status' => Notification::STATUS_READ,
        ]);
        RedisService::setNotificationsCount($user, self::NO_NOTIFICATION);
    }

    public static function getUnreadNotifications($user, $type = null)
    {
        $types = self::getTypeNotify();
        $notifications = $user->notifications()->unread();

        switch ($type) {
            case Notification::NOTIFY_TYPE:
                $notifications = $notifications->whereIn('type', $types[Notification::NOTIFY_TYPE]);
                break;
            case Notification::REQUEST_TYPE:
                $notifications = $notifications->whereIn('type', $types[Notification::REQUEST_TYPE]);
                break;

            default:
                break;
        }

        $notifications = $notifications->orderBy('updated_at', 'desc')
            ->get();

        return $notifications;
    }

    public static function getIdNotifications($notifications)
    {
        $listIds = [];
        if (empty($notifications)) {
            return $listIds;
        }
        foreach ($notifications as $notification) {
            $listIds[] = $notification->id;
        }
        return $listIds;
    }

    public static function fetchNotifications($user, $input)
    {
        if ($input['init'] == 'true') {
            $notifications = self::getNotificationsBy(Notification::NOTIFY_TYPE, $user, self::PER_POPUP);
            $requests = self::getNotificationsBy(Notification::REQUEST_TYPE, $user, self::PER_POPUP);
        } else {
            $requests = self::getUnreadNotifications($user, Notification::REQUEST_TYPE);
            $notifications = self::getUnreadNotifications($user, Notification::NOTIFY_TYPE);
            self::readAllNotifications($user);
        }
        return [
            'notifications' => $notifications,
            'requests' => $requests,
        ];
    }

    public static function sendMailNotifications()
    {
        $users = User::select('users.id', 'users.username', 'users.email')
            ->leftJoin('settings', 'settings.user_id', '=', 'users.id')
            ->leftJoin('notifications', 'notifications.recipient_id', '=', 'users.id')
            ->where('settings.receive_mail_notification', Notification::RECEIVE_MAIL_NOTIFICATION)
            ->where('notifications.status', Notification::STATUS_UNREAD)
            ->where('notifications.is_sent_mail', '!=', Notification::IS_SENT_MAIL)
            ->groupBy('users.id')
            ->get();

        $from = Config::get('mail.from');

        foreach ($users as $user) {
            $to = [
                'address' => $user->email,
                'name' => $user->name,
            ];
            $subject = trans('messages.notification.mail_subject_notify');
            $layout = 'emails.user.notifications';

            $notifications = $user->notificationsUnreadIsNotSentMail();
            $data = [
                'username' => $user->username,
                'notifications' => $notifications->get(),
            ];
            $notifications->update(['is_sent_mail' => Notification::IS_SENT_MAIL]);
            Mail::send($layout, $data, function ($message) use ($from, $to, $subject) {
                $message->from($from['address'], $from['name']);
                $message->to($to['address'], $to['name'])->subject($subject);
            });
        }
    }

    public static function sentMailNotifyTopPosts()
    {
        $topPosts = Post::getStockRankingByPost();
        Setting::where('receive_mail_notification', Notification::RECEIVE_MAIL_NOTIFICATION)->get();
        $users = User::select('users.id', 'users.username', 'users.email')
            ->leftJoin('settings', 'settings.user_id', '=', 'users.id')
            ->where('settings.receive_mail_notification', Notification::RECEIVE_MAIL_NOTIFICATION)
            ->get();

        $from = Config::get('mail.from');

        foreach ($users as $user) {
            $to = [
                'address' => $user->email,
                'name' => $user->name,
            ];
            $subject = trans('messages.notification.mail_subject_top_posts');
            $layout = 'emails.user.notifyTopPosts';
            $data = [
                'id' => $user->id,
                'username' => $user->username,
                'notifications' => $topPosts,
            ];
            Mail::send($layout, $data, function ($message) use ($from, $to, $subject) {
                $message->from($from['address'], $from['name']);
                $message->to($to['address'], $to['name'])->subject($subject);
            });
        }
    }

    public static function sentMailNotifyAdmin($type = null, $object = null)
    {
        $today = new \DateTime();
        $countRecord = 0;
        $reports = null;

        if (isset($type) && $type == 'feedback') {
            $created_time = (isset($object) && $object->created_at != '') ? $object->created_at : $today;
            $feedbacks = Feedback::where('created_at', $created_time)->get();
            $countRecord += $feedbacks->count();
        } else {
            $interval = Config::get('constants.sent_admin_email_interval');
            $created_time = $today->modify($interval);
            $feedbacks = Feedback::where('created_at', '>', $created_time)->get();
            $reports = Report::where('created_at', '>', $created_time)->get();
            $countRecord += $feedbacks->count() + $reports->count();
        }

        if ($countRecord > 0 ) {
            $adminsArr = User::leftJoin('user_roles','users.id', '=', 'user_roles.user_id')
                ->select('users.name', 'users.email', 'users.work_email')
                ->where('user_roles.role_id', Role::ADMIN)
                ->where(function($query) {
                    $query->where('users.email', 'LIKE', Config::get('mail.extension'))
                        ->orWhere('users.work_email', 'LIKE', Config::get('mail.extension'));
                })->get();

            $admins = array();
            
            foreach($adminsArr as $admin) {
                $admins[] = array(
                    'email' => empty($admin->work_email) ? $admin->email : $admin->work_email,
                    'name' => $admin->name
                );
            }

            $from = Config::get('mail.from');

            foreach ($admins as $admin) {
                $to = [
                    'address' => $admin['email'],
                    'name' => $admin['name'],
                ];
                $subject = trans('messages.notification.mail_subject_notify_admin');
                $layout = 'emails.admin.notify';
                $data = [
                    'name' => $admin['name'],
                    'feedbacks' => $feedbacks,
                    'reports' => $reports
                ];

                Mail::send($layout, $data, function ($message) use ($from, $to, $subject) {
                    $message->from($from['address'], $from['name']);
                    $message->to($to['address'], $to['name'])->subject($subject);
                });
            }
        }
    }

    public static function sendMailMagazineWeekly()
    {
        $from = Config::get('mail.from');
        $offsetFile = storage_path() . '/logs/offset.log';
        $data = [];

        if(!File::exists($offsetFile)) {
            $usersForMail = UserService::getUsersMagazine()->where('users.id', '>', 0)->take(500)->get();
            $saveInfo = [
                'offset' => max($usersForMail->lists('id')->toArray()),
                'date'  => date('d/m/Y')
            ];
        } else {
            $info = json_decode(file_get_contents($offsetFile), true);
            $startIdUser = isset($info['offset']) && $info['offset'] > 0 ? $info['offset'] : 0;
            $usersForMail = UserService::getUsersMagazine()->where('users.id', '>', $startIdUser)->take(500)->get();
            if ($info['date'] == date('d/m/Y')) {
                if ($usersForMail->lists('id') != null) {
                    $saveInfo = array(
                        'offset' => $usersForMail->lists('id')->toArray() != null ? max($usersForMail->lists('id')->toArray()) : 0,
                        'date'  => $info['date']
                    );
                }
            } else {
                $usersForMail = UserService::getUsersMagazine()->where('users.id', '>', 0)->take(500)->get();
                if ($usersForMail->lists('id') != null) {
                    $saveInfo = array(
                        'offset' => max($usersForMail->lists('id')->toArray()),
                        'date'  => date('d/m/Y')
                    );
                }
            }
        }

        if (isset($saveInfo) && $saveInfo != null) {
            if(File::exists($offsetFile)) {
                File::delete($offsetFile);
            }
            file_put_contents($offsetFile, json_encode($saveInfo));
            chmod($offsetFile, 0777);
        }

        if (isset($usersForMail) && $usersForMail->count() > 0) {
            $data['lastMonday'] = date('d/m', strtotime('monday last week'));
            $data['lastSunday'] = date('d/m', strtotime('sunday last week'));
            $lastTimeSendMail = date('Y-m-d', strtotime('monday last week')).' 07:00:00';

            foreach($usersForMail as $user) {
                $setting = $user->setting()->get();
                if (isset($setting[0]) && $setting[0]->receive_weekly_magazine == 1) {
                    App::setLocale($user->setting->lang);

                    $subject = trans('messages.notification.mail_subject_magazine_weekly');
                    $layout = 'emails.magazine.weekly_post';

                    $postLangs = $user->userPostLanguages()->lists('language_code');

                    $data['populars'] = PostService::getPopularPostsMagazine(Post::POPULAR_POST_MAGAZINE_LIMIT_INDEX, $postLangs);

                    $authors = $user->following()->lists('followed_id');

                    $followingPosts = Post::with('categories', 'user', 'user.profile')
                        ->whereIn('user_id', $authors)
                        ->whereNull('deleted_at')
                        ->where('published_at', '>=', $lastTimeSendMail);

                    if (isset($postLangs[0]) && count($postLangs) > 0 && $postLangs[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                        $followingPosts->whereIn('language_code', $postLangs);
                    }

                    $data['followingPosts'] = $followingPosts->orderBy('published_at', 'desc')->take(3)->get();

                    $cate = $user->followingCategories()->get()->lists('id');

                    $postIds = PostCategory::whereIn('category_id', $cate)->lists('post_id');

                    $categoriesFollowing = Post::with('categories', 'user', 'user.profile')
                        ->whereIn('id', $postIds)
                        ->whereNull('deleted_at');

                    if (isset($postLangs[0]) && count($postLangs) > 0 && $postLangs[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                        $categoriesFollowing->whereIn('language_code', $postLangs);
                    }

                    $data['categoriesFollowing'] = $categoriesFollowing->whereNotNull('published_at')
                        ->orderByRaw('RAND()')
                        ->take(3)
                        ->get();

                    $currentMonthThemeSubject = MonthlyThemeService::getCurrentMonthThemeSubject();
                    if(isset($currentMonthThemeSubject) && $currentMonthThemeSubject != null) {
                        $themeCurrent = self::getMonthlyMagazine($currentMonthThemeSubject->short_name);
                        $data['short_name'] = $themeCurrent['short_name'];
                        $data['monthSubject'] = $themeCurrent['monthSubject'];
                    }

                    if (count($data['populars']) > 0 || count($data['followingPosts']) > 0 || count($data['categoriesFollowing']) > 0) {
                        Mail::send($layout, $data, function ($message) use ($from, $user, $subject) {
                            $message->from($from['address'], $from['name']);
                            $message->to(empty($user->work_email) ? $user->email : $user->work_email, $user->name);
                            $message->subject($subject);
                        });
                        \Log::info('Send weekly email to: ' . (empty($user->work_email) ? $user->email : $user->work_email));
                    }
                }
            }
        }
    }

    public static function sendMailMagazineMonthly() {
        $from = Config::get('mail.from');
        $users = UserService::getUsersMagazine()->get();

        if (isset($users) && $users->count() > 0) {
            foreach($users as $user) {
                $setting = $user->setting()->get();
                if (isset($setting[0]) && $setting[0]->receive_monthly_magazine == 1) {
                    App::setLocale($user->setting->lang);

                    $subject = trans('messages.notification.mail_subject_magazine_monthly');
                    $layout = 'emails.magazine.monthly_theme';

                    $currentMonthThemeSubject = MonthlyThemeService::getCurrentMonthThemeSubject();
                    if(isset($currentMonthThemeSubject) && $currentMonthThemeSubject != null) {
                        $data = self::getMonthlyMagazine($currentMonthThemeSubject->short_name);
                        Mail::send($layout, $data, function ($message) use ($from, $user, $subject) {
                            $message->from($from['address'], $from['name']);
                            $email = empty($user->work_email) ? $user->email : $user->work_email;
                            $message->to($email, $user->name);
                            $message->subject($subject);
                        });
                        \Log::info('Send monthly email to: ' . (empty($user->work_email) ? $user->email : $user->work_email));
                    }
                }
            }
        }
    }

    public static function sendMailMagazineMonthlyNoPost()
    {
        $from = Config::get('mail.from');
        $users = UserService::getUsersMagazine()->get();

        if (isset($users) && $users->count() > 0) {
            $data = [];
            $lastMonth = Carbon::now()->subMonth()->month;
            $dayOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->daysInMonth;
            $dayOfLastMonth = ($dayOfLastMonth < 10) ? '0'.$dayOfLastMonth : $dayOfLastMonth;
            $lastMonthOfYear = Carbon::now()->subMonth()->year;
            $startDateOfMonth = $lastMonthOfYear.'-'.$lastMonth.'-01 00:00:00';
            $endDateOfMonth = $lastMonthOfYear.'-'.$lastMonth.'-'.$dayOfLastMonth.' 23:59:59';
            $lastTimeSendMail = date('Y-m-d', strtotime('-14 days')).' 16:00:00';

            $postCount = Post::with('categories', 'user', 'user.profile')
                ->where('published_at', '>=', $startDateOfMonth)
                ->where('published_at', '<=', $endDateOfMonth)
                ->whereNull('deleted_at')
                ->get();

            $data['lastMonth'] = trans('magazine.month.'.$lastMonth);
            $data['lastMonthPost'] = $postCount->count();

            foreach($users as $user) {
                $setting = $user->setting()->get();
                if (isset($setting[0]) && $setting[0]->receive_other_mail == 1) {
                    App::setLocale($user->setting->lang);
                    $subject = trans('messages.notification.mail_subject_magazine_no_post');
                    $layout = 'emails.magazine.user_no_post';

                    $currentMonthThemeSubject = MonthlyThemeService::getCurrentMonthThemeSubject();

                    if(isset($currentMonthThemeSubject) && $currentMonthThemeSubject != null) {
                        $themeOfMonth = self::getMonthlyMagazine($currentMonthThemeSubject->short_name);
                        $data['short_name'] = $themeOfMonth['short_name'];
                        $data['monthSubject'] = $themeOfMonth['monthSubject'];
                    }

                    if ($user->publishedPosts()->count() == 0) {
                        $postLangs = $user->userPostLanguages()->lists('language_code');
                        //Post clip
                        $clipPosts = Post::with('categories', 'user', 'user.profile')->where('posts.stocks_count', '>', 0);

                        if (isset($postLangs[0]) && count($postLangs) > 0 && $postLangs[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                            $clipPosts->whereIn('language_code', $postLangs);
                        }
                        $data['mostClips'] = $clipPosts->orderBy('posts.stocks_count', 'desc')->take(3)->skip(0)->get();
                        
                        // Post Helpful
                        $postIdsHelpful = PostHelpful::select(
                            'post_id',
                            DB::raw('COUNT(IF(helpful, 1, NULL)) AS helpful_yes'),
                            DB::raw('COUNT(IF(helpful, NULL, 1)) AS helpful_no')
                        )
                            ->groupBy('post_id')
                            ->orderBy('helpful_yes', 'desc')
                            ->orderBy('helpful_no', 'asc')
                            ->lists('post_id')
                            ->toArray();

                        $orderBy = implode(',', $postIdsHelpful);

                        $mostHelpful = Post::with('categories', 'user', 'user.profile')
                            ->whereIn('id', $postIdsHelpful);

                        if (isset($postLangs[0]) && count($postLangs) > 0 && $postLangs[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                            $mostHelpful->whereIn('language_code', $postLangs);
                        }

                        $data['mostHelpful'] = $mostHelpful->orderByRaw(DB::raw('FIELD(id, ' . $orderBy . ')'))->take(3)->get();

                        $authors = $user->following()->lists('followed_id');

                        $followingPosts = Post::with('categories', 'user', 'user.profile')
                            ->whereIn('user_id', $authors)
                            ->whereNull('deleted_at')
                            ->where('published_at', '>=', $lastTimeSendMail);

                        if (isset($postLangs[0]) && count($postLangs) > 0 && $postLangs[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                            $followingPosts->whereIn('language_code', $postLangs);
                        }

                        $data['followingPosts'] = $followingPosts->whereNull('deleted_at')
                            ->whereNotNull('published_at')
                            ->orderBy('published_at', 'desc')
                            ->take(3)
                            ->get();

                        Mail::send($layout, $data, function ($message) use ($from, $user, $subject) {
                            $message->from($from['address'], $from['name']);
                            $email = empty($user->work_email) ? $user->email : $user->work_email;
                            $message->to($email, $user->name)->subject($subject);
                        });
                        \Log::info('Send no_post email to: ' . (empty($user->work_email) ? $user->email : $user->work_email));
                    }
                }
            }
        }
    }

    public static function getMonthlyMagazine($themeName)
    {
        if ($themeName != null) {
            $monthSubjectByName = MonthlyThemeSubject::where('short_name', $themeName)->first();
        }
        $year = !empty($monthSubjectByName) ? $monthSubjectByName->publish_year : null;
        $month = !empty($monthSubjectByName) ? $monthSubjectByName->publish_month : null;
        $monthSubject = MonthlyThemeService::getMonthThemes($year, $month);
        if (!empty($monthSubject)) {
            $professionals = MonthlyThemeService::getProfessionalMagazineByThemeSubjectId($monthSubject->id);
        }
        $data = [
            'endDate' => date('t', strtotime($year . '-' . $month . '-1')),
            'month' => $month,
            'monthText' => trans('magazine.month.'.$month),
            'monthSubject' => $monthSubject,
            'professionals' => isset($professionals) ? $professionals : array(),
            'short_name' => $themeName
        ];
        return $data;
    }

    public static function getTypeNotify()
    {
        return [
            Notification::NOTIFY_TYPE => [
                Notification::TYPE_FOLLOW,
                Notification::TYPE_STOCK,
                Notification::TYPE_COMMENT,
                Notification::TYPE_MENTION,
                Notification::TYPE_ADD_MEMBER_TO_GROUP,
                Notification::TYPE_POST_IN_GROUP,
                Notification::TYPE_REPORT_POST,
                Notification::TYPE_FEEDBACK,
                Notification::TYPE_FOLLOWING_POST,
            ],
            Notification::REQUEST_TYPE => [
                Notification::TYPE_APPROVE_POST_IN_GROUP,
                Notification::TYPE_APPROVE_MEMBER_IN_GROUP
            ],
        ];
    }

    public static function sentMailNotify($type = 'report', $object)
    {
        $from = Config::get('mail.from');
        $to = [
            'address' => env('EMAIL_FOR_SEND_MAIL_NOTIFY'),
            'name' => 'Viblo',
        ];

        if (!is_null($object->user_id) && ($object->user_id != 0)) {
            $username = User::find($object->user_id)->username;
        } else {
            $username = trans('labels.not_registered_user');
        }

        if ($type == 'report') {
            $subject = trans('messages.notification.report_from', ['user' => $username]);
            $logType = MailService::EMAIL_LOG_TYPE_REPORT;
        } else {
            $subject = trans('messages.notification.feedback_from', ['user' => $username]);
            $logType = MailService::EMAIL_LOG_TYPE_FEEDBACK;
        }

        $layout = 'emails.admin._notify';
        $data = [
            'name' => 'Viblo',
            'object' => $object,
            'type' => $type,
        ];

        MailService::send($from, $to, $subject, $data, $layout, $logType);
    }

    public static function sentSystemMailNotify()
    {
        $from = Config::get('mail.from');
        $layout = 'emails.admin._notify';
        $to = [
            'address' => 'info@viblo.asia',
            'name' => 'Viblo',
        ];
        $subject = trans('messages.notification.feedback_from.system_backup_mail_subject');
        MailService::send($from, $to, $subject, null, $layout, null);
    }
}
