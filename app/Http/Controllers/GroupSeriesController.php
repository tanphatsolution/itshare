<?php
namespace App\Http\Controllers;

use App\Data\Blog\Group;
use App\Data\Blog\GroupSeries;
use App\Data\Blog\GroupUser;
use App\Data\Blog\Role;
use App\Data\System\User;
use App\Events\ViewGroupSeriesHandler;
use App\Services\GroupSeriesService;
use Route;
use View;
use Input;
use Redirect;
use Response;
use Auth;
use Request;
use Event;

class GroupSeriesController extends BaseController
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
        $urlParams = Route::current()->parameters();

        $this->viewData['encryptedGroupId'] = $urlParams['encryptedGroupId'];
        $this->viewData['title'] = trans('titles.series_create');

        $group = Group::where('encrypted_id', $urlParams['encryptedGroupId'])->first();
        $this->prepareParamsForGroupLayout($group);
        $this->viewData['defaultPostLang'] = $this->currentUser->setting->default_post_language;

        return View::make('groupseries.create', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $inputs = Input::all();
        $urlParams = Route::current()->parameters();
        $inputs['encryptedGroupId'] = $urlParams['encryptedGroupId'];

        if (empty($inputs['name'])) {
            $error = ['empty_name' => trans('messages.group.url_not_empty')];
            return Redirect::action('GroupSeriesController@create', ['encryptedGroupId' => $inputs['encryptedGroupId']])->withInput($inputs)->withErrors($error);
        }
        if (isset($inputs['name']) && isset($inputs['description']) && isset($inputs['url'])) {
            $groupSeries = GroupSeriesService::save($inputs);
            if ($groupSeries) {
                return Redirect::action('GroupSeriesController@show', ['encryptedGroupId' => $inputs['encryptedGroupId'], 'groupSeriesId' => $groupSeries->id])->with('success', trans('messages.series.save_success'));
            } else {
                $error = ['empty_name' => 'Can\'t save series.'];
                return Redirect::action('GroupSeriesController@create', ['encryptedGroupId' => $inputs['encryptedGroupId']])->withInput($inputs)->withErrors($error);
            }
        }
        $error = ['empty_url' => trans('messages.series.empty_list')];
        return Redirect::action('GroupSeriesController@create', ['encryptedGroupId' => $inputs['encryptedGroupId']])->withInput($inputs)->withErrors($error);
    }

    /**
     * Display the specified resource.
     * @param $encryptedGroupId
     * @param $groupSeriesId
     * @return Response
     */
    public function show($encryptedGroupId, $groupSeriesId)
    {
        $groupSeries = GroupSeries::find($groupSeriesId);
        if (!$groupSeries) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $group = Group::where('encrypted_id', $encryptedGroupId)
            ->orWhere('id', $encryptedGroupId)
            ->first();
        $this->prepareParamsForGroupLayout($group);
        $this->viewData['groupSeries'] = $groupSeries;
        $this->viewData['group'] = $group;
        $this->viewData['title'] = trans('titles.group_series', ['title' => htmlspecialchars($groupSeries->name)]);
        $editable = false;
        $groupUser = null;
        if (Auth::check()) {
            $groupUser = GroupUser::where('group_id', $group->id)
                ->where('user_id', Auth::user()->id)
                ->where('status', GroupUser::STATUS_MEMBER)
                ->first();
        }
        if ($groupSeries->canEditBy($groupUser)) {
            $editable = true;
        }
        $hideSeriesContent = false;
        $groupSetting = $group->groupSetting()->first();
        if ($groupSetting && $groupSetting->isSecret() && !$group->haveMemberIs($this->currentUser)) {
            return Response::view('errors.404', $this->viewData, 404);
        } elseif ($groupSetting && $groupSetting->isNonePublic()) {
            $hideSeriesContent = !$group->haveMemberIs($this->currentUser) ? true : false;
        }

        $this->viewData['hideSeriesContent'] = $hideSeriesContent;
        $this->viewData['editable'] = $editable;
        // Prepare data for Group Layout (inherit from BaseController)
        $this->prepareParamsForGroupLayout($group);

        return View::make('groupseries.show', $this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $urlParams = Route::current()->parameters();

        $groupSeries = GroupSeries::find($urlParams['groupSeriesId']);
        if (!$groupSeries) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        $group = Group::find($groupSeries->group_id);
        if (!$group) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        $groupUser = null;
        if ($this->currentUser) {
            $groupUser = GroupUser::where('group_id', $group->id)
                ->where('user_id', $this->currentUser->id)
                ->where('status', GroupUser::STATUS_MEMBER)
                ->first();
        }

        $isAdmin = false;
        $checkUser = User::leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->select('users.name', 'user_roles.role_id')
            ->where('user_roles.role_id', Role::ADMIN)
            ->where('users.id', $this->currentUser->id)->count();
        if ($checkUser > 0) {
            $isAdmin = true;
        }

        if ($groupSeries->canEditBy($groupUser) || $isAdmin) {
            $this->viewData['encryptedGroupId'] = $urlParams['encryptedGroupId'];
            $this->viewData['groupSeries'] = $groupSeries;

            $group = Group::where('encrypted_id', $urlParams['encryptedGroupId'])->first();
            $this->prepareParamsForGroupLayout($group);
            $this->viewData['title'] = trans('titles.series_edit');

            return View::make('groupseries.edit', $this->viewData);

        } else {
            return Response::view('errors.404', $this->viewData, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        $inputs = Input::all();
        $urlParams = Route::current()->parameters();
        $inputs['encryptedGroupId'] = $urlParams['encryptedGroupId'];
        $inputs['groupSeriesId'] = $urlParams['groupSeriesId'];

        if (GroupSeriesService::update($inputs)) {
            return Redirect::action('GroupSeriesController@show', [
                'encryptedGroupId' => $inputs['encryptedGroupId'],
                'groupSeriesId' => $inputs['groupSeriesId'],
            ])->with('success', trans('messages.series.save_success'));
        } else {
            $errors = array();
            $errors['not_save'] = trans('messages.group.not_save');
            return Redirect::back()
                ->with('errors', $errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $id;
    }

    public function addItem()
    {
        $input = Input::all();
        $serverName = \URL::to('');
        switch ($input['type']) {
            case GroupSeries::URL_TYPE_LINK:
                if (!GroupSeriesService::checkFormatURL($input['url'])) {
                    $result = json_encode(false);
                } elseif (GroupSeriesService::isImageUrl($input['url'])) {
                    $result = GroupSeriesService::genLinkImagePreview($input['url']);
                } elseif (strpos($input['url'], 'youtube.com/watch?v=') > 0) {
                    $result = GroupSeriesService::genLinkYoutubePreview($input['url']);
                } else {
                    if (strpos($input['url'], $serverName) !== false && strpos($input['url'], '/posts/') > 0) {
                        $encryptedGroupId = Input::get('encryptedGroupId');
                        $result = GroupSeriesService::genVibloLinkPreview($input['url'], $encryptedGroupId);
                    } else {
                        $result = GroupSeriesService::genLinkPreview($input['url']);
                    }
                }
                break;
            case GroupSeries::URL_TYPE_IMAGE:
                if (GroupSeriesService::isImageUrl($input['url'])) {
                    $result = GroupSeriesService::genLinkImagePreview($input['url']);
                } else {
                    $result = json_encode(false);
                }
                break;
            case GroupSeries::URL_TYPE_YOUTUBE:
                if (strpos($input['url'], 'youtube.com/watch?v=') > 0) {
                    $result = GroupSeriesService::genLinkYoutubePreview($input['url']);
                } else {
                    $result = json_encode(false);
                }
                break;
            case GroupSeries::URL_TYPE_QUOTE:
                $result = GroupSeriesService::genLinkQuotePreview($input['url'], $input['quote']);
                break;
            case GroupSeries::URL_TYPE_TEXT:
                $result = GroupSeriesService::genLinkTextPreview($input['text']);
                break;
            case GroupSeries::URL_TYPE_HEADING:
                $result = GroupSeriesService::genLinkHeadingPreview($input['text']);
                break;

            default:
                $result = json_encode(false);
                break;
        }

        return $result;
    }

    public function checkInput()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $notice = GroupSeriesService::validation($input);
            $error = true;
            if (empty($notice)) {
                $error = false;
            }
            return Response::json([
                'error' => $error,
                'notice' => $notice,
            ]);
        }
    }

    public function checkFormatURL()
    {
        if (Request::ajax()) {
            $url = Input::get('url');
            return json_encode(GroupSeriesService::checkFormatURL($url));
        }
    }

    public function getElementBtnByLinkType()
    {
        if (Request::ajax()) {

            $linkType = Input::get('type');
            $data = [
                'type' => $linkType,
            ];

            switch ($linkType) {
                case GroupSeries::URL_TYPE_IMAGE:
                    $html = View::make('groupseries.elements._an_input_image', ['data' => $data])->render();
                    break;
                case GroupSeries::URL_TYPE_LINK:
                    $html = View::make('groupseries.elements._an_input_link', ['data' => $data])->render();
                    break;
                case GroupSeries::URL_TYPE_YOUTUBE:
                    $html = View::make('groupseries.elements._an_input_link', ['data' => $data])->render();
                    break;
                case GroupSeries::URL_TYPE_QUOTE:
                    $html = View::make('groupseries.elements._an_input_quote', ['data' => $data])->render();
                    break;
                case GroupSeries::URL_TYPE_TEXT:
                    $html = View::make('groupseries.elements._an_input_text', ['data' => $data])->render();
                    break;
                case GroupSeries::URL_TYPE_HEADING:
                    $html = View::make('groupseries.elements._an_input_heading', ['data' => $data])->render();
                    break;

                default:
                    $data = [
                        'type' => GroupSeries::URL_TYPE_LINK,
                    ];
                    $html = View::make('groupseries.elements._an_input_link', ['data' => $data])->render();
                    break;
            }

            return Response::json(['html' => $html], 200);
        }
    }

    public function counter()
    {
        $series = GroupSeries::find((int) Input::get('series_id'));
        if ($series) {
            Event::fire(ViewGroupSeriesHandler::EVENT_NAME, $series);
            return Response::json([$series->viewsCount]);
        }
        return Response::json([0]);
    }

}
