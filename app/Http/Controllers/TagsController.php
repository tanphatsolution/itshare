<?php namespace App\Http\Controllers;

use View;

class TagsController extends BaseController
{

    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewData['title'] = 'Tag';
    }

    /**
     * Default tag page
     * Route /tag/index
     */
    public function getIndex()
    {
        $this->viewData['title'] = 'Tag';
        return View::make('tag.index', $this->viewData);
    }

}
