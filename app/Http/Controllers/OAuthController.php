<?php namespace App\Http\Controllers;

use App\Data\System\User;
use Illuminate\Http\Request;
use Redirect;
use Response;
use View;
use Auth;
use Validator;
use DB;

use LucaDegasperi\OAuth2Server\Authorizer;

use App\Data\Blog\OauthClient;
use App\Services\UserService;
use App\Services\AppService;

class OAuthController extends BaseController
{
    protected $authorizer;

    public function __construct(Authorizer $authorizer)
    {
        parent::__construct();

        $this->authorizer = $authorizer;

        $this->middleware('auth', ['only' => ['postAuthorize']]);
        $this->middleware('csrf', ['only' => ['postAuthorize', 'postLogin']]);
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => ['getApps', 'createApp', 'editApp', 'updateApp', 'storeApp', 'destroyApp'],
        ]);
    }

    public function postAccessToken()
    {
        return Response::json($this->authorizer->issueAccessToken());
    }

    public function getAuthorize(Request $request)
    {
        if (!Auth::check()) {
            $input = $request->all();
            return View::make('oauth.authorization_login_form', $input);
        }

        $authParams = $this->authorizer->getAuthCodeRequestParams();
        $formParams = array_except($authParams, 'client');
        $formParams['client_id'] = $authParams['client']->getId();

        $dataParams = [
            'params' => $formParams,
            'client' => $authParams['client'],
            'currentUser' => $this->currentUser,
        ];

        return View::make('oauth.authorization-form', $dataParams);
    }

    public function postAuthorize(Request $request)
    {
        $params = array();
        $params['user_id'] = $this->currentUser->id;
        $redirectUri = '';

        if (!is_null($request->get('approve'))) {
            $redirectUri = $this->authorizer->issueAuthCode('user', $params['user_id'], $params);
        }

        if (!is_null($request->get('deny'))) {
            $redirectUri = $this->authorizer->authCodeRequestDeniedRedirectUri();
        }

        return Redirect::to($redirectUri);
    }

    public function postLogin(Request $request)
    {
        $input = $request->all();

        $field = filter_var($input['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $input[$field] = $input['username'];
        $validator = Validator::make($input, User::authRules($field));
        $loginResult = UserService::login($input, $field, $validator);

        if (!$loginResult['success']) {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($loginResult['messages']);
        }

        return Redirect::action('OAuthController@getAuthorize', [
            'redirect_uri' => $input['redirect_uri'],
            'response_type' => $input['response_type'],
            'client_id' => $input['client_id'],
        ]);
    }

    public function getApps()
    {
        $oauthClients = OauthClient::orderBy('created_at', 'desc')
            ->paginate(OauthClient::DISPLAY_CLIENT_PER_PAGE);

        $this->viewData['oauthClients'] = $oauthClients;
        $this->viewData['title'] = trans('titles.app_manager');

        return View::make('oauth.index', $this->viewData);
    }

    public function createApp()
    {
        $this->viewData['title'] = trans('titles.app_create');
        return View::make('oauth.create', $this->viewData);
    }

    public function editApp($clientId)
    {
        $oauthClient = OauthClient::find($clientId);
        if (!$oauthClient) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        $this->viewData['oauthClient'] = $oauthClient;
        $this->viewData['title'] = trans('titles.app_edit');

        return View::make('oauth.edit', $this->viewData);
    }

    public function updateApp(Request $request)
    {
        $input = $request->all();
        $oauthClient = OauthClient::find((int)$input['client_id']);
        $errors = array();
        $errors['not_update'] = trans('messages.client.not_update');

        if (!$oauthClient) {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($errors);
        }

        $errors = AppService::validate($input);
        if (empty($errors)) {
            DB::beginTransaction();
            try {
                $updateClient = $oauthClient->update([
                    'name' => $input['client_name'],
                ]);
                if ($updateClient) {
                    $oauthClient->oauthClientEndpoint()
                        ->update([
                            'redirect_uri' => $input['redirect_uri'],
                        ]);
                }
                DB::commit();
                return Redirect::action('OAuthController@getApps');

            } catch (\Exception $e) {
                DB::rollback();
                return Redirect::back()
                    ->withInput($input)
                    ->withErrors($errors);
            }
        } else {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($errors);
        }
    }

    public function storeApp(Request $request)
    {
        $input = $request->all();
        $errors = AppService::validate($input);
        if (empty($errors)) {
            DB::beginTransaction();
            try {
                $newClient = AppService::create($input);
                DB::commit();
                return Redirect::action('OAuthController@editApp', [$newClient->id])
                    ->with('success', trans('messages.client.saved'));

            } catch (\Exception $e) {
                $errors['not_save'] = trans('messages.client.not_save');
                DB::rollback();
                return Redirect::back()
                    ->withInput($input)
                    ->withErrors($errors);
            }
        } else {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($errors);
        }
    }

    public function destroyApp(Request $request)
    {
        $input = $request->all();
        $oauthClient = OauthClient::find((int)$input['clientId']);
        $error = false;
        $message = '';

        if (!$oauthClient) {
            $error = true;
            $message = trans('message.client.not_delete');
        } else {
            DB::beginTransaction();
            try {
                $oauthClient->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $error = true;
                $message = trans('message.client.not_delete');
            }
        }

        return Response::json([
            'error' => $error,
            'message' => $message,
        ], 200);
    }

}
