<?php namespace App\Http\Controllers;

use Validator;
use View;
use Redirect;
use Illuminate\http\Request;
use Response;
use Input;

use App\Data\Blog\CategoryRequest;
use App\Data\Blog\Category;

class CategoryRequestsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => ['getView', 'edit', 'update', 'destroy', 'accept'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->viewData['title'] = trans('messages.category.create_title');
        return View::make('categoryrequests.create', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules = CategoryRequest::getRules();
        $validator = Validator::make($input, $rules);
        $inputCallBack = $request->except('image');
        if ($validator->fails()) {
            return Redirect::action('CategoryRequestsController@create')
                ->withInput($inputCallBack)
                ->WithErrors($validator);
        }
        CategoryRequest::create([
            'name' => $input['name'],
            'short_name' => $input['short_name'],
        ]);
        return Redirect::action('CategoryRequestsController@create')
            ->with('message', trans('messages.category.request_success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $category = CategoryRequest::find($id);
        if (is_null($category)) {
            return Redirect::action('CategoryRequestsController@getView');
        }
        $this->viewData['title'] = trans('messages.category.edit_title', ['name' => $category->name]);
        return View::make('categoryrequests.edit', $this->viewData)->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     * @param  int $id
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $rules = CategoryRequest::getRules($id);
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Redirect::action('CategoryRequestsController@edit', [$id])
                ->withInput([$input['name']])
                ->withErrors($validator);
        }
        $result = [
            'action' => 'CategoryRequestsController@edit',
            'message' => null,
            'message_type' => 'success',
        ];
        $category = CategoryRequest::find($id);
        if ($input['name'] == $category->name && $input['short_name'] == $category->shortName) {
            $result['message'] = trans('messages.category.nothing_edited');
            $result['message_type'] = 'warning';
            $error = true;
        }
        if (!isset($error)) {
            $category->name = $input['name'];
            $category->shortName = $input['short_name'];
            /** @var \Illuminate\Database\Eloquent\Model $category */
            $category->save();
            $result['message'] = trans('messages.category.edit_success');
        }
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
        /** @var \Illuminate\Database\Eloquent\Model $category */
        $category = CategoryRequest::find($id);
        $message = trans('messages.error');
        if (!$category) {
            $message = trans('messages.category.not_exist', ['item' => $id]);
            list($message, $error) = [$message, true];
        } elseif ($category->delete()) {
            $message = trans('messages.category.has_deleted', ['item' => $category->name]);
            list($message, $error) = [$message, false];
        } else {
            list($message, $error) = [$message, true];
        }
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        return Response::json($response, 200);
    }

    public function getView(Request $request)
    {
        /** @var Illuminate\Database\Eloquent\Builder $categories */
        $categories = CategoryRequest::withTrashed();
        $categories = $categories->paginate(CategoryRequest::ADMIN_LIMIT_CATEGORIES_PER_PAGE);
        if ($request->has('name')) {
            $name = $request->get('name');
            $categories = CategoryRequest::searchByName($name);
        }
        $this->viewData['title'] = trans('messages.category.manage_title');
        $this->viewData['categories'] = $categories;
        return View::make('categoryrequests.view', $this->viewData);
    }

    public function restore(Request $request)
    {
        $id = (int)$request->get('id');
        /** @var Illuminate\Database\Eloquent\Builder $category */
        $category = CategoryRequest::withTrashed();
        $category = $category->find($id);
        $message = trans('messages.error');
        if (!$category) {
            $message = trans('messages.category.not_exist', ['item' => $id]);
            array_combine(['message', 'error'], [$message, true]);
        } elseif ($category->status == false) {
            $message = trans('messages.category.has_restored', ['item' => $id]);
            $category->status = null;
            $category->save();
            array_combine(['message', 'error'], [$message, false]);
        }
        if ($category->restore()) {
            $message = trans('messages.category.has_restored', ['item' => $category->name]);
            $response = array_combine(['message', 'error'], [$message, false]);
        } else {
            $response = array_combine(['message', 'error'], [$message, true]);
        }
        return Response::json($response, 200);
    }

    public function accept()
    {
        $id = (int)Input::get('id');
        /** @var \Illuminate\Database\Eloquent\Model $requestCategory */
        $requestCategory = CategoryRequest::find($id);
        $createPoint = Category::create([
            'name' => $requestCategory->name,
            'short_name' => $requestCategory->short_name,
        ]);
        $requestCategory->status = true;
        if ($createPoint) {
            CategoryRequest::where('name', '=', $requestCategory->name)->update(['status' => true]);
            $requestCategory->save();
            $message = trans('messages.category.accepted');
            $error = false;
        } else {
            $message = trans('messages.category.not_accept');
            $error = true;
        }
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        return Response::json($response, 200);
    }

    public function reject(Request $request)
    {
        $id = (int)$request->get('id');
        /** @var \Illuminate\Database\Eloquent\Model $requestCategory */
        $requestCategory = CategoryRequest::find($id);
        if ($requestCategory) {
            $requestCategory->status = false;
            if ($requestCategory->save()) {
                $message = trans('messages.category.rejected');
                $error = false;
            } else {
                $message = trans('messages.category.not_reject');
                $error = true;
            }
        } else {
            $message = trans('messages.category.not_reject');
            $error = true;
        }
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        return Response::json($response, 200);
    }
}
