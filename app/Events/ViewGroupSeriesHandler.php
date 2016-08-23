<?php namespace App\Events;

use App\Data\Blog\GroupSeries;
use Illuminate\Session\Store;

class ViewGroupSeriesHandler
{
    private $session;

    CONST TIME_DELAY_COUNTER = 3600; //default an hour
    CONST EVENT_NAME = 'groupseries.view';

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function handle(GroupSeries $groupSeries)
    {
        if (!$this->isGroupSeriesViewed($groupSeries))
        {
            $groupSeries->increment('views_count');
            $this->storeGroupSeries($groupSeries);
        }
    }

    private function isGroupSeriesViewed($groupSeries)
    {
        $now = time();
        $viewed = $this->session->get('viewed_series');
        if(!isset($viewed[$groupSeries->id])){
            return false;
        }
        $elapsedTime = $now - $viewed[$groupSeries->id];
        if(isset($viewed[$groupSeries->id]) && $elapsedTime > self::TIME_DELAY_COUNTER){
            return false;
        }
        return true;
    }

    private function storeGroupSeries($groupSeries)
    {
        $key = 'viewed_series.' . $groupSeries->id;
        $this->session->put($key, time());
    }

}
