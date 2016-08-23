<?php namespace App\Http\Controllers;

use App\Data\Blog\Activity;
use App\Events\LogKeywordSearchByUserHandler;
use App\Services\SearchService;
use Session;
use View;
use Input;
use Event;
use Redirect;
use Response;

class SearchsController extends BaseController
{

    /**
     * Instantiate a new SearchsController instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default search page
     * Route /search/{keyword}
     * @return response
     */
    public function getIndex($keyword = '')
    {
        $this->viewData['keyword'] = $keyword;
        if (empty($keyword)) {
            Session::set('message', trans('messages.search.invalid_keyword'));
            return View::make('search.index', $this->viewData);
        }
        $this->viewData['title'] = trans('messages.search.index', ['keyword' => $keyword]);
        $type = Session::get('search_type', SearchService::getDefaultType());
        Session::forget('message', trans('messages.search.invalid_keyword'));
        $this->viewData['results'] = SearchService::fullSearch($keyword, $type);
        $this->viewData['template'] = SearchService::getTemplateByType($type);
        $this->viewData['screen'] = Activity::SCREEN_SEARCH_RESULT;
        return View::make('search.index', $this->viewData);
    }

    /**
     * Default search page
     * Route /search
     * @return response
     */
    public function postIndex()
    {
        $keyword = Input::get('keyword');
        $type = Input::get('type');

        Event::fire(LogKeywordSearchByUserHandler::EVENT_NAME, $keyword);

        if (empty($type)) {
            Session::set('search_type', SearchService::TYPE_FULL_SEARCH_POST);
        } else {
            Session::set('search_type', $type);
        }
        if (empty($keyword)) {
            return Redirect::action('SearchsController@getIndex')
                ->with('message', trans('messages.search.invalid_keyword'));
        }
        return Redirect::action('SearchsController@getIndex', $keyword);
    }

    /**
     * Quick search
     * Route /quick-search
     * @return response
     */
    public function getQuickSearch()
    {
        $callback = Input::get('callback');
        $keyword = Input::get('keyword');
        $result = SearchService::quickSearch($keyword);
        return Response::json($result, 200)->setCallback($callback);
    }
}
