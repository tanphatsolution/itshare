<?php namespace App\Services;

use App\Data\Blog\Image;
use Carbon\Carbon;
use DB;
use Cache;

class UserRankingService
{
    const LIMIT = 5;

    /**
     * get user ranking
     * @param null $tabs
     * @return mixed
     */
    public static function getUserRanking($tabs = null)
    {
        if (Cache::has($tabs . '_ranking')) {
            return Cache::get($tabs . '_ranking');
        }
        $users = DB::table('users')
            ->leftJoin('answers', 'answers.user_id', '=', 'users.id')
            ->select(array(
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.avatar_id',
                    DB::raw('SUM(answers.best_answer) as sum_best_answer'),
                    DB::raw('SUM(number_helpful) as sum_number_helpful'),
                )
            );
        if (is_null($tabs)) {
            $users = $users->whereBetween('answers.created_at', [Carbon::Now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
        if ($tabs == 'thisMonth') {
            $users = $users->whereBetween('answers.created_at', [Carbon::Now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }
        $users = $users->orderby('sum_best_answer', 'DESC')
            ->orderby('sum_number_helpful', 'DESC')
            ->groupBy('users.id')
            ->limit(self::LIMIT)
            ->get();

        Cache::put($tabs . '_ranking', $users, 60);
        return $users;
    }


    /**
     * get avatar user
     * @param $user
     * @return string
     */
    public static function getAvatar($user)
    {
        if (Cache::has('avatar_' . $user->username)) {
            return Cache::get('avatar_' . $user->username);
        }
        $img = Image::find($user->avatar_id);
        if ($img) {
            $base_url = ImageService::UPLOAD_DIR . sha1($user->username) . '/';
            $url = HelperService::getImageBy($base_url . $img->name);
        } else {
            $d = 'mm';
            $r = 'g';
            $url = 'https://www.gravatar.com/avatar/';
            $url .= '?s=' . 20 . '&d=' . $d . '&r=' . $r;
            return $url;
        }
        Cache::put('avatar_' . $user->username, $url, 60);
        return $url;
    }
}