<?php

namespace Goldfinch\SocialMedia\Tasks;

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
        return '0 0 0 ? 1/2 FRI#2 *';
    }

    /**
     * @return void
     */
    public function process()
    {
        $service = new SocialMeta();

        $service->InstagramRefreshLongToken();

        // for Facebook we get new Long Live token instead
        $service->FacebookGetLongLiveToken();
    }
}
