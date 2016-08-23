<?php namespace App\Services;

use App\Libraries\GithubLoginHelper;
use App\Libraries\LaravelFacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use App\Data\Blog\Social;
use App\Data\System\User;
use Google_Client;
use Google_Service_Oauth2;
use Session;
use Config;
use Redirect;
use Exception;
use Auth;

class SocialService
{
    public static $sessionKey = [
        Social::FACEBOOK => 'fbAccessToken',
        Social::GOOGLE => 'googleAccessToken',
        Social::GITHUB => 'githubAccessToken',
    ];

    public static function getFields()
    {
        return [
            Social::FACEBOOK => trans('socials.facebook'),
            Social::GOOGLE => trans('socials.google'),
            Social::GITHUB => trans('socials.github'),
        ];
    }

    public static function setSessionAccessToken($type, $token)
    {
        Session::put(self::$sessionKey[$type], $token);
    }

    public static function getSessionAccessToken($type)
    {
        return Session::get(self::$sessionKey[$type]);
    }

    public static function googleClient($urlCallback = '')
    {
        $urlCallback = empty($urlCallback) ? action('SocialsController@getGoogle') : $urlCallback;
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_APP_ID'));
        $client->setClientSecret(env('GOOGLE_APP_SECRET'));
        $client->setRedirectUri($urlCallback);
        $client->addScope([Google_Service_Oauth2::USERINFO_PROFILE, Google_Service_Oauth2::USERINFO_EMAIL]);

        return $client;
    }

    public static function getGoogleUserFromToken($token)
    {
        $client = SocialService::googleClient();
        $client->setAccessToken($token);
        $service = new Google_Service_Oauth2($client);
        $userinfo = $service->userinfo->get();
        $linkPage = $userinfo->link != null ? $userinfo->link : '#';

        return [$userinfo->id, $userinfo->email, $userinfo->name, $linkPage, $userinfo->picture];
    }

    public static function facebookSession($token = null, $urlCallback = '')
    {
        $urlCallback = empty($urlCallback) ? action('SocialsController@getFacebook') : $urlCallback;
        FacebookSession::setDefaultApplication(env('FACEBOOK_APP_ID'), env('FACEBOOK_APP_SECRET'));
        $session = null;
        $helper = new LaravelFacebookRedirectLoginHelper($urlCallback, env('FACEBOOK_APP_ID'), env('FACEBOOK_APP_SECRET'));

        try {
            if ($token) {
                $session = new FacebookSession($token);
                $session->validate();
            } else {
                $helper->disableSessionStatusCheck();
                $session = $helper->getSessionFromRedirect();
            }
        } catch (Exception $ex) {
            return [null, null];
        }

        return [$session, $helper];
    }

    public static function getFacebookUserFromToken($token)
    {
        FacebookSession::setDefaultApplication(env('FACEBOOK_APP_ID'), env('FACEBOOK_APP_SECRET'));
        $session = new FacebookSession($token);

        try {
            $session->validate();
        } catch (\Exception $ex) {
            return [null, null, null, null];
        }

        $request = new FacebookRequest($session, 'GET', '/me?fields=name,email,first_name,link,picture');
        $graphObject = $request->execute()->getGraphObject();

        $uid = $graphObject->getProperty('id');
        $email = $graphObject->getProperty('email');
        $name = $graphObject->getProperty('name');
        $link = $graphObject->getProperty('link');

        $avatarUrl = $graphObject->getProperty('picture')->asArray() != null
            ? $graphObject->getProperty('picture')->asArray()['url'] : '';

        if (empty($avatarUrl)) {
            $userPictureRequest = new FacebookRequest($session, 'GET', '/' . $uid . '/picture', ['redirect' => false]);
            $userPictureResponse = $userPictureRequest->execute();
            $userPictureGraphObject = $userPictureResponse->getGraphObject();
            $avatarUrl = $userPictureGraphObject->getProperty('url');
        }

        return [$uid, $email, $name, $link, $avatarUrl];
    }

    public static function getSocialUser($type)
    {
        $accessToken = SocialService::getSessionAccessToken($type);

        if ($accessToken) {
            switch ($type) {
                case Social::FACEBOOK:
                    return SocialService::getFacebookUserFromToken($accessToken);
                case Social::GOOGLE:
                    return SocialService::getGoogleUserFromToken($accessToken);
                case Social::GITHUB:
                    return SocialService::getGithubUserFromToken($accessToken);
            }
        }
        return [null, null, null, null];
    }

