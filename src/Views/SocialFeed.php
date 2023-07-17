<?php

namespace Goldfinch\SocialKit\Views;

use Goldfinch\SocialKit\Models\SocialPost;
use SilverStripe\View\ViewableData;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\ORM\FieldType\DBHTMLText;

class SocialFeed extends ViewableData
{
    private static $casting = [
        'FacebookPosts' => DBHTMLText::class,
    ];

    public function FacebookPosts($limit = 20)
    {
        if (!$this->authorized('MetaFacebook'))
        {
            return;
        }

        return SocialPost::get()->filter('Type', 'facebook')->limit($limit);
    }

    public function FacebookFeed($limit = 20)
    {
        if (!$this->authorized('MetaFacebook'))
        {
            return;
        }

        return $this->customise([
          'Posts' => $this->FacebookPosts($limit)
        ])->renderWith('Views/FacebookFeed');
    }

    public function InstagramPosts($limit = 20)
    {
        if (!$this->authorized('MetaInstagram'))
        {
            return;
        }

        return SocialPost::get()->filter('Type', 'instagram')->limit($limit);
    }

    public function InstagramFeed($limit = 20)
    {
        if (!$this->authorized('MetaFacebook'))
        {
            return;
        }

        return $this->customise([
          'Posts' => $this->InstagramPosts($limit)
        ])->renderWith('Views/InstagramFeed');
    }

    public function Posts($limit = 20)
    {
        if ($this->authorized('MetaFacebook') && $this->authorized('MetaInstagram'))
        {
            return SocialPost::get()->limit($limit);
        }
        else if ($this->authorized('MetaFacebook'))
        {
            return SocialPost::get()->filter('Type', 'facebook')->limit($limit);
        }
        else if ($this->authorized('MetaInstagram'))
        {
            return SocialPost::get()->filter('Type', 'instagram')->limit($limit);
        }
        else
        {
            return;
        }
    }

    public function forTemplate()
    {
        if (!$this->authorized('MetaFacebook') && !$this->authorized('MetaInstagram'))
        {
            return;
        }

        return $this->renderWith('Views/SocialFeed');
    }

    private function authorized($state)
    {
        $cfg = SiteConfig::current_site_config();

        if ($cfg->$state)
        {
            return true;
        }

        return false;
    }
}
