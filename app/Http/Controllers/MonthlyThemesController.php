<?php namespace App\Http\Controllers;

use App\Data\Blog\MonthlyTheme;
use App\Data\Blog\MonthlyThemeSubject;
use App\Services\PostService;
use View;
use Request;
use App\Services\MonthlyThemeService;
use Response;
use Input;

class MonthlyThemesController extends BaseController
{
    const TAB_TOP_CLIP = 'recent';
    const TAB_TOP_POST = 'top';
    const TAB_HELPFUL = 'helpful';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function backNumber()
    {
        $this->viewData['title'] = trans('titles.back_number');
        $this->viewData['backNumberSubject'] = MonthlyThemeService::getBackNumberThemes(true);

        return View::make('themes.back_number', $this->viewData);
    }

    public function categories($lang, $themeName, $subThemeName, $tab = '')
    {
        $category = MonthlyTheme::with('themeLanguages')
            ->leftJoin('monthly_theme_subjects', 'monthly_themes.monthly_theme_subject_id', '=', 'monthly_theme_subjects.id')
            ->where('monthly_themes.short_name', $subThemeName)
            ->where('monthly_theme_subjects.short_name', $themeName)
            ->select('monthly_themes.*', 'monthly_theme_subjects.short_name AS theme_subject_short_name')
            ->first();

        if (is_null($category)) {
            $this->viewData['title'] = trans('categories.error.404');
            return View::make('errors.404', $this->viewData);
        }
        $categoryId = $category->id;
        $themeSubject = MonthlyThemeSubject::find($category->monthly_theme_subject_id);
        $total = 0;

        switch ($tab) {
            case self::TAB_TOP_CLIP:
                if (Request::ajax()) {
                    $pageCount = Input::get('pageCount');
                    $posts = PostService::getPostByThemeCategoryStock($categoryId, $pageCount);
                } else {
                    $posts = PostService::getPostByThemeCategoryStock($categoryId);
                    $total = PostService::getPostByThemeCategoryStockCount($categoryId);
                }
                break;
            case self::TAB_TOP_POST:
                if (Request::ajax()) {
                    $pageCount = Input::get('pageCount');
                    $posts = PostService::getPostByThemeCategoryTop($categoryId, $pageCount);
                } else {
                    $posts = PostService::getPostByThemeCategoryTop($categoryId);
                    $total = PostService::getPostByThemeCategoryTopCount($categoryId);
                }
                break;
            case self::TAB_HELPFUL:
                if (Request::ajax()) {
                    $pageCount = Input::get('pageCount');
                    $posts = PostService::getPostByThemeCategoryHelpful($categoryId, $pageCount);
                } else {
                    $posts = PostService::getPostByThemeCategoryHelpful($categoryId);
                    $total = PostService::getPostByThemeCategoryHelpfulCount($categoryId);
                }
                break;
            default:
                if (Request::ajax()) {
                    $pageCount = Input::get('pageCount');
                    $posts = PostService::getPostByThemeCategory($categoryId, $pageCount);
                } else {
                    $posts = PostService::getPostByThemeCategory($categoryId);
                    $total = PostService::getPostByThemeCategoryCount($categoryId);
                }
                break;
        }

        $posts = PostService::filterByGroupSecretNotInUser($posts, $this->currentUser)->get();

        if (Request::ajax()) {
            $seeMore = trans('labels.load_more');
            $hideSeeMore = $posts->count() < PostService::PER_PAGE;
            $html = '';
            foreach ($posts as $post) {
                $html .= View::make('post._a_post', ['post' => $post, 'inList' => true, 'lang' => $lang])->render();
            }
            return Response::json(['views' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            $countAuthor = [];
            foreach ($posts as $post) {
                if (!in_array($post->user_id, $countAuthor)) {
                    $countAuthor[] = $post->user_id;
                }
            }

            $this->viewData['category'] = $category->toArray();
            $this->viewData['hideSeeMore'] = ($total <= PostService::PER_PAGE);
            $this->viewData['posts'] = $posts;
            $this->viewData['tab'] = $tab;
            $this->viewData['total'] = $total;
            $this->viewData['author'] = count($countAuthor);
            $this->viewData['themeName'] = $themeSubject->theme_name;
            $this->viewData['themeShortName'] = $themeSubject->short_name;
            $this->viewData['title'] = trans(
                'titles.small_theme',
                [
                    'smallTheme' => $category['theme_languages'][0]['name'],
                    'theme' => $themeSubject->theme_name,
                    'month' => trans('datetime.month.' . $themeSubject->publish_month),
                ]
            );
            return View::make('themes.categories', $this->viewData);
        }
    }

    public function professionals()
    {
        if (Request::ajax()) {
            $monthlyThemSubjectId = (int)Input::get('monthlyThemSubjectId', 0);
            $pageCount = Input::get('pageCount', 0);

            $monthSubject = MonthlyThemeSubject::find($monthlyThemSubjectId);
            $professionals = MonthlyThemeService::getProfessionalByThemeSubjectId($monthlyThemSubjectId, $pageCount);

            $seeMore = trans('labels.and_more');
            $hideSeeMore = $professionals->count() < MonthlyThemeService::PER_PAGE;

            $html = '';
            foreach ($professionals as $professional) {
                $html .= View::make('themes._a_professional', ['professional' => $professional, 'monthSubject' => $monthSubject])->render();
            }
            return Response::json(['views' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        }

        $this->viewData['title'] = trans('categories.error.404');
        return Response::view('errors.404', $this->viewData, 404);
    }
}
