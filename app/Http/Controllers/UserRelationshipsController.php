<?php namespace App\Http\Controllers;

use App\Data\Blog\UserRelationships;
use App\Data\System\User;
use Request;
use View;
use Response;
use Redirect;

class UserRelationshipsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->viewData['title'] = 'Relationships';
    }

    public function store()
    {
        if (Request::ajax()) {
            $followedId = (int) Request::get('followed_id');
            $user = User::find($followedId);

            if ($user) {
                $numberFollowers = $user->followers()->count() + 1;
                $currentUser = $this->currentUser;
                $currentUser->follow($user);
                $this->viewData['user'] = $user;
                $this->viewData['numberFollowers'] = $numberFollowers;
                $this->viewData['relationship'] =  (new UserRelationships)->findId($currentUser->id, $followedId)->first();
                $html = View::make('relationships.unfollow', $this->viewData)->render();

                return Response::json([
                    'error' => false,
                    'html' => $html,
                    'numberFollowers' => $numberFollowers,
                ], 200);
            }
            return Response::json(['error' => true], 200);
        }
        return Redirect::action('HomeController@getTopPage');
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
            $relationships = UserRelationships::find($id);

            if ($relationships) {
                $user = $relationships->followed;
                $numberFollowers = $user->followers()->count() - 1;
                $this->currentUser->unfollow($user);
                $this->viewData['user'] = $user;
                $this->viewData['numberFollowers'] = $numberFollowers;
                $html = View::make('relationships.follow', $this->viewData)->render();
                return Response::json([
                    'error' => false,
                    'html' => $html,
                    'numberFollowers' => $numberFollowers,
                ], 200);
            }
            return Response::json(['error' => true], 200);
        }
        return Redirect::action('HomeController@getTopPage');
    }
}
