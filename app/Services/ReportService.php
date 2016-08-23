<?php namespace App\Services;

use Event;
use DB;

use App\Data\Blog\Report;
use App\Events\ReportNotificationHandler;

class ReportService
{
    const BATCH_UNREAD = 1;
    const BATCH_READ = 2;
    const BATCH_RESOLVE = 3;
    const BATCH_DELETE = 4;
    const FILTER_ALL = 0;

    const REPORT_PER_PAGE = 10;
    const ADMIN_REPORT_PER_PAGE = 200;

    /**
     * @param array $input
     * @return array
     */
    public static function create($input)
    {
        $response = [
            'success' => true,
            'message' => trans('messages.report.create_success'),
        ];
        $report = Report::where('user_id', $input['user_id'])
            ->where('post_id', $input['post_id'])
            ->where('status', '<>', Report::STATUS_RESOLVED)
            ->first();
        if ($report) {
            $response['success'] = false;
            $response['message'] = trans('messages.report.error_created');
            return $response;
        }
        $report = Report::create([
            'user_id' => $input['user_id'],
            'post_id' => $input['post_id'],
            'type' => $input['type'],
        ]);

        \Event::fire(ReportNotificationHandler::EVENT_NAME, $report);
        NotificationService::sentMailNotify('report', $report);

        return $response;
    }

    public static function getAllStatuses()
    {
        return [
            Report::STATUS_ALL => trans('messages.report.all'),
            Report::STATUS_UNREAD => trans('messages.report.unread'),
            Report::STATUS_READ => trans('messages.report.read'),
            Report::STATUS_RESOLVED => trans('messages.report.resolved'),
        ];
    }

    public static function getStatusLabel($index)
    {
        $statuses = self::getAllStatuses();
        return $statuses[$index];
    }

    public static function getAllBatchs()
    {
        return [
            self::BATCH_UNREAD => trans('messages.report.unread'),
            self::BATCH_READ => trans('messages.report.read'),
            self::BATCH_RESOLVE => trans('messages.report.resolve'),
            self::BATCH_DELETE => trans('messages.report.delete'),
        ];
    }

    public static function getTypeLabel($index)
    {
        $types = [
            Report::TYPE_SPAM => trans('messages.report.type_spam'),
            Report::TYPE_ILLEGAL_CONTENT => trans('messages.report.type_illegal_content'),
            Report::TYPE_HARASSMENT => trans('messages.report.type_harassment'),
        ];
        return $types[$index];
    }

    public static function filter($status)
    {
        if ($status == Report::STATUS_ALL) {
            return Report::paginate(self::ADMIN_REPORT_PER_PAGE);
        }
        return Report::where('status', '=', $status)->paginate(self::ADMIN_REPORT_PER_PAGE);
    }

    public static function delete($id)
    {
        $response = [
            'success' => false,
            'message' => trans('messages.report.deleted_fail', ['id' => $id]),
        ];
        $report = Report::find($id);
        if (!$report) {
            return $response;
        }
        if ($report->delete()) {
            $response['success'] = true;
            $response['message'] = trans('messages.report.deleted_success', ['id' => $id]);
            return $response;
        }
        return $response;
    }

    public static function batch($input)
    {
        if (!isset($input['selected'])) {
            return [
                'success' => false,
                'message' => trans('messages.report.processing_invalid'),
                'type' => 'alert-danger',
            ];
        }
        $action = $input['batch'];
        $response = [
            'success' => true,
            'message' => trans('messages.report.processing_completed'),
            'type' => 'alert-success',
        ];

        $processResult = false;
        switch ($action) {
            case self::BATCH_READ:
            case self::BATCH_UNREAD:
            case self::BATCH_RESOLVE:
                $processResult = self::updateStatus($input, self::actionToStatus($action));
                break;
            case self::BATCH_DELETE:
                $processResult = self::batchDelete($input);
                break;
        }
        if ($processResult) {
            return $response;
        }

        $response = [
            'success' => true,
            'message' => trans('messages.report.processing_completed'),
            'type' => 'alert-danger',
        ];

        return $response;
    }

    public static function updateStatus($input, $status)
    {
        $ids = [];
        foreach ($input['selected'] as $id => $value) {
            $ids[] = $id;
        }
        $report = DB::table(Report::getTableName())
            ->whereIn('id', $ids)
            ->update(['status' => $status]);
        if ($report) {
            return true;
        }
        return false;
    }

    public static function batchDelete($input)
    {
        $ids = [];
        foreach ($input['selected'] as $id => $value) {
            $ids[] = $id;
        }
        $report = DB::table(Report::getTableName())
            ->whereIn('id', $ids)
            ->delete();
        if ($report) {
            return true;
        }
        return false;
    }

    public static function actionToStatus($index)
    {
        $actions = [
            self::BATCH_READ => Report::STATUS_READ,
            self::BATCH_UNREAD => Report::STATUS_UNREAD,
            self::BATCH_RESOLVE => Report::STATUS_RESOLVED,
        ];
        return $actions[$index];
    }
}
