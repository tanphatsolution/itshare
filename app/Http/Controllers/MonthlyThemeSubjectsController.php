<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\ThemeService;
use Cache;
use View;

use Request;
use Input;
use Redirect;
use Response;

use App\Data\Blog\MonthlyProfessional;
use App\Data\Blog\MonthlyTheme;
use App\Data\Blog\MonthlyThemeLanguage;
use App\Data\Blog\MonthlyThemeSubject;

class MonthlyThemeSubjectsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => ['create', 'checkBackNumber'],
        ]);
    }

    public function create()
    {
        $this->viewData['timeOptions'] = ThemeService::monthYearOptions();
        $this->viewData['title'] = trans('titles.create_theme_monthly');
        return View::make('monthlythemes.create', $this->viewData);
    }

    public function store()
    {
        $input = Input::all();
        $input['display_slider'] = isset($input['display_slider']) ? 1 : 0;
        $monthlyThemeSubject = MonthlyThemeSubject::where('publish_month', $input['publish_month'])
                                    ->where('publish_year', $input['publish_year'])
                                    ->get();
        $errors = ThemeService::validate($input);
        if (!empty($errors)) {
            $input['imgInput'] = !empty($input['image']) ? ThemeService::uploadImg($input['image']) : $input['imgInput'];
            $this->viewData['timeOptions'] = ThemeService::monthYearOptions();
            $this->viewData['input'] = $input;
            $this->viewData['title'] = trans('titles.create_theme_monthly');
            return View::make('monthlythemes.create', $this->viewData)->withErrors($errors);
        }
        if ($monthlyThemeSubject->count() == 0) {
            ThemeService::create($input);
        }
        Cache::forget('month_theme_subject');
        return Redirect::action('MonthlyThemeSubjectsController@getView', [$input['publish_month'], $input['publish_year']])->with('success', trans('messages.theme.save_success'));
    }

    public function getView($month = null, $year = null)
    {
        $this->viewData['title'] = trans('titles.back_number');
        $this->viewData['month'] = $month;
        $this->viewData['year'] = $year;
        $this->viewData['timeOptions'] = ThemeService::monthYearOptions();
        return View::make('monthlythemes.backnumber', $this->viewData);
    }

    public function checkBackNumber()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $monthlyThemeSubject = MonthlyThemeSubject::where('publish_month', $input['publishMonth'])
                                    ->where('publish_year', $input['publishYear'])
                                    ->get();
            $backNumber = ($monthlyThemeSubject->count() > 0) ? true : false;
            $html = '';
            if ($backNumber) {
                $monthlyProfessionals = MonthlyProfessional::where('monthly_theme_subject_id', $monthlyThemeSubject[0]->id)
                                                            ->orderBy('order', 'asc')
                                                            ->get();
                $monthlyThemesId = MonthlyTheme::where('monthly_theme_subject_id', $monthlyThemeSubject[0]->id)
                                                ->orderBy('order', 'asc')->lists('id');
                $monthlyThemes = [];

                if (!$monthlyThemesId->isEmpty()) {
                    $monthlyThemeLanguages = MonthlyThemeLanguage::whereIn('monthly_theme_id', $monthlyThemesId)
                                                                ->orderBy((DB::raw('FIELD(monthly_theme_id, ' . implode(',', $monthlyThemesId->toArray()) . ')')))
                                                                ->get();
                    foreach ($monthlyThemeLanguages as $monthlyThemeLanguage) {
                        $monthlyThemes[$monthlyThemeLanguage->monthly_theme_id][$monthlyThemeLanguage->language_code] = [
                            'id' => $monthlyThemeLanguage->id,
                            'name' => $monthlyThemeLanguage->name,
                        ];
                    }
                }

                $html = View::make('monthlythemes.edit', [
                    'monthlyThemeSubject' => $monthlyThemeSubject[0],
                    'monthlyProfessionals' => $monthlyProfessionals,
                    'monthlyThemes' => $monthlyThemes,
                    'monthlyThemesId' => $monthlyThemesId,
                    'timeOptions' => ThemeService::monthYearOptions(),
                ])->render();
            }
            return Response::json([
                'html' => $html,
                'backNumber' => $backNumber,
            ], 200);
        }
    }

    public function postUpdate()
    {
        $input = Input::all();
        $input['display_slider'] = isset($input['display_slider']) ? 1 : 0;
        $errors = ThemeService::validate($input);
        if (!empty($errors)) {
            $this->viewData['input'] = $input;
            $this->viewData['title'] = trans('titles.create_theme_monthly');
            return Redirect::action('MonthlyThemeSubjectsController@getView', [$input['publish_month'], $input['publish_year']])->withErrors($errors);
        }
        $update = ThemeService::update($input);
        if ($update) {
            Cache::forget('month_theme_subject');
            return Redirect::action('MonthlyThemeSubjectsController@getView', [$input['publish_month'], $input['publish_year']])->with('success', trans('messages.theme.save_success'));
        }
        $errors['can_not_update_some_field'] = trans('messages.theme.can_not_update_some_field');
        return Redirect::action('MonthlyThemeSubjectsController@getView', [$input['publish_month'], $input['publish_year']])->withErrors($errors);
    }

    public function getListMonthlyThemes()
    {
        if (Request::ajax()) {
            $monthlyThemeSubjectId = Input::get('monthlyThemeSubjectId');
            $themeId = Input::get('themeId');
            $monthlyThemes = MonthlyTheme::where('monthly_theme_subject_id', $monthlyThemeSubjectId)->get();
            $html = ThemeService::getOptionMonthlyThemes($monthlyThemes, $themeId);
            return Response::json([
                'html' => $html,
            ], 200);
        }
    }

    public function checkInput()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $errors = ThemeService::validate($input);
            $error = false;
            $notice = '';
            if (!empty($errors)) {
                $error = true;
                foreach ($errors as $message) {
                    $notice .= $message . ' ';
                }
            }
            return Response::json([
                'error' => $error,
                'notice' => $notice,
            ], 200);
        }
    }
}