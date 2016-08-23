<?php namespace App\Data\Blog;

use DB;

class Notification extends BaseModel
{
    CONST TYPE_FOLLOW = 1;
    CONST TYPE_STOCK = 2;
    CONST TYPE_COMMENT = 3;
    CONST TYPE_MENTION = 4;
    CONST TYPE_ADD_MEMBER_TO_GROUP = 5;
    CONST TYPE_APPROVE_POST_IN_GROUP = 6;
    CONST TYPE_APPROVE_MEMBER_IN_GROUP = 7;
    CONST TYPE_POST_IN_GROUP = 8;
    CONST TYPE_REPORT_POST = 9;
    CONST TYPE_FEEDBACK = 10;
    CONST TYPE_FOLLOWING_POST = 11;
    CONST STATUS_UNREAD = 0;
    CONST STATUS_READ = 1;
    CONST RECEIVE_MAIL_NOTIFICATION = 1;
    CONST IS_SENT_MAIL = 1;
    CONST NOTIFY_TYPE = 1;
    CONST REQUEST_TYPE = 2;
    CONST RECEIVE_MAIL_MAGAZINE = 1;

    protected $table = 'notifications';
    protected $guarded = ['id'];

    public static function findByArray($input)
    {
        $notification = null;
        foreach ($input as $key => $value) {
            if ($notification === null) {
                $notification = Notification::where($key, $value);
            } else {
                $notification = $notification->where($key, $value);
            }
        }
        return $notification->first();
    }

    public static function createOrUpdate($input)
    {
        if (!isset($input['comment_id'])) {
            $input['comment_id'] = 0;
        }
        if (!isset($input['post_id'])) {
            $input['post_id'] = 0;
        }
        $notification = self::findByArray($input);
        if (is_null($notification)) {
            $notification = Notification::create($input);
            if ($notification) {
                return $notification->id;
            }
        }
        if ($notification->status == self::STATUS_READ) {
            if ($notification->update(['status' => self::STATUS_UNREAD])) {
                return $notification->id;
            }
        }
        $notification->touch();
        return null;
    }

    public function sender()
    {
        return $this->belongsTo('App\Data\System\User', 'sender_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Data\System\User', 'recipient_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    public function group()
    {
        return $this->belongsTo('App\Data\Blog\Group');
    }

    public static function clearNotification($maxNotifications, $maxTimeLive)
    {
        $sql = 'DELETE
                FROM notifications
                WHERE id IN (
                    SELECT CN.id
                    FROM (
                        SELECT id, recipient_id, status, updated_at,
                            (
                                CASE recipient_id
                                WHEN @recipient
                                THEN @notification_num:=@notification_num+1
                                ELSE @notification_num := 1 AND @recipient := recipient_id END
                            ) as notification_num
                        FROM notifications, (SELECT @notification_num:=0, @recipient:=1) number
                        ORDER BY recipient_id ASC
                    ) CN
                    WHERE (CN.notification_num >= ' . $maxNotifications . ' OR CN.updated_at < NOW() - INTERVAL ' . $maxTimeLive . ' DAY) AND CN.status = 1
                )';
        return DB::delete($sql);
    }

    public static function maskAsRead($id)
    {
        $notification = self::find((int)$id);
        if ($notification) {
            return $notification->update(['status' => self::STATUS_READ]);
        }
        return false;
    }

    public static function checkReferenceLink($input)
    {
        if (isset($input['ref']) && isset($input['notif_id']) && $input['ref'] == 'notification') {
            return self::maskAsRead($input['notif_id']);
        }
        return false;
    }

    public static function createOrUpdateApproval($input)
    {
        $notification = self::where('type', $input['type'])
            ->where('recipient_id', $input['recipient_id'])
            ->where('group_id', $input['group_id'])
            ->first();
        if (!is_null($notification)) {
            $notification->update([
                'sender_id' => $input['sender_id'],
                'status' => self::STATUS_UNREAD,
            ]);
        } else {
            $notification = self::create($input);
        }

        return $notification;
    }
}
