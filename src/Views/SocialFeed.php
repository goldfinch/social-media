<?php

namespace Goldfinch\SocialMedia\Views;

use SilverStripe\View\ViewableData;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Goldfinch\SocialMedia\Models\SocialMediaPost;
use Goldfinch\SocialMedia\Configs\SocialMediaConfig;

class SocialFeed extends ViewableData
{
    public function FacebookPosts($limit = null)
    {
        if (!$this->authorized('MetaFacebook'))
        {
            return;
        }

        if ($limit === null)
        {
            $cfg = $this->getCfg();
            $limit = $cfg->dbObject('MetaFacebookLimit')->getValue() ?? 10;
        }

        return SocialMediaPost::get()->filter('Type', 'facebook')->limit($limit);
    }

    public function FacebookFeed($limit = null)
    {
        if (!$this->authorized('MetaFacebook'))
        {
            return;
        }

        if ($limit === null)
        {
            $cfg = $this->getCfg();
            $limit = $cfg->dbObject('MetaFacebookLimit')->getValue() ?? 10;
        }

        return $this->renderWith('Views/FacebookFeed');
    }

    public function InstagramPosts($limit = null)
    {
        if (!$this->authorized('MetaInstagram'))
        {
            return;
        }

        if ($limit === null)
        {
            $cfg = $this->getCfg();
            $limit = $cfg->dbObject('MetaInstagramLimit')->getValue() ?? 10;

        }

        return SocialMediaPost::get()->filter('Type', 'instagram')->limit($limit);
    }

    public function InstagramFeed($limit = null)
    {
        if (!$this->authorized('MetaInstagram'))
        {
            return;
        }

        if ($limit === null)
        {
            $cfg = $this->getCfg();
            $limit = $cfg->dbObject('MetaInstagramLimit')->getValue() ?? 10;
        }

        return $this->renderWith('Views/InstagramFeed');
    }

    public function Posts($limit = null)
    {
        $cfg = $this->getCfg();

        if ($this->authorized('MetaFacebook') && $this->authorized('MetaInstagram'))
        {
            if ($limit === null)
            {
                $limit = $cfg->dbObject('MetaFacebookLimit')->getValue() ?? ($cfg->dbObject('MetaInstagramLimit')->getValue() ?? 10);
            }

            return SocialMediaPost::get()->limit($limit);
        }
        else if ($this->authorized('MetaFacebook'))
        {
            if ($limit === null)
            {
                $limit = $cfg->dbObject('MetaFacebookLimit')->getValue() ?? 10;
            }

            return SocialMediaPost::get()->filter('Type', 'facebook')->limit($limit);
        }
        else if ($this->authorized('MetaInstagram'))
        {
            if ($limit === null)
            {
                $limit = $cfg->dbObject('MetaInstagramLimit')->getValue() ?? 10;
            }

            return SocialMediaPost::get()->filter('Type', 'instagram')->limit($limit);
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
        $cfg = SocialMediaConfig::current_config();

        if ($cfg->$state)
        {
            return true;
        }

        return false;
    }

    private function getCfg()
    {
        return SocialMediaConfig::current_config();
    }
}
