<?php namespace App\Services;
use App\Data\System\User;
use Redis;

class RedisService
{
    const REDIS_PREFIX = 'ngheroi_';

    const STORAGE_PREFIX = 'storage_';

    const NOTIFICATION_PREFIX = 'notification_';
    public static $redis;
    private $content;

    /**
     * @param null|string $content
     */
    public function __construct($content = null)
    {
        self::prepareRedis();
        $this->content = $content;
    }

    public static function prepareRedis()
    {
        if (!self::$redis) {
            self::$redis = Redis::connection();
        }
    }

    /**
     * Generate key from userId
     * @param $userId
     * @return string
     */
    public static function getKey($userId)
    {
        return self::REDIS_PREFIX . 'user_' . $userId;
    }

    /**
     * Register an user channel in Redis if not exists
     * @param int $userId
     * @return string mixed
     */
    public static function registerUser($userId)
    {
        $key = self::getKey($userId);
        self::prepareRedis();
        if (!self::$redis->exists($key)) {
            self::$redis->set($key, md5(microtime() . $userId . rand(10000, 99999)));
        }
        return self::getUserChannel($userId);
    }

    /**
     * Delete user channel in Redis
     * @param int $userId
     */
    public static function revokeUser($userId)
    {
        self::prepareRedis();
        self::$redis->del(self::getKey($userId));
    }

    /**
     * Get User channel in redis from userId
     * @param int $userId
     * @return string mixed
     */
    public static function getUserChannel($userId)
    {
        self::prepareRedis();
        return self::$redis->get(self::getKey($userId));
    }

    /**
     * Publish content to list users
     * @param User $users
     * @param string|null $channel
     */
    public function publish($users, $channel = null)
    {
        if (!is_array($users)) {
            $this->publishOne($users, $channel);
        } else {
            foreach ($users as $user) {
                $this->publishOne($user, $channel);
            }
        }
    }

    /**
     * @param User $user
     * @param string|null $channel
     */
    private function publishOne($user, $channel)
    {
        if (is_numeric($user)) {
            $userId = $user;
        } else {
            $userId = $user->id;
        }
        $channel = $channel ? $channel : $this->getUserChannel($userId);
        self::$redis->publish($channel, $this->content);
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getNotificationKey($user)
    {
        return self::REDIS_PREFIX . self::STORAGE_PREFIX . self::NOTIFICATION_PREFIX . $user->id;
    }

    /**
     * @param User $user
     * @param int $count
     */
    public static function setNotificationsCount($user, $count)
    {
        self::prepareRedis();
        $key = self::getNotificationKey($user);
        self::$redis->set($key, $count);
    }

    /**
     * @param User $user
     * @return int
     */
    public static function getNotificationsCount($user)
    {
        if (!$user) {
            return 0;
        }
        self::prepareRedis();
        $key = self::getNotificationKey($user);
        $count = self::$redis->get($key);
        return $count ? $count : 0;
    }
}
