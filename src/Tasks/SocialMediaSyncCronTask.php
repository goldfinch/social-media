<?php

namespace Goldfinch\SocialMedia\Tasks;

use Goldfinch\SocialMedia\Services\SocialMeta;
use SilverStripe\CronTask\Interfaces\CronTask;

class SocialMediaSyncCronTask implements CronTask
{
    /**
     * run this task every 60 minutes
     *
     * @return string
     */
    public function getSchedule()
    {
        return '*/60 * * * *';
    }

    /**
     * @return void
     */
    public function process()
    {
        $service = new SocialMeta();

        $service->FacebookFeed();
        $service->InstagramFeed();
    }
}
