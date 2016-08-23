<?php namespace App\Events;

use App\Data\System\User;

trait MentionNotificationTrait
{
    /**
     * Parse content and return Users who are mentioned
     * @param string $text
     * @return array User
     */
    public function getMentionedUsers($text)
    {
        $regex = '/(^|\s)@([a-z0-9][a-z0-9_\.]+)/i';
        preg_match_all($regex, $text, $usernames);
        if (!empty($usernames[2])) {
            $usernames = array_unique($usernames[2]);
            return User::whereIn('username', $usernames)->get();
        }
        return [];
    }
}