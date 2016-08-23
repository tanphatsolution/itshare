<?php
namespace App\Services;

use App\Data\Blog\Feedback;

class FeedbackService
{
    const STATUS_ALL = '0';
    const STATUS_UNREAD = '1';
    const STATUS_READ = '2';
    const STATUS_REPLIED = '3';
    const STATUS_FINISHED = '4';

    const PER_PAGE = 10;
    const ADMIN_PER_PAGE = 200;

    /**
     * Create search options for feedback
     *
     * @param
     * @return array
     */
    public static function getFindFeedbackOptions()
    {
        $searchOptions = [
            self::STATUS_ALL => trans('feedbacks.feedback_status.all'),
            self::STATUS_UNREAD => trans('feedbacks.feedback_status.unread'),
            self::STATUS_READ => trans('feedbacks.feedback_status.read'),
            self::STATUS_REPLIED => trans('feedbacks.feedback_status.replied'),
            self::STATUS_FINISHED => trans('feedbacks.feedback_status.finished'),
        ];
        return $searchOptions;
    }

    /**
     * Get feedback data by search option
     *
     * @param  String $searchOption Feedback's status
     * @return array
     */
    public static function getFeedbackByOptions($searchOption = null)
    {
        if ($searchOption !== null && array_key_exists($searchOption, self::getFindFeedbackOptions())) {
            $data = Feedback::where('status', $searchOption)
                ->paginate(self::ADMIN_PER_PAGE);
        } else {
            $data = Feedback::paginate(self::ADMIN_PER_PAGE);
        }
        return $data;
    }

    /**
     * Get feedback data by search option
     *
     * @param  String $id Feedback's id
     * @param  String $when In which action this function was called  : 'read' | 'reply'
     * @param  String $option Special option : 'close' - change status to finished
     * @return boolean              true if success ;  fail is there is error
     */
    public static function saveFeedbackStatus($id = null, $when = null, $option = null)
    {
        if ($id === null) {
            return false;
        }

        $feedback = Feedback::find($id);

        if (!$feedback) {
            return false;
        }

        switch ($when) {
            case 'read':
                if ($feedback->status == self::STATUS_UNREAD) {
                    $feedback->status = self::STATUS_READ;
                }
                break;
            case 'reply':
                if ($option == 'close') {
                    $feedback->status = self::STATUS_FINISHED;
                } elseif ($feedback->status == self::STATUS_READ || $feedback->status == self::STATUS_READ) {
                    $feedback->status = self::STATUS_REPLIED;
                }
                break;
            default:
                return false;
        }

        $feedback->save();
        return true;
    }

    /**
     * Get feedback data by search option
     *
     * @param  String $status Feedback status code
     * @return String   $txt        Feedback status text
     */
    public static function getFeedbackStatusText($status)
    {
        switch ($status) {
            case self::STATUS_ALL:
                $txt = trans('feedbacks.feedback_status.all');
                break;
            case self::STATUS_READ:
                $txt = trans('feedbacks.feedback_status.read');
                break;
            case self::STATUS_UNREAD:
                $txt = trans('feedbacks.feedback_status.unread');
                break;
            case self::STATUS_REPLIED:
                $txt = trans('feedbacks.feedback_status.replied');
                break;
            case self::STATUS_FINISHED:
                $txt = trans('feedbacks.feedback_status.finished');
                break;
            default:
                $txt = '';
                break;
        }
        return $txt;
    }
}
