<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Validator;

use App\Data\Blog\Setting;
use App\Data\Blog\Contest;

use App\Services\ContestService;
use App\Services\PostService;
use App\Services\CategoryService;
use App\Services\UserService;

class ContestController extends BaseController
{
    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('auth');
        
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => [
                'create',
                'store',
                'show',
                'edit',
                'update',
                'destroy',
                'getArticles',
            ],
        ]);
        
        $this->viewData['title'] = trans('titles.contests_title');
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $this->viewData['title'] = trans('titles.contests_title');
        $contests = Contest::with('domains')
            ->where('title', 'LIKE', '%' . $request->get('q') . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(Contest::CONTEST_PER_PAGE);

        $this->viewData['q'] = $request->get('q');
        $this->viewData['contests'] = $contests;
        return View::make('contests.index', $this->viewData);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function create()
    {
        $noneHolder = [];
        $this->viewData['title'] = trans('titles.create_contest');
        $this->viewData['categories'] = CategoryService::getAllCategoriesName();
        $this->viewData['emails'] = array_merge($noneHolder, UserService::getAllEmailDomain());
        $this->viewData['editorThemeList'] = Setting::getThemeSettingFields();
        $monthlyThemeSubject = PostService::getThemesOption(null)['monthlyThemeSubjects']->toArray();
        $monthlyThemeSubject = array_merge($monthlyThemeSubject, [0 => trans('messages.contest.none')]);
        $this->viewData['themes'] = PostService::getThemesOption(null);
        $this->viewData['monthlyThemeSubject'] = $monthlyThemeSubject;
        return View::make('contests.create', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     * @property int $this->currentUser->id
     * @return string
     *
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = $this->currentUser->id;
        $validator = Validator::make($input, Contest::$createRules);
        if ($validator->fails()) {
            return redirect('/contests/create')
                ->withInput($input)
                ->withErrors($validator);
        }
        $contest = ContestService::create($input);
        ContestService::saveCategories($contest, $input['category']);
        ContestService::saveDomains($contest, $input['email']);

        return redirect('/contests')
            ->with('message', trans('messages.contest.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return string
     */
    public function show($id)
    {
        $errors = [];
        $this->viewData['title'] = trans('titles.contest_rankings');

        try {
            $contest = Contest::findOrFail($id);
            $categories = $contest->categories->lists('id');
            $domains = $contest->domains->lists('name');
            $validPosts = ContestService::getContestRankings($contest, $categories);

            $this->viewData['users'] = UserService::getUserByContest($contest, $domains, $categories)->get();
            $this->viewData['contest'] = $contest;
            $this->viewData['posts'] = $validPosts;
            return View::make('contests.ranking', $this->viewData);
        } catch (\Exception $e) {
            $errors['contest_error'] = trans('messages.category.no_contest');
            return redirect('/contests')->withErrors($errors);
        }
    }

    public function getArticles(Request $request, $contestId, $userId)
    {
        if ($request->ajax()) {
            $contest = Contest::findOrFail($contestId);
            $articles = PostService::getPostByUserContest($contest, $userId)->get();
            return \Response::json($articles);
        }
    }
}
