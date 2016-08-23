<?php namespace App\Http\Controllers;

use App\Data\Blog\Feedback;
use App\Events\FeedbackNotificationHandler;
use App\Services\FeedbackService;
use App\Services\MailService;
use App\Services\NotificationService;
use Request;
use Input;
use View;
use Response;
use Redirect;
use Event;
use Config;

class FeedbacksController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('authority:read-edit,privilege', [
            'only' => [
                'index',
                'postIndex',
                'show',
                'postReply',
                'getPreview',
            ],
        ]);

        $this->viewData['title'] = trans('titles.feedbacks');
    }

    public function index()
    {
        $data = Feedback::whereNotIn('status', [FeedbackService::STATUS_FINISHED])
            ->paginate(FeedbackService::ADMIN_PER_PAGE);
        if (Input::has('option')) {
            $searchOption = Input::get('option');
            $data = FeedbackService::getFeedbackbyOptions($searchOption);
        }
        $this->viewData['data'] = $data;
        $this->viewData['searchOption'] = isset($searchOption) ? $searchOption : FeedbackService::STATUS_ALL;
        return View::make('feedback.index', $this->viewData);
    }

    public function postIndex()
    {
        if (Input::get('is_feedback') == true) {
            $action = Input::get('action');
            $feedbackId = (int) Input::get('feedback_id');
            $feedback = Feedback::find($feedbackId);
            switch ($action) {
                case 'finish':
                    $feedback->status = FeedbackService::STATUS_FINISHED;
                    break;
                case 'open':
                    $feedback->status = FeedbackService::STATUS_REPLIED;
                    break;
                default:
                    return false;
            }
            $feedback->save();
        }
        return Redirect::action('FeedbacksController@index');
    }

    /**
     * Show the form for creating a new feedback.
     *
     * @return Response
     */
    public function getCreate()
    {
        $this->viewData['captchaHtml'] = $this->Captcha->Html();
        return View::make('feedback.create', $this->viewData);
    }

    /**
     * Save a newly created feedback.
     *
     * @return Response
     */
    public function store()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $input['name'] = ($this->currentUser) ? $this->currentUser->name : trans('labels.not_registered_user');
            $message = '';
            $validator = Feedback::validateFeedback($input);
            if ($validator->fails()) {
                $errors = true;
                foreach ($validator->messages()->all() as $msg) {
                    $message .= $msg . ' ';
                }
            } else {
                $feedback = new Feedback;
                $feedback->title = $input['title'];
                $feedback->message = $input['message'];
                $feedback->name = $input['name'];
                $feedback->email = $input['email'];
                $feedback->status = FeedbackService::STATUS_UNREAD;

                if ($this->currentUser) {
                    $feedback->username = $this->currentUser->username;
                    $feedback->userId = $this->currentUser->id;
                }

                $savedFeedback = $feedback->save();
                $errors = $savedFeedback ? false : true;
                $message = $savedFeedback ? trans('messages.feedback.success') : trans('messages.feedback.error');
                if ($savedFeedback) {
                    Event::fire(FeedbackNotificationHandler::EVENT_NAME, $feedback);
                    NotificationService::sentMailNotify('feedback', $feedback);
                    NotificationService::sentMailNotifyAdmin('feedback', $feedback);
                }
            }

            return Response::json(['errors' => $errors, 'message' => $message], 200);
        } else {
            $input = Input::all();
            $code = Input::get('CaptchaCode');
            $isHuman = $this->Captcha->Validate($code);

            if (!$isHuman) {
                return Redirect::to('feedbacks/create')->withErrors(trans('feedbacks.create.wrong_captcha'))->withInput(Input::except('CaptchaCode'));
            }

            $validator = Feedback::validateFeedback($input);

            if ($validator->fails()) {
                return Redirect::to('feedbacks/create')->withErrors($validator)->withInput(Input::all());
            }
            $feedback = new Feedback;
            $feedback->title = $input['title'];
            $feedback->message = $input['message'];
            $feedback->name = $input['name'];
            $feedback->email = $input['email'];
            $feedback->status = FeedbackService::STATUS_UNREAD;

            if ($this->currentUser) {
                $feedback->username = $this->currentUser->username;
                $feedback->userId = $this->currentUser->id;
            }

            $feedback->save();

            return Redirect::action('HomeController@getTopPage')
                ->with('feedbackCreatedMessage', trans('feedbacks.create.success'));
        }
    }

    /**
     * Display the specified feedback.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $data = Feedback::find($id);

        $this->viewData['id'] = $id;
        $this->viewData['data'] = $data;

        if ($data != null) {
            FeedbackService::saveFeedbackStatus($id, 'read');

            return View::make('feedback.show', $this->viewData);
        } else {
            return Redirect::action('FeedbacksController@getIndex');
        }
    }

    /**
     * Display the form to create reply to a feedback.
     *
     * @param
     * @return Response
     */
    public function postReply()
    {
        $data = Input::only('title', 'name', 'feedback', 'reply', 'email', 'id');

        if (Input::has('preview')) {
            $this->viewData['data'] = $data;
            return View::make('feedback.preview', $this->viewData);
        } elseif (Input::has('send')) {
            $isFinished = Input::get('isFinished');
            FeedbackService::saveFeedbackStatus($data['id'], 'reply', $isFinished ? 'close' : '');
            $to = [
                'address' => $data['email'],
                'name' => $data['name'],
            ];
            $subject = trans('feedbacks.reply.thank_you_message', ['app_name' => Config::get('app.app_name')]);
            $layout = 'emails.feedback.reply';
            $data['name'] = trans('feedbacks.reply_mail.greeting_email', ['customer_name' => $data['name']]);
            MailService::send(Config::get('mail.from'), $to, $subject, $data, $layout, MailService::EMAIL_LOG_TYPE_FEEDBACK);
            return Redirect::to('feedbacks')->with('msg', trans('feedbacks.send_reply.success'));
        } else {
            return View::make('home.index');
        }
    }

    /**
     * Preview a reply in email format.
     *
     * @param
     * @return Response
     */
    public function getPreview()
    {
        return View::make('feedback.preview');
    }

}
