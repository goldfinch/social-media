<?php

namespace Goldfinch\SocialMedia\Providers;

use Goldfinch\SocialMedia\Views\SocialFeed;
use SilverStripe\View\TemplateGlobalProvider;

class SocialMetaTemplateProvider implements TemplateGlobalProvider
{
    /**
     * @return array|void
     */
    public static function get_template_global_variables(): array
    {
        return [
            'SocialFeed'
        ];
    }

    /**
     * @return boolean
     */
    public static function SocialFeed() : SocialFeed
    {
        return SocialFeed::create();
    }
}
