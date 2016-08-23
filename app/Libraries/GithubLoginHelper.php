<?php namespace App\Libraries;

class GithubLoginHelper
{
    private $clientId = '';
    private $clientSecret = '';
    private $redirectUrl = '';
    private $app_name = '';

    public $authBase = 'https://github.com/';
    public $apiBase = 'https://api.github.com/';
    public $git_code = '';
    private $result = [];
    private $user = [];

    public function __construct($config)
    {
        if (is_array($config)) {
            $this->clientId = $config['client_id'];
            $this->clientSecret = $config['client_secret'];
            if (isset($config['redirect_url'])) {
                $this->redirectUrl = $config['redirect_url'];
            }
            $this->app_name = str_replace(' ', '-', $config['app_name']);
        }
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function setRedirectUri($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    private function parseResponse($response)
    {
        $content = array();
        $status = 200;
        $header = true;
        if (!empty($response)) {
            foreach (explode("\r\n", $response) as $line) {
                if ($line == '') {
                    $header = false;
                } else {
                    if ($header) {
                        $line = explode(': ', $line);
                        switch ($line[0]) {
                            case 'Status':
                                $status = substr($line[1], 0, 3);
                                break;
                            case 'X-RateLimit-Limit':
                                intval($line[1]);
                                break;
                            case 'X-RateLimit-Remaining':
                                intval($line[1]);
                                break;
                        }
                    } else {
                        $content[] = $line;
                    }
                }
            }
            return [$status, json_decode(implode("\n", $content))];
        }
    }

    private function sendRequest($config)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($config['header'] and is_array($config['header'])) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $config['header']);
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($config['method'] == 'POST' and !empty($config['data'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $config['data']);
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        curl_setopt($ch, CURLOPT_URL, $config['url']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        return $this->parseResponse($response);
    }

    public function authenticate($code)
    {
        $this->git_code = $code;
        $config = [
            'method' => 'POST',
            'data' => $this->buildParams(),
            'header' => ['Content-Type: application/x-www-form-urlencoded', 'Accept: application/json'],
            'url' => $this->authBase . 'login/oauth/access_token',
        ];
        $this->result = $this->sendRequest($config);
    }

    private function buildParams()
    {
        return http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'client_secret' => $this->clientSecret,
            'code' => $this->git_code,
        ]);
    }

    public function getAccessToken()
    {
        return isset($this->result[1]->access_token) ? $this->result[1]->access_token : '';
    }

    public function getStatusCode()
    {
        return $this->result[0];
    }

    public function getTokenType()
    {
        return $this->result[1]->token_type;
    }

    public function getScope()
    {
        return $this->result[1]->scope;
    }

    private function sendUserDetailsReq($token = '')
    {
        $config = [
            'method' => 'GET',
            'header' => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                'User-Agent: ' . $this->app_name,
            ],
            'url' => $this->apiBase . 'user?access_token=' . (empty($token) ? $this->getAccessToken() : $token),
        ];
        $this->user = $this->sendRequest($config);
    }

    public function getUserDetails($token = '')
    {
        $this->sendUserDetailsReq($token);
        return $this->user[1];
    }

    public function getLoginUrl($scope)
    {
        $url = $this->authBase . 'login/oauth/authorize?client_id=' . $this->clientId . '&redirect_uri='
            . $this->redirectUrl . '&scope=' . $scope;
        return $url;
    }

    public function userData($key)
    {
        return $this->user[1]->$key;
    }

}
