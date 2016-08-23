<?php
namespace App\Services;

use App\Data\Blog\OauthClient;
use App\Data\Blog\OauthClientEndpoint;


class AppService {

    public static function create($input)
    {
        $clientId = self::genClientId();
        $clientSecret = str_random(50);

        $newClient = OauthClient::create([
            'id' => $clientId,
            'secret' => $clientSecret,
            'name' => $input['client_name'],
        ]);

        if ($newClient) {
            OauthClientEndpoint::create([
                'client_id' => $clientId,
                'redirect_uri' => $input['redirect_uri'],
            ]);
        }
        $newClient->id = $clientId;
        return $newClient;
    }

    public static function genClientId($clientsId = [])
    {
        if (empty($clientsId)) {
            $clientsId = OauthClient::lists('id')->toArray();
        }

        $newId = uniqid();
        if (in_array($newId, $clientsId)) {
            return self::genClientId($clientsId);
        }

        return $newId;
    }

    public static function validate($input)
    {
        $errors = [];
        if (empty($input['client_name']) || empty($input['redirect_uri'])) {
            $errors['empty_input'] = trans('messages.client.empty_input');
        }
        if (!is_url($input['redirect_uri'])) {
            $errors['not_redirect_uri'] = trans('messages.client.not_redirect_uri');
        }
        return $errors;
    }
}
