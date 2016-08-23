<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Response;

use App\Data\Blog\CategoryFilter;
use App\Services\CategoryService;

class CategoryFiltersController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('authority:add-edit-delete,privilege');
        $this->viewData['title'] = 'Category Filter';
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $rules = CategoryFilter::getRules();
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->all()[0];
            $error = true;
        } else {
            list($message, $error) = CategoryService::createCategoryFilter($input);
        }
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        $code = $error ? 400 : 200;
        return Response::json($response, $code);
    }

    public function destroy($categoryId)
    {
        list($message, $error) = CategoryService::deleteCategoryFilter($categoryId);
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        $code = $error ? 400 : 200;
        return Response::json($response, $code);
    }
}
