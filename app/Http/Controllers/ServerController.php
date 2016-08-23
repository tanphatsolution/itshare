<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ServerService;
use View;
use Redirect;

class ServerController extends BaseController
{
    const INPUT_PRIVILEGE_CHECKBOX_NAME = 'roles';

    /**
     * Instantiate a new ServerController instance.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('auth');
        
        $this->middleware("authority:'', '',admin", [
            'only' => [
                'getDeploy',
                'postDeploy',
            ]
        ]);
    }

    /**
     * Default Server page
     * Route /server/index
     * @return response
     */
    public function getIndex()
    {
        return Redirect::action('ServerController@getDeploy');
    }

    /**
     * Show deploy page
     * Route /server/deploy
     * @return response
     */
    public function getDeploy()
    {
        return View::make('server.deploy', $this->viewData);
    }

    /**
     * Deploy action
     * Route /server/deploy
     * @return reidrect
     */
    public function postDeploy(Request $request)
    {
        $input = $request->only('environment', 'branch', 'composer_update', 'migrate');
        $message_success = [trans('messages.server.deploy_success')];
        ServerService::announceChatwork($input, ServerService::STATUS_START);
        try {
            $feedback = ServerService::deploy($input);
        } catch (\Exception $e) {
            ServerService::announceChatwork($input, ServerService::STATUS_FAIL);
            return Redirect::action('ServerController@getDeploy')
                ->with('message', [trans('messages.server.deploy_error'), $e->getMessage()]);
        }
        ServerService::announceChatwork($input, ServerService::STATUS_SUCCESS);
        return Redirect::action('ServerController@getDeploy')
            ->with('message', array_merge($message_success, $feedback));
    }
}
