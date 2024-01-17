<?php

namespace Goldfinch\SocialMedia\Tasks;

use SilverStripe\Dev\BuildTask;
use Goldfinch\SocialMedia\Services\SocialMeta;

class SocialMediaSyncBuildTask extends BuildTask
{
    private static $segment = 'SocialMediaSync';

    protected $enabled = true;

    protected $title = 'Social Media - sync';

    protected $description = 'Fetch/sync social media posts from Facebook/Instagram';

    public function run($request)
    {
        $service = new SocialMeta();

        $service->FacebookFeed();
        $service->InstagramFeed();
    }
}
