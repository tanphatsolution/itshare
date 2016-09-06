<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Authority;
use App\Console\Commands\CreateSitemapCommand;
use Redirect;
use View;
use Auth;
use URL;
use File;
use Response;
use Jenssegers\Agent\Agent;
use Session;
use App\Data\Blog\MonthlyThemeSubject;
use App\Data\Blog\UserPostLanguage;
use App\Data\Blog\Post;

use App\Services\PostService;
use App\Services\MonthlyThemeService;
use App\Services\LanguageService;

class HomeController extends BaseController
{
    const LIMIT_CATEGORY = 28;

    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default home page
     * Route / or /home/index
     * @return response
     */
    public function getIndex()
    {
        return Redirect::action('PostsController@getIndex', ['wall' => PostService::WALL_ALL]);
    }

    public function getTop($lang = null, $themeName = null)
    {
        if ($themeName != null) {
            $monthSubjectByName = MonthlyThemeSubject::where('short_name', $themeName)->first();
        }

        $year = !empty($monthSubjectByName) ? $monthSubjectByName->publish_year : null;
        $month = !empty($monthSubjectByName) ? $monthSubjectByName->publish_month : null;

        $monthSubject = MonthlyThemeService::getMonthThemes($year, $month);
        if (empty($monthSubject)) {
            if (!empty($year) && !empty($month)) {
                return Redirect::action('HomeController@getTop');
            } else {
                return Redirect::action('HomeController@getIndex');
            }
        }

        $professionals = MonthlyThemeService::getProfessionalByThemeSubjectId($monthSubject->id);
        $randomProfessionals = ($monthSubject->display_slider) ? MonthlyThemeService::getRandomProfessionalByThemeSubjectId($monthSubject->id) : [];

        $this->viewData['monthSubject'] = $monthSubject;
        $this->viewData['backNumberSubject'] = MonthlyThemeService::getBackNumberThemes(true);
        $this->viewData['currentMonth'] = isset($month) ? $month : (int)date('m');
        $this->viewData['currentYear'] = isset($year) ? $year : (int)date('Y');
        $this->viewData['professionals'] = $professionals;
        $this->viewData['hideSeeMore'] = $professionals->count() < MonthlyThemeService::PER_PAGE;
        $this->viewData['randomProfessionals'] = $randomProfessionals;
        $this->viewData['lang'] = $lang;

        if (!is_null($themeName) && !is_null($monthSubject) && !empty($monthSubject)) {
            $this->viewData['title'] = trans('titles.theme_of_month', ['theme' => $monthSubject->theme_name, 'month' => trans('datetime.month.' . $monthSubject->publish_month)]);
        } else {
            $this->viewData['title'] = trans('titles.top_page', ['title' => trans('titles.top_page_title_after')]);
        }

        $this->viewData['topPageLanguage'] = Auth::check() ? null : LanguageService::getTopPageLang();

        return View::make('home.top', $this->viewData);
    }

    public function getTopPage(Request $request, $locale = '')
    {
        if (Auth::check()) {
            $seoLang = $request->get('lang', $locale);
            $posts = PostService::getPostFollowInWall(PostService::WALL_ALL, 0, PostService::SELECT_FILTER, $seoLang);

            foreach ($posts['class'] as $key => $class) {
                $posts['class'][$key] = $class . ($this->lang == ' ja' ? 'font-size-ja' : '');
            }

            $this->viewData['populars'] = PostService::getPopularPosts(Post::POPULAR_POST_LIMIT_IN_POST_INDEX);
            $this->viewData['filterBy'] = PostService::SELECT_FILTER;
            $this->viewData['topCategories'] = $this->categories;
            $this->viewData['title'] = trans('titles.top_page', ['title' => trans('titles.top_page_title_after')]);
            $this->viewData = array_merge($this->viewData, $posts);
            $this->viewData['seoLang'] = $seoLang;
            if (URL::previous() == action('UsersController@socialRegistration')) {
                $this->viewData['isSetDefaultLang'] = 0;
            }

            // return View::make('post.index', $this->viewData);

        } else {
            $this->viewData['categories'] = $this->categories;
            $this->viewData['title'] = trans('titles.top_page', ['title' => trans('titles.top_page_title_after')]);
            $this->viewData['agent'] = new Agent();
            // return view('home.top_sign_up', $this->viewData);
        }

        return view('_coursecode.home');
    }

    public function getSitemap()
    {
        if (Auth::check() && Authority::hasRole('admin')) {
            if (!File::exists(public_path() . '/sitemap.xml')) {
                $obj = new CreateSitemapCommand();
                $obj->fire();
            }
            return Redirect::to('sitemap.xml');
        } else {
            return Response::view('errors.404', $this->viewData, 404);
        }
    }

}
