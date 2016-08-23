<?php namespace App\Http\Controllers;

use App\Data\Blog\PostHelpful;
use Request;
use Input;
use Auth;
use Response;
use Validator;

class PostHelpfulsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function store()
    {
        if (Request::ajax()) {
            $message = trans('messages.vote.error');
            $input = Input::all();
            if (Auth::check()) {
                $input['user_id'] = $this->currentUser->id;
                if (PostHelpful::where('post_id', $input['post_id'])->where('user_id', $input['user_id'])->count() > 0) {
                    $message = trans('messages.vote.already_warning');
                    return Response::json([
                        'message' => $message,
                        'success' => false,
                    ], 200);
                }
            } else {
                $message = trans('messages.vote.login_warning');
            }
            $validator = Validator::make($input, PostHelpful::$createRules);
            if ($validator->fails()) {
                return Response::json([
                    'message' => $message,
                    'success' => false,
                ], 200);
            }
            $messages = ($input['helpful'] == PostHelpful::HELPFUL_YES) ? trans('messages.vote.voted_helpful') : trans('messages.vote.voted_not_helpful');
            $helpful = PostHelpful::create($input);
            if ($helpful) {
                return Response::json([
                    'message' => $messages,
                    'helpful' => ($input['helpful'] == PostHelpful::HELPFUL_YES) ? true : false,
                    'success' => true,
                ], 200);
            }
        }
    }
}
