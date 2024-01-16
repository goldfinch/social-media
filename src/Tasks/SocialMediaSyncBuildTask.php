<?php

namespace Goldfinch\SocialMedia\Tasks;

use Goldfinch\SocialMedia\Services\SocialMeta;
use SilverStripe\Dev\BuildTask;

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
