<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data\Blog\Social;
use App\Services\SocialService;
use App\Services\RedisService;
use App\Services\UserService;
use App\Data\System\User;
use Response;
use Redirect;
use View;
use Validator;
use Auth;
use Input;

class SocialsController extends BaseController
{
    /**
     * Instantiate a new SocialsController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewData['title'] = 'Social';
        $this->middleware('auth', [
            'only' => [
                'getIndex',
                'getRevoke',
                'getAuthorize'
            ]
        ]);
    }

    public function getIndex()
    {
        $this->viewData['title'] = trans('titles.login_setting');
        return View::make('social.index', $this->viewData);
    }

    public function getRevoke($type)
    {
        $this->currentUser->socials()->where('type', $type)->delete();
        return Redirect::action('ProfilesController@getUpdate');
    }

    public function getAuthorize(Request $request, $type)
    {
        $input = $request->all();
        if (isset($input['code'])) {
            SocialService::connectSocial($type, $input['code'],
                action('SocialsController@getAuthorize', ['type' => $type]));
            list($uid, $email, $name, $link, $avatarUrl) = SocialService::getSocialUser($type);
            if ($uid) {
                if (Social::where('type', $type)->where('uid', $uid)->count() > 0) {
                    return Redirect::action('ProfilesController@getUpdate')
                        ->with('message_error', trans('socials.already_associated'));
                }
                Social::create([
                    'email' => $email,
                    'name' => $name,
                    'uid' => $uid,
                    'link' => $link,
                    'type' => $type,
                    'avatar_url' => $avatarUrl,
                    'user_id' => $this->currentUser->id
                ]);
                return Redirect::action('ProfilesController@getUpdate')
                    ->with('message', trans('socials.connect_successfully'));
            }
        }

        switch ($type) {
            case Social::GOOGLE:
                if (isset($input['error']) && !empty($input['error'])) {
                    return Redirect::action('ProfilesController@getUpdate');
                }
                break;

            default:
                break;
        }

        return Redirect::to(SocialService::getAuthorizedUrl($type,
            action('SocialsController@getAuthorize', ['type' => $type])));
    }

    public function getConnect($type)
    {
        $this->viewData['title'] = 'Connect';
        $this->viewData['type'] = $type;

        list($uid, $email, $name, $link) = SocialService::getSocialUser($type);

        $this->viewData['socialName'] = $name;
        $this->viewData['socialEmail'] = $email;
        $this->viewData['socialLink'] = $link;
        $this->viewData['uid'] = $uid;
        return View::make('social.connect', $this->viewData);
    }

    public function postConnect(Request $request)
    {
        $input = $request->all();
        $type = $input['type'];

        $validator = Validator::make($input, User::$createRules, User::$messagesCreate);

        if ($validator->fails()) {
            return Redirect::action('SocialsController@getConnect', ['type' => $type])
                ->withInput($input)
                ->withErrors($validator);
        }

        list($uid, $email, $name, $link, $avatarUrl) = SocialService::getSocialUser($type);

        $sendEmail = (!empty($email) && $email == $input['email']) ? false : true;

        $input['social_avatar_url'] = $avatarUrl;

        if ($userId = UserService::signup($input, false, $sendEmail)) {
            Social::create([
                'email' => $email,
                'uid' => $uid,
                'name' => $name,
                'link' => $link,
                'type' => $type,
                'avatar_url' => $avatarUrl,
                'user_id' => $userId
            ]);
            if (!$sendEmail) {
                Auth::loginUsingId($userId);
                RedisService::registerUser(Auth::user()->id);
                return Redirect::action('UsersController@socialRegistration');
            }
        }

        return Redirect::action('UsersController@getLogin')->with('message', trans('messages.user.signup_success'));
    }

    public function getConfirm($type)
    {
        $this->viewData['title'] = 'Confirm';
        $this->viewData['type'] = $type;

        list($uid, $email, $name, $link, $avatarUrl) = SocialService::getSocialUser($type);

        $user = User::where('email', $email)->whereNull('active_token')->first();
        if ($user && !UserService::isBanedUser($user)) {
            $this->viewData['user'] = $user;
            $this->viewData['socialName'] = $name;
            $this->viewData['socialLink'] = $link;
            $this->viewData['uid'] = $uid;
            $this->viewData['avatarUrl'] = $avatarUrl;
            return View::make('social.confirm', $this->viewData);
        }

        return Redirect::action('UsersController@getLogin');
    }

    public function postConfirm()
    {
        $input = Input::all();
        $type = $input['type'];

        $validator = Validator::make($input, User::authRules('email'));

        if ($validator->fails()) {
            return Redirect::action('SocialsController@getConfirm', ['type' => $type])
                ->withInput($input)
                ->withErrors($validator);
        }

        if (!User::validate($input, 'email')) {
            return Redirect::action('SocialsController@getConfirm', ['type' => $type])
                ->withInput($input)
                ->with('message_error', trans('messages.user.incorrect_password'));
        }

        list($uid, $email, $name, $link, $avatarUrl) = SocialService::getSocialUser($type);

        $user = User::where('email', $email)->whereNull('active_token')->first();

        if ($user && !UserService::isBanedUser($user)) {
            Social::create([
                'email' => $email,
                'uid' => $uid,
                'link' => $link,
                'name' => $name,
                'type' => $type,
                'avatar_url' => $avatarUrl,
                'user_id' => $user->id
            ]);
            $user->name = $input['name'];
            $user->save();

            Auth::loginUsingId($user->id);
            RedisService::registerUser($user->id);
            return Redirect::action('HomeController@getTopPage');
        }

        return Redirect::action('UsersController@getLogin');
    }

    public function getFacebook()
    {
        $input = Input::all();

        if (isset($input['code'])) {
            SocialService::connectSocial(Social::FACEBOOK, $input['code']);
            $social = SocialService::getSocialUser(Social::FACEBOOK);
            return SocialService::authenticate(Social::FACEBOOK, $social);
        } else {
            return Redirect::to(SocialService::getAuthorizedUrl(Social::FACEBOOK));
        }
    }

    public function getGoogle()
    {
        $input = Input::all();
        if (isset($input['code'])) {
            SocialService::connectSocial(Social::GOOGLE, $input['code']);
            $social = SocialService::getSocialUser(Social::GOOGLE);
            return SocialService::authenticate(Social::GOOGLE, $social);
        } elseif (isset($input['error']) && !empty($input['error'])) {
            return Redirect::to('/');
        } else {
            return Redirect::to(SocialService::getAuthorizedUrl(Social::GOOGLE));
        }
    }

    public function getGithub()
    {
        $input = Input::all();
        if (isset($input['code'])) {
            SocialService::connectSocial(Social::GITHUB, $input['code']);
            $social = SocialService::getSocialUser(Social::GITHUB);
            return SocialService::authenticate(Social::GITHUB, $social);
        } else {
            return Redirect::to(SocialService::getAuthorizedUrl(Social::GITHUB));
        }
    }

    public function getCount(Request $request)
    {
        $url = $request->input('url', '');
        $provider = $request->input('provider', '');

        $result = [
            'error' => false,
            'data' => '',
            'message' => ''
        ];
        if (!empty($url) && !empty($provider)) {
            switch ($provider) {
                case 'facebook':
                    $endPoint = 'https://api.facebook.com/method/links.getStats';
                    $queryParams = [
                        'urls' => $url,
                        'format' => 'json'
                    ];

                    $response = SocialService::getSocialCountAPI($endPoint, $queryParams);

                    if (!empty($response) && isset($response[0])) {
                        $result['data'] = [
                            'share_count' => isset($response[0]['share_count']) ? $response[0]['share_count'] : 0,
                            'url' => isset($response[0]['url']) ? $response[0]['url'] : 0
                        ];
                    } else {
                        $result['error'] = true;
                        $result['message'] = trans('messages.notification.cant_not_get_fb_share_acc');
                    }

                    break;
                case 'twitter':
                    $endPoint = 'http://urls.api.twitter.com/1/urls/count.json';
                    $queryParams = [
                        'url' => $url
                    ];

                    $response = SocialService::getSocialCountAPI($endPoint, $queryParams);

                    if (!empty($response)) {
                        $result['data'] = [
                            'share_count' => $response['count'],
                            'url' => $response['url']
                        ];
                    } else {
                        $result['error'] = true;
                        $result['message'] = trans('messages.notification.cant_not_get_twt_share_acc');
                    }

                    break;
                case 'google':
                    $response = SocialService::getGooglePlusCountAPI($url);

                    $result['data'] = [
                        'share_count' => $response,
                        'url' => $url
                    ];
                    break;

                default:
                    $result['error'] = true;
                    $result['message'] = trans('messages.notification.empty_url_or_provider');
                    break;
            }
        } else {
            $result['error'] = true;
            $result['message'] = trans('messages.notification.empty_url_or_provider');
        }

        $result['data']['provider'] = $provider;

        return Response::json($result);
    }
}
