<?php namespace App\Http\Controllers\Faq;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Response;

use App\Data\Blog\Category;
use App\Data\Blog\Setting;
use App\Data\Faq\Question;

use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\GroupService;

class QuestionsController extends BaseController
{
    /**
     * Instantiate a new QuestionsController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewData['title'] = trans('titles.posts');
    }

    public function index()
    {
        return view('faq.home.index', $this->viewData);
    }

    public function create()
    {

        if (!PostService::checkDraftAvailable()) {
            return redirect()->route('getDrafts', ['encryptedId' => ''])
                ->withErrors([trans('messages.post.max_drafts_error')]);
        }
        $this->viewData['title'] = trans('titles.create_post');
        $this->viewData['fullSize'] = true;
        $this->viewData['categories'] = CategoryService::getAllCategoriesName();
        $topCategories = Category::getTopRecentCategories($this->currentUser);
        $this->viewData['topCategories'] = $topCategories;
        $this->viewData['editorThemeList'] = Setting::getThemeSettingFields();
        $this->viewData['themes'] = PostService::getThemesOption(null);
        $this->viewData['fixedHeaderOnScroll'] = false;
        $this->viewData['languageCode'] = $this->currentUser->setting()->first()->default_post_language;
        $this->viewData['groups'] = GroupService::getGroupsCanPostOf($this->currentUser);
        return view('faq.questions.create', $this->viewData);
    }

    public function store(Request $request)
    {
        //do save in to database
        $input = $request->except('_token');
        $input['user_id'] = $this->currentUser->id;
        $input['title'] = !empty($input['title']) ? $input['title'] : 'Untitle';
        $input['category'] = !empty($input['category']) ? $input['category'] : 'Uncategory';
        $input['slug'] = uniquePostSlug($input['title'], $input['language_code']);
        unset($input['share_by_url']);
        $messages = Question::insertDB($input);
        if ($request->ajax()) {
            return response()->json($messages);
        }
        if ($messages['saved']) {
            
            return redirect()->route('faq.questions.show', ['id' => $messages['encrypted_id'], 'slug' => $input['slug']]);
        }

        return back()->withInput()->withErrors([trans('messages.action_failed')]);
    }

    public function edit()
    {
        
    }

    public function update()
    {
        
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        $viewData = $question->getViewData();
        if (!$question) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $this->viewData = array_merge($this->viewData, $viewData);
        return view('faq.questions.show', $this->viewData);
    }

    public function destroy()
    {
        
    }
}
