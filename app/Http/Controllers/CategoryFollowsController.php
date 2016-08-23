<?php namespace App\Http\Controllers;

use App\Data\Blog\Category;
use Request;
use View;
use Response;
use Redirect;

class CategoryFollowsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->viewData['title'] = 'Category Follow';
    }

    public function store()
    {
        if (Request::ajax()) {
            $categoryId = (int) Request::get('category_id');
            $category = Category::find($categoryId);
            if ($category) {
                $category->addFollower($this->currentUser);
                $this->viewData['category'] = $category;
                $html = View::make('categories._unfollow', $this->viewData)->render();
                return Response::json([
                    'error' => false,
                    'html' => $html,
                    'category_id' => $category->id,
                    'follow_count' => $category->followersCount,
                ], 200);
            }
            return Response::json(['error' => true], 200);
        }
        return Redirect::action('CategoriesController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            $category = Category::find($id);
            if ($category) {
                $category->removeFollower($this->currentUser);
                $this->viewData['category'] = $category;
                $html = View::make('categories._follow', $this->viewData)->render();
                return Response::json([
                    'error' => false,
                    'html' => $html,
                    'category_id' => $category->id,
                    'follow_count' => $category->followersCount,
                ], 200);
            }
            return Response::json(['error' => true], 200);
        }
        return Redirect::action('CategoriesController@index');
    }
}