    public static function logoutSocial()
    {
        //Logout Facebook
        $fbAccessToken = Session::get(SocialService::$sessionKey[Social::FACEBOOK]);
        if ($fbAccessToken) {
            Session::forget(SocialService::$sessionKey[Social::FACEBOOK]);
            list($session, $helper) = SocialService::facebookSession($fbAccessToken);
            if ($session) {
                return Redirect::to($helper->getLogoutUrl($session, action('UsersController@getLogout')));
            }
        }

        //Logout and Revoke Google
        $googleAccessToken = Session::get(SocialService::$sessionKey[Social::GOOGLE]);
        if ($googleAccessToken) {
            Session::forget(SocialService::$sessionKey[Social::GOOGLE]);
            $client = SocialService::googleClient();
            $client->setAccessToken($googleAccessToken);
            $client->revokeToken();
        }

        //Logout Github
        $githubAccessToken = Session::get(SocialService::$sessionKey[Social::GITHUB]);
        if ($githubAccessToken) {
            Session::forget(SocialService::$sessionKey[Social::GITHUB]);
        }
    }

    public static function authenticate($type, $social)
    {
        list($uid, $email) = $social;

        if (Social::authenticate($type, $uid)) {
            if (Auth::check()) {
                return Redirect::action('HomeController@getTopPage');
            } else {
                return Redirect::action('UsersController@getLogin');
            }
        }

        if ($email && User::where('email', $email)->count() > 0) {
            return Redirect::action('SocialsController@getConfirm', ['type' => $type]);
        }

        return Redirect::action('SocialsController@getConnect', ['type' => $type]);
    }

    public static function githubClient($urlCallback = '')
    {
        $urlCallback = empty($urlCallback) ? action('SocialsController@getGithub') : $urlCallback;

        $client = new GithubLoginHelper([
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'scope' => env('GITHUB_SCOPE'),
            'app_name' => env('GITHUB_APP_NAME')
        ]);

        $client->setRedirectUri($urlCallback);
        return $client;
    }

    public static function getGithubUserFromToken($token)
    {
        $github = SocialService::githubClient();
        $userinfo = $github->getUserDetails($token);
        $email = property_exists($userinfo, 'email') ? $userinfo->email : '';
        return [$userinfo->id, $email, $userinfo->name, $userinfo->html_url, $userinfo->avatar_url];
    }

    public static function authorizedSocial($user, $type)
    {
        $socials = $user->socials()->where('type', $type);
        if ($socials->count() > 0) {
            return $socials->first();
        }
        return false;
    }

    public static function getAuthorizedUrl($type, $urlCallback = '')
    {
        if ($type == Social::FACEBOOK) {
            list($session, $helper) = SocialService::facebookSession(null, $urlCallback);
            return $helper->getLoginUrl(array('public_profile', 'email'));
        }

        if ($type == Social::GOOGLE) {
            $client = SocialService::googleClient($urlCallback);
            return $client->createAuthUrl();
        }

        if ($type == Social::GITHUB) {
            $github = SocialService::githubClient($urlCallback);
            return $github->getLoginUrl(env('GITHUB_SCOPE'));
        }

        return '';
    }

    public static function connectSocial($type, $code = '', $callbackUrl = '')
    {
        if ($type == Social::FACEBOOK) {
            list($session, $helper) = SocialService::facebookSession(null, $callbackUrl);
            if ($session) {
                $accessToken = $session->getAccessToken();
                $longLivedAccessToken = $accessToken->extend();
                SocialService::setSessionAccessToken(Social::FACEBOOK, $longLivedAccessToken);
                return true;
            }
        }
        if ($type == Social::GOOGLE) {
            $client = SocialService::googleClient($callbackUrl);
            $client->authenticate($code);
            SocialService::setSessionAccessToken(Social::GOOGLE, $client->getAccessToken());
            return true;
        }
        if ($type == Social::GITHUB) {
            $github = SocialService::githubClient($callbackUrl);
            $github->authenticate($code);
            $accessToken = $github->getAccessToken();
            SocialService::setSessionAccessToken(Social::GITHUB, $accessToken);
        }
        return false;
    }

    public static function getSocialCountAPI($url = '', $queryParams = [])
    {
        $response = null;
        if (!empty($url)) {
            $client = new \GuzzleHttp\Client();
            $request = $client->request('GET', $url, ['query' => $queryParams]);

            // Status Code of Request
            $statusCode = $request->getStatusCode();
            // Check status code
            if ($statusCode >= 200 && $statusCode < 300) {
                $response = json_decode($request->getBody(), true);
            }
        }

        return $response;
    }

    public static function getGooglePlusCountAPI($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://clients6.google.com/rpc');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        $curl_results = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($curl_results, true);


        return intval($json[0]['result']['metadata']['globalCounts']['count']);
    }
}
