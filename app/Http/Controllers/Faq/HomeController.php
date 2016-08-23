<?php namespace App\Http\Controllers\Faq;

use App\Data\Faq\Question;
use App\Http\Controllers\BaseController;
use App\Services\UserRankingService;
use Illuminate\Http\Request;
use View;

class HomeController extends BaseController
{
    const LIMIT = 2;

    /**
     * Home faq
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->viewData['items'] = $this->getQuestion();
        return view('faq.home.index', $this->viewData);
    }

    /**
     * get question in home
     * @param int $page
     * @return $this|\Illuminate\Database\Eloquent\Builder|static
     */
    public function getQuestion($page = 1)
    {
        $posts = Question::with(['user.avatar' => function ($q) {
            $q->select('images.id', 'images.name');
        }])->with(['categories' => function ($q) {
            $q->select('name', 'id');
        }]);

        $offset = ((int)$page - 1) * self::LIMIT;

        $posts = $posts->whereNotNull('slug')
            ->orderBy('published_at', 'desc')
            ->take(self::LIMIT)
            ->skip($offset)
            ->get();
        return $posts;
    }

    /**
     * Get question by ajax | load more click
     * @param Request $requests
     * @return \Illuminate\View\View
     */
    public function ajaxGetQuestion(Request $requests)
    {
        $data = $requests->all();
        $question = $this->getQuestion(array_get($data, 'pages', ''));
        if (!$question->isEmpty()) {
            return View::make('faq.home.list_question', ['items' => $question]);
        }
        return null;
    }

    /**
     * Get user ranking by ajax
     * @param Request $requests
     * @return \Illuminate\View\View
     */

    public function ajaxGetUserRanking(Request $requests)
    {
        $data = $requests->all();
        $users_ranking = UserRankingService::getUserRanking(array_get($data, 'tabs'));
        if ($users_ranking) {
            $this->viewData['users_ranking'] = $users_ranking;
            return View::make('faq.sidebarleft.list_user_ranking', $this->viewData);
        }
        return null;
    }
}
