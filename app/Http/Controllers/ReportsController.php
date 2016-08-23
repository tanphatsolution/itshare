<?php namespace App\Http\Controllers;

use App\Data\Blog\Report;
use App\Services\ReportService;
use Validator;
use Request;
use View;
use Response;

class ReportsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware("authority:'','',admin", ['only' => ['index']]);
    }


    public function index()
    {
        if(Request::ajax()) {
            $status = Request::get('status');
            $reports = ReportService::filter($status);
            $hasItem = ($reports->count() > 0) ? true : false;
            $html = View::make('report.content', ['reports' => $reports])->render();
            return Response::json(['html' => $html, 'hasItem' => $hasItem]);
        } else {
            $this->viewData['title'] = trans('messages.report.index_title');
            $reports = ReportService::filter(ReportService::FILTER_ALL);
            $this->viewData['reports'] = $reports;
            $this->viewData['status'] = ReportService::FILTER_ALL;
            return View::make('report.index', $this->viewData);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Request::all();
        $input['user_id'] = $this->currentUser->id;
        $validator = Validator::make($input, Report::$storeRules);
        $response = [
            'success' => false,
            'message' => trans('messages.report.error_validate'),
        ];
        if ($validator->fails()) {
            return Response::json($response, 400);
        }
        $response = ReportService::create($input);
        return Response::json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $response = ReportService::delete($id);
        return Response::json($response);
    }

    /**
     * Batch process report ticket.
     *
     * @return Redirect
     */
    public function process()
    {
        $input = Request::all();
        $response = ReportService::batch($input);
        return Response::json($response);
    }
}
