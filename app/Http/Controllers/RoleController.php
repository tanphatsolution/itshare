<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Response;
use View;
use App\Data\Blog\Role;
use App\Data\Blog\RolePermission;
use App\Services\RoleService;
use App\Services\UserService;

class RoleController extends BaseController
{
    const INPUT_PRIVILEGE_CHECKBOX_NAME = 'roles';

    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('authority:read-edit,privilege', [
            'only' => [
                'getPrivilege',
                'postPrivilege',
                'getChange',
                'postChange',
            ]
        ]);
        $this->viewData['title'] = '';
    }

    /**
     * Default Role page
     * Route /role/index
     * @return response
     */
    public function getIndex()
    {
        return Redirect::action('RoleController@getPrivilege');
    }

    /**
     * Show edit privilege page
     * Route /role/privilege
     * @return response
     */
    public function getPrivilege()
    {

        $privilege = RoleService::privilege();
        $this->viewData['title'] = trans('messages.role.privilege_title');
        $this->viewData['inputName'] = self::INPUT_PRIVILEGE_CHECKBOX_NAME . '[%s][%s]';
        $this->viewData = array_merge($this->viewData, $privilege);
        return View::make('role.privilege', $this->viewData);
    }

    /**
     * Update privilege action
     * Route /role/privilege
     * @return response
     */
    public function postPrivilege(Request $request)
    {
        $input = $request->all();
        $inputRoles = [];
        if (isset($input[self::INPUT_PRIVILEGE_CHECKBOX_NAME])) {
            $inputRoles = $input[self::INPUT_PRIVILEGE_CHECKBOX_NAME];
        }
        $roles = Role::with('permission')->get()->toArray();
        RolePermission::updateRolePermission($roles, $inputRoles);
        return Redirect::action('RoleController@getPrivilege');
    }

    /**
     * Show change role page
     * Route /role/change
     * @return response
     */
    public function getChange(Request $request)
    {
        $name = $request->get('name');
        $this->viewData['title'] = trans('messages.role.change_title');
        $this->viewData['name'] = $name;
        $this->viewData['users'] = UserService::searchByName($name, false);
        return View::make('role.change', $this->viewData);
    }

    /**
     * Update role action
     * Route /role/change
     * @return response
     */
    public function postChange(Request $request)
    {
        $input = $request->all();
        $response = RoleService::change($input);
        return Redirect::back()
            ->with('message', $response['message'])
            ->with('type', $response['type']);
    }

}
