<?php
namespace App\Services;

use App\Data\Blog\MonthlyThemeSubject;
use App\Data\Blog\MonthlyProfessional;
use Cache;

class MonthlyThemeService
{
    CONST PER_PAGE = 8;
    CONST PROFESSIONAL_RANDOM_NUMBER = 5;

    public static function getMonthThemes($publishYear = null, $publishMonth = null)
    {
        $currentMonth = isset($publishMonth) ? (int)$publishMonth : (int)date('m');
        $currentYear = isset($publishYear) ? (int)$publishYear : (int)date('Y');

        $monthSubject = MonthlyThemeSubject::where('publish_year', $currentYear)
            ->where('publish_month', $currentMonth)
            ->first();

        return $monthSubject;
    }

    public static function getBackNumberThemes($getAllFlag = false, $publishYear = null)
    {
        $currentYear = isset($publishYear) ? (int)$publishYear : (int)date('Y');

        $backNumberQuery = MonthlyThemeSubject::whereRaw('DATE_FORMAT(CURDATE(), "%Y-%m") >= CONCAT(publish_year, "-", LPAD(publish_month, 2, 0))')
            ->orderBy('publish_year', 'desc')
            ->orderBy('publish_month', 'asc');

        if (!$getAllFlag) {
            $backNumberQuery->where('publish_year', $currentYear);
        }

        return $backNumberQuery->get();
    }

    public static function getCurrentMonthThemeSubject()
    {
        if (Cache::has('month_theme_subject')) {
            return Cache::get('month_theme_subject');
        }

        $monthSubject = MonthlyThemeSubject::where('publish_year', (int)date('Y'))
            ->where('publish_month', (int)date('m'))
            ->first();
        Cache::put('month_theme_subject', $monthSubject, 100);
        return $monthSubject;
    }

    public static function getProfessionalByThemeSubjectId($themeSubjectId, $pageCount = 0)
    {
        $offset = $pageCount * self::PER_PAGE;
        $professionals = MonthlyProfessional::with('post.theme', 'post.user.profile', 'post.user.avatar')
            ->where('monthly_theme_subject_id', $themeSubjectId)
            ->orderBy('order', 'asc')
            ->take(self::PER_PAGE)
            ->skip($offset)
            ->get();

        return $professionals;
    }

    public static function getProfessionalMagazineByThemeSubjectId($themeSubjectId)
    {
        $professionals = MonthlyProfessional::with('post.theme', 'post.user.profile', 'post.user.avatar')
            ->where('monthly_theme_subject_id', $themeSubjectId)
            ->orderByRaw('RAND()')
            ->take(3)
            ->get();

        return $professionals;
    }

    public static function getRandomProfessionalByThemeSubjectId($themeSubjectId)
    {
        $randomProfessionalArr = [];
        $professionals = MonthlyProfessional::with('post.theme.themeLanguages', 'post.user.profile', 'post.user.avatar')
            ->where('monthly_theme_subject_id', $themeSubjectId)
            ->get();

        if (count($professionals) > 0) {
            if (count($professionals) == 1) {
                $randomProfessionalArr[] = $professionals[0];
            } else {
                $randomKeys = array_rand($professionals->toArray(), self::PROFESSIONAL_RANDOM_NUMBER < count($professionals) ? self::PROFESSIONAL_RANDOM_NUMBER : count($professionals));

                foreach ($randomKeys as $key => $value) {
                    $randomProfessionalArr[] = $professionals[$value];
                }
            }
        }

        return $randomProfessionalArr;
    }
}