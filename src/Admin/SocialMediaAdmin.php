<?php

namespace Goldfinch\SocialMedia\Admin;

use SilverStripe\Admin\ModelAdmin;
use JonoM\SomeConfig\SomeConfigAdmin;
use Goldfinch\SocialMedia\Blocks\SocialMediaBlock;
use Goldfinch\SocialMedia\Models\SocialMediaPost;
use Goldfinch\SocialMedia\Configs\SocialMediaConfig;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;

class SocialMediaAdmin extends ModelAdmin
{
    use SomeConfigAdmin;

    private static $url_segment = 'social-media';
    private static $menu_title = 'Social media';
    private static $menu_icon_class = 'bi-instagram';
    // private static $menu_priority = -0.5;

    private static $managed_models = [
        SocialMediaPost::class => [
            'title'=> 'Posts',
        ],
        SocialMediaBlock::class => [
            'title'=> 'Blocks',
        ],
        SocialMediaConfig::class => [
            'title'=> 'Settings',
        ],
    ];

    // public $showImportForm = true;
    // public $showSearchForm = true;
    // private static $page_length = 30;

    public function getList()
    {
        $list = parent::getList();

        // ..

        return $list;
    }

    protected function getGridFieldConfig(): GridFieldConfig
    {
        $config = parent::getGridFieldConfig();

        if ($this->modelClass == SocialMediaPost::class)
        {
            $config->removeComponentsByType(GridFieldAddNewButton::class);
        }

        return $config;
    }

    public function getSearchContext()
    {
        $context = parent::getSearchContext();

        // ..

        return $context;
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        // ..

        return $form;
    }

    // public function getExportFields()
    // {
    //     return [
    //         // 'Name' => 'Name',
    //         // 'Category.Title' => 'Category'
    //     ];
    // }
}
