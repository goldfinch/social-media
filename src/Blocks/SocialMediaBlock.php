<?php

namespace Goldfinch\SocialMedia\Blocks;

use DNADesign\Elemental\Models\BaseElement;

class SocialMediaBlock extends BaseElement
{
    private static $table_name = 'SocialMediaBlock';
    private static $singular_name = 'Social Media';
    private static $plural_name = 'Social Media';

    private static $db = [
        'FeedType' => 'Enum("facebook,instagram,mixed", "mixed")',
        'FeedLimit' => 'Int',
    ];

    private static $inline_editable = false;
    private static $description = '';
    private static $icon = 'bi-instagram';
    // private static $disable_pretty_anchor_name = false;
    // private static $displays_title_in_template = true;

    private static $has_one = [];

    private static $owns = [];

    // private static $belongs_to = [];
    // private static $has_many = [];
    // private static $many_many = [];
    // private static $many_many_extraFields = [];
    // private static $belongs_many_many = [];

    // private static $default_sort = null;
    // private static $indexes = null;
    // private static $casting = [];

    private static $field_labels = [
        'FeedType' => 'Type',
        'FeedLimit' => 'Post limit',
    ];
    // private static $field_labels = [];
    // private static $searchable_fields = [];

    // private static $cascade_deletes = [];
    // private static $cascade_duplicates = [];

    // * goldfinch/helpers
    // private static $field_descriptions = [];
    // private static $required_fields = [];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // ..

        return $fields;
    }

    public function getSummary()
    {
        return $this->getDescription();
    }

    public function getType()
    {
        $default = $this->i18n_singular_name() ?: 'Block';

        return _t(__CLASS__ . '.BlockType', $default);
    }

    // public function validate()
    // {
    //     $result = parent::validate();

    //     // $result->addError('Error message');

    //     return $result;
    // }

    // public function onBeforeWrite()
    // {
    //     // ..

    //     parent::onBeforeWrite();
    // }

    // public function onBeforeDelete()
    // {
    //     // ..

    //     parent::onBeforeDelete();
    // }

    // public function canView($member = null)
    // {
    //     return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    // }

    // public function canEdit($member = null)
    // {
    //     return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    // }

    // public function canDelete($member = null)
    // {
    //     return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    // }

    // public function canCreate($member = null, $context = [])
    // {
    //     return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    // }
}
