<?php
namespace App\Services;

use Illuminate\Http\Request;

define('WEB_ROOT_DIR', base_path());

class ServerService
{
    const ENV_LOCAL = 1;
    const ENV_LOCAL_NAME = 'local';
    const ENV_STAGING = 2;
    const ENV_STAGING_NAME = 'staging';
    const ENV_PRODUCTION = 3;
    const ENV_PRODUCTION_NAME = 'production';

    const BRANCH_MASTER = 1;
    const BRANCH_MASTER_NAME = 'master';
    const BRANCH_DEVELOP = 2;
    const BRANCH_DEVELOP_NAME = 'develop';
    const BRANCH_RELEASE = 3;
    const BRANCH_RELEASE_NAME = 'release';

    const MIGRATE_NOTHING = 0;
    const MIGRATE = 1;
    const MIGRATE_SEED = 2;
    const MIGRATE_REFRESH_SEED = 3;

    const COMMAND_CD = 'cd %s';
    const COMMAND_CHECKOUT = 'git checkout %s';
    const COMMAND_PULL = 'git pull origin %s';
    const COMMAND_PUSH = 'git push origin %s';
    const COMMAND_COMPOSER_UPDATE = 'composer update';
    const COMMAND_COMPOSER_DUMP_AUTOLOAD = 'composer dump-autoload';
    const COMMAND_MIGRATE = 'php artisan migrate';
    const COMMAND_MIGRATE_SEED = 'php artisan migrate --seed';
    const COMMAND_MIGRATE_REFRESH_SEED = 'php artisan migrate:refresh --seed';

    const CHATWORK_URI = 'https://api.chatwork.com/v1/rooms/%s/messages';
    const STATUS_START = 1;
    const STATUS_START_MESSAGE = '[info][title]Notice[/title][%s] Starting Deploy[/info]';
    const STATUS_SUCCESS = 2;
    const STATUS_SUCCESS_MESSAGE = '[info][title]Notice[/title][%s] Deployed Successfully[/info]';
    const STATUS_FAIL = 3;
    const STATUS_FAIL_MESSAGE = '[info][title]Notice[/title][%s] Deploy Failed[/info]';

    public static function getAllEnvironments()
    {
        return [
            self::ENV_LOCAL => self::ENV_LOCAL_NAME,
            self::ENV_STAGING => self::ENV_STAGING_NAME,
            self::ENV_PRODUCTION => self::ENV_PRODUCTION_NAME,
        ];
    }

    public static function getDefaultEnvironments(Request $request)
    {
        $environment = $request->has('NGHEROI_PROJECT_ENV') ? $request->get('NGHEROI_PROJECT_ENV') : '';
        switch ($environment) {
            case self::ENV_LOCAL_NAME:
                return self::ENV_LOCAL;
            case self::ENV_STAGING_NAME:
                return self::ENV_STAGING;
            case self::ENV_PRODUCTION_NAME:
                return self::ENV_LOCAL;
            default:
                return self::ENV_LOCAL;
        }
    }

    public static function getAllBranchs()
    {
        return [
            self::BRANCH_MASTER => self::BRANCH_MASTER_NAME,
            self::BRANCH_DEVELOP => self::BRANCH_DEVELOP_NAME,
            self::BRANCH_RELEASE => self::BRANCH_RELEASE_NAME,
        ];
    }

    public static function getDefaultBranchs()
    {
        return self::BRANCH_DEVELOP;
    }

    public static function getCommandMigrate($migrate = self::MIGRATE)
    {
        switch ($migrate) {
            case self::MIGRATE:
                return self::COMMAND_MIGRATE;
            case self::MIGRATE_SEED:
                return self::COMMAND_MIGRATE_SEED;
            case self::MIGRATE_REFRESH_SEED:
                return self::COMMAND_MIGRATE_REFRESH_SEED;
            default:
                return self::COMMAND_MIGRATE;
        }
    }

    public static function buildCommand($input, $branch)
    {
        $commands = [
            sprintf(self::COMMAND_CD, WEB_ROOT_DIR),
            sprintf(self::COMMAND_CHECKOUT, $branch),
        ];

        if (!isset($input['checkout']) || !$input['checkout']) {
            $commands[] = sprintf(self::COMMAND_PULL, $branch);
        }

        if (isset($input['composer_update']) && $input['composer_update']) {
            $commands[] = self::COMMAND_COMPOSER_UPDATE;
        }

        $commands[] = self::COMMAND_COMPOSER_DUMP_AUTOLOAD;

        if ($input['migrate']) {
            $commands[] = self::getCommandMigrate($input['migrate']);
        }
        return $commands;
    }

    public static function deploy($input)
    {
        if (!isset($input['environment']) || !isset($input['branch'])) {
            throw new \Exception(trans('messages.server.deploy_invalid_input'));
        }

        $environments = self::getAllEnvironments();
        $environment = $environments[$input['environment']];
        $branchs = self::getAllBranchs();
        $branch = $branchs[$input['branch']];

        $commands = self::buildCommand($input, $branch);

        $feedback = [];
        \SSH::into($environment)->run($commands, function ($line) use (&$feedback) {
            $feedback[] = $line;
        });
        return $feedback;
    }

    public static function announceChatwork($input, $status)
    {
        if (!isset($input['environment'])) {
            return false;
        }
        $environments = self::getAllEnvironments();
        $environment = ucfirst($environments[$input['environment']]);
        $message = '';
        switch ($status) {
            case self::STATUS_START:
                $message = sprintf(self::STATUS_START_MESSAGE, $environment);
                break;
            case self::STATUS_SUCCESS:
                $message = sprintf(self::STATUS_SUCCESS_MESSAGE, $environment);
                break;
            case self::STATUS_FAIL:
                $message = sprintf(self::STATUS_FAIL_MESSAGE, $environment);
                break;
        }
        self::sendMessageToChatwork($message);
    }

    public static function sendMessageToChatwork($message)
    {
        $request = new Request();
        $room_id = $request->has('NGHEROI_ROOM_ID') ? $request->has('NGHEROI_ROOM_ID') : '';
        $api_token = $request->has('NGHEROI_API_TOKEN') ? $request->has('NGHEROI_API_TOKEN') : '';

        if (empty($room_id) || empty($api_token)) {
            return false;
        }

        $params = [
            'body' => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf(self::CHATWORK_URI, $room_id));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-ChatWorkToken: ' . $api_token]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
        return true;
    }
}