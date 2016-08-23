<?php namespace App\Http\Controllers;

use App\Facades\Authority;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Redirect;
use Validator;
use Auth;
use View;

use App\Data\Blog\Category;
use App\Services\CategoryService;

class CategoriesController extends BaseController
{
    const TAB_TOP_CLIP = 'recent';
    const TAB_TOP_POST = 'top';
    const TAB_HELPFUL = 'helpful';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => ['create', 'store', 'getView', 'edit',
                'update', 'destroy', 'getImport', 'getExport',
                'postImport', 'postExport'
            ]
        ]);
    }

    public function index()
    {
        if (Request::ajax()) {
            $pageCount = Input::get('pageCount');
            $categories = Category::filterLanguageInCategory(CategoryService::CATEGORIES_PER_PAGE, $pageCount)->get();
            $html = View::make('categories._list_categories', ['categories' => $categories, 'currentUser' => $this->currentUser, 'lang' => $this->lang])->render();
            $seeMore = trans('labels.load_more');
            $hideSeeMore = false;
            if ($categories->count() < CategoryService::CATEGORIES_PER_PAGE) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            $categories = Category::filterLanguageInCategory(CategoryService::CATEGORIES_PER_PAGE)->get();
            $this->viewData['categories'] = $categories;
            $this->viewData['title'] = trans('titles.tag');
            return view('categories.index', $this->viewData);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->viewData['title'] = trans('messages.category.create_title');
        return View::make('categories.create', $this->viewData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $rules = Category::getRules();
        $validator = Validator::make($input, $rules);
        $inputCallBack = Input::except('image');
        if ($validator->fails()) {
            return Redirect::action('CategoriesController@create')
                ->withInput($inputCallBack)
                ->WithErrors($validator);
        }
        CategoryService::create($input);
        return Redirect::action('CategoriesController@create')
            ->with('message', trans('messages.category.create_success'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int $name
     * @return Response
     */

    public function show($name, $tab = '')
    {
        $category = Category::filterLanguageInCategory(CategoryService::CATEGORIES_PER_PAGE, null, $name)->first();

        if (is_null($category)) {
            $category = Category::findByShortName($name);
            if (empty($category)) {
                $this->viewData['title'] = trans('categories.error.404');
                return Response::view('errors.404', $this->viewData, 404);
            }
        }

        $posts = null;

        switch ($tab) {
            case self::TAB_TOP_CLIP:
                if (Request::ajax()) {
                    $posts = $category->getRecentStockedPosts(Input::get('pageCount'));
                } else {
                    $this->viewData['posts'] = $category->getRecentStockedPosts()['posts']
                        ->paginate(CategoryService::POSTS_PER_CATEGORY_DETAIL);
                }
                break;
            case self::TAB_TOP_POST:
                if (Request::ajax()) {
                    $posts = $category->getTopPostsInCategory(Input::get('pageCount'));
                } else {
                    $this->viewData['posts'] = $category->getTopPostsInCategory()['posts']
                        ->paginate(CategoryService::POSTS_PER_CATEGORY_DETAIL);
                }
                break;
            case self::TAB_HELPFUL:
                if (Request::ajax()) {
                    $posts = $category->getHelpfulPostsInCategory(Input::get('pageCount'));
                } else {
                    $this->viewData['posts'] = $category->getHelpfulPostsInCategory()['posts']
                        ->paginate(CategoryService::POSTS_PER_CATEGORY_DETAIL);
                }
                break;
            default:
                if (Request::ajax()) {
                    $pageCount = Input::get('pageCount');
                    $posts = $category->getRecentPublishedPosts($pageCount);
                } else {
                    $this->viewData['posts'] = $category->getRecentPublishedPosts()['posts']
                        ->paginate(CategoryService::POSTS_PER_CATEGORY_DETAIL);
                }
                break;
        }

        if (Request::ajax()) {
            $result = array();
            $result['hasMore'] = $posts['hasMore'];
            $result['views'] = '';
            foreach ($posts['posts']->get() as $post) {
                $result['views'] .= View::make('post._a_post', ['post' => $post, 'inList' => true, 'lang' => $this->lang])
                    ->render();
            }
            return $result;
        } else {
            $this->viewData['title'] = trans('titles.each_tag', ['name' => $category->name]);
            $this->viewData['category'] = $category;
            $this->viewData['url'] = URL::current();
            $this->viewData['tab'] = $tab;
            $this->viewData['hasMore'] = $category->categories_count > CategoryService::POSTS_PER_CATEGORY_DETAIL;
            return view('categories.show', $this->viewData);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return Redirect::action('CategoriesController@index');
        }

        $this->viewData['title'] = trans('messages.category.edit_title', ['name' => $category->name]);
        return View::make('categories.edit', $this->viewData)->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $input = Input::all();
        $rules = Category::getRules($id);
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Redirect::action('CategoriesController@edit', [$id])
                ->withInput([$input['name']])
                ->withErrors($validator);
        }
        $result = CategoryService::update($id, $input);
        return Redirect::action($result['action'], [$id])
            ->with($result['message_type'], $result['message']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        list($message, $error) = CategoryService::delete($id);
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        return Response::json($response, 200);
    }

    public function getView()
    {
        $categories = Category::with('filtered')->withTrashed()->paginate(Category::ADMIN_LIMIT_CATEGORIES_PER_PAGE);
        if (Input::has('name')) {
            $name = Input::get('name');
            $categories = Category::searchByName($name);
        }
        $this->viewData['title'] = trans('messages.category.manage_title');
        $this->viewData['categories'] = $categories;
        return view('categories.view', $this->viewData);
    }

    public function restore()
    {
        $id = Input::get('id');
        $response = array_combine(['message', 'error'], CategoryService::restore($id));
        return Response::json($response, 200);
    }

    public function getImport()
    {
        $this->viewData['title'] = trans('titles.category_import');
        return view('categories.import', $this->viewData);
    }

    public function postImport()
    {
        $errors = [];
        if (Auth::check() && Authority::hasRole('admin')) {
            $input = array();
            $input['file'] = Input::file('csv_file');

            if (!empty($input['file'])) {
                $input['extension'] = strtolower($input['file']->getClientOriginalExtension());
            }
            $uploadRules = Category::getUploadRules();
            $msgUpload = Category::getUploadMessages();
            $validator = Validator::make($input, $uploadRules, $msgUpload);
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator->messages());
            }

            $importCategories = CategoryService::importCategoriesFrom($input['file']);

            if ($importCategories['error']) {
                $errors['import_error'] = trans('messages.category.import_error');
                return Redirect::back()->withErrors($errors);
            }
            return Redirect::action('CategoriesController@getImport')
                ->with('message', trans('messages.category.import_success', ['total' => $importCategories['total']]));
        }

        return Response::view('errors.404', $this->viewData, 404);
    }

    public function getExport()
    {
        $this->viewData['title'] = trans('titles.category_export');
        return view('categories.export', $this->viewData);
    }

    public function postExport()
    {
        $input = Input::all();
        $type = isset($input['csv']) ? 'csv' : 'xls';
        $filename = 'categories_export_' . date('Y_m_d');
        return CategoryService::getExportCategories($filename, $type);
    }

}
