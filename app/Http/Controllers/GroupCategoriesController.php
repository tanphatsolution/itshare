<?php namespace App\Http\Controllers;

use App\Data\Blog\Category;
use App\Data\Blog\Group;
use App\Services\GroupPostService;
use Response;
use Request;
use Input;
use View;

class GroupCategoriesController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return $id;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        return $id;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        return $id;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        return $id;
    }

    public function getGroupCategoryPosts($groupEncryptedId, $categoryShortName)
    {
        $group = Group::findByEncryptedId($groupEncryptedId);
        if (empty($group)) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        $category = Category::findByShortName($categoryShortName);
        if (is_null($category)) {
            $this->viewData['title'] = trans('categories.error.404');
            return Response::view('errors.404', $this->viewData, 404);
        }

        if (Request::ajax()) {
            $pageCount = Input::get('pageCount');
            $posts = GroupPostService::getCategoryPosts($group, $category, $pageCount);

            $seeMore = trans('labels.load_more');
            $hideSeeMore = $posts->count() < GroupPostService::PER_PAGE;

            $html = '';
            foreach ($posts as $post) {
                $html .= View::make('post._a_post', ['post' => $post, 'inList' => true, 'lang' => $this->lang])->render();
            }
            return Response::json(['views' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            $posts = GroupPostService::getCategoryPosts($group, $category);

            $hideSeeMore = $posts->count() < GroupPostService::PER_PAGE;

            // Prepare data for Group Layout (inherit from BaseController)
            $this->prepareParamsForGroupLayout($group);

            return View::make(
                'groups.category_posts',
                array_merge(
                    $this->viewData,
                    compact(
                        'category',
                        'posts',
                        'group',
                        'hideSeeMore'
                    )
                )
            );
        }
    }
}
