<?php

namespace Goldfinch\SocialMedia\CronTasks;

use Goldfinch\SocialMedia\Services\SocialMeta;
use SilverStripe\CronTask\Interfaces\CronTask;

class SocialMediaRefreshTokenCronTask implements CronTask
{
    /**
     * run this task every second Friday of every other month at 00:00
     *
     * @return string
     */
    public function getSchedule()
    {
        return "0 0 0 ? 1/2 FRI#2 *";
    }

    /**
     *
     * @return void
     */
    public function process()
    {
        $service = new SocialMeta;

        // refresh for Facebook (TODO) check if never-expired-long-lived-token requires refresh
        $service->InstagramRefreshLongToken();
    }
}
