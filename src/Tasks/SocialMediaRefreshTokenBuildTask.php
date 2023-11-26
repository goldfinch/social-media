<?php

namespace Goldfinch\SocialMedia\Tasks;

use Goldfinch\SocialMedia\Services\SocialMeta;
use SilverStripe\Dev\BuildTask;

class SocialMediaRefreshTokenBuildTask extends BuildTask
{
    private static $segment = 'SocialMediaRefresh';

    protected $enabled = true;

    protected $title = 'Social Media - refresh token';

    protected $description = 'Refresh long-lived tokens of Facebook/Instagram';

    public function run($request)
    {
        $service = new SocialMeta;

        // refresh for Facebook (TODO) check if never-expired-long-lived-token requires refresh
        $service->InstagramRefreshLongToken();
    }
}
