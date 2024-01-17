<?php

namespace Goldfinch\SocialMedia\Admin;

use SilverStripe\Admin\ModelAdmin;
use JonoM\SomeConfig\SomeConfigAdmin;
use Goldfinch\SocialMedia\Models\SocialMediaPost;
use SilverStripe\Forms\GridField\GridFieldConfig;
use Goldfinch\SocialMedia\Blocks\SocialMediaBlock;
use Goldfinch\SocialMedia\Configs\SocialMediaConfig;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;

class SocialMediaAdmin extends ModelAdmin
{
    use SomeConfigAdmin;

    private static $url_segment = 'social-media';

    private static $menu_title = 'Social media';

    private static $menu_icon_class = 'font-icon-block-instagram';

    private static $menu_priority = -0.5;

    private static $managed_models = [
        SocialMediaPost::class => [
            'title' => 'Posts',
        ],
        SocialMediaBlock::class => [
            'title' => 'Blocks',
        ],
        SocialMediaConfig::class => [
            'title' => 'Settings',
        ],
    ];

    protected function getGridFieldConfig(): GridFieldConfig
    {
        $config = parent::getGridFieldConfig();

        if ($this->modelClass == SocialMediaPost::class) {
            $config->removeComponentsByType(GridFieldAddNewButton::class);
            $config->removeComponentsByType(GridFieldEditButton::class);
        }

        return $config;
    }
}
