<?php

namespace Goldfinch\SocialMedia\Configs;

use SilverStripe\AssetAdmin\Forms\UploadField;
use Carbon\Carbon;
use SilverStripe\Assets\Image;
use JonoM\SomeConfig\SomeConfig;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use LeKoala\Encrypt\EncryptHelper;
use SilverStripe\Forms\FieldGroup;
use LeKoala\Encrypt\EncryptedDBText;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\ORM\ValidationResult;
use LeKoala\Encrypt\EncryptedDBVarchar;
use LeKoala\Encrypt\HasEncryptedFields;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\View\TemplateGlobalProvider;

class SocialMediaConfig extends DataObject implements TemplateGlobalProvider
{
    use HasEncryptedFields, SomeConfig;

    private static $table_name = 'SocialMediaConfig';

    private static $db = [
        'GeneralFacebook' => 'Boolean',
        'GeneralFacebookURL' => 'Varchar',
        'GeneralInstagram' => 'Boolean',
        'GeneralInstagramURL' => 'Varchar',
        'GeneralTwitter' => 'Boolean',
        'GeneralTwitterURL' => 'Varchar',
        'GeneralLinkedIn' => 'Boolean',
        'GeneralLinkedInURL' => 'Varchar',
        'GeneralYouTube' => 'Boolean',
        'GeneralYouTubeURL' => 'Varchar',

        'MetaFacebook' => 'Boolean',
        'MetaFacebookLastSync' => 'Datetime',
        'MetaFacebookLongAccessTokenLastRefresh' => 'Datetime',
        'MetaFacebookAccessTokenExpiresIn' => 'Varchar',
        'MetaFacebookAccessToken' => EncryptedDBText::class,
        'MetaFacebookLongAccessToken' => EncryptedDBText::class,
        'MetaFacebookAppSecret' => EncryptedDBVarchar::class,
        'MetaFacebookAppId' => EncryptedDBVarchar::class,
        'MetaFacebookPageId' => EncryptedDBVarchar::class,
        'MetaFacebookFields' => EncryptedDBText::class,
        'MetaFacebookLimit' => EncryptedDBVarchar::class,

        'MetaInstagram' => 'Boolean',
        'MetaInstagramLastSync' => 'Datetime',
        'MetaInstagramLongAccessTokenLastRefresh' => 'Datetime',
        'MetaInstagramAccessTokenExpiresIn' => 'Varchar',
        'MetaInstagramAccessToken' => EncryptedDBText::class,
        'MetaInstagramLongAccessToken' => EncryptedDBText::class,
        'MetaInstagramAppSecret' => EncryptedDBVarchar::class,
        'MetaInstagramFields' => EncryptedDBText::class,
        'MetaInstagramLimit' => EncryptedDBVarchar::class,
    ];

    private static $has_one = [
        'DefaultPostImage' => Image::class,
    ];

    private static $owns = [
        'DefaultPostImage',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'GeneralFacebook',
            'GeneralFacebookURL',
            'GeneralInstagram',
            'GeneralInstagramURL',
            'GeneralTwitter',
            'GeneralTwitterURL',
            'GeneralLinkedIn',
            'GeneralLinkedInURL',
            'GeneralYouTube',
            'GeneralYouTubeURL',

            'MetaFacebook',
            'MetaFacebookLastSync',
            'MetaFacebookLongAccessTokenLastRefresh',
            'MetaFacebookAccessTokenExpiresIn',
            'MetaFacebookAccessToken',
            'MetaFacebookLongAccessToken',
            'MetaFacebookAppSecret',
            'MetaFacebookAppId',
            'MetaFacebookPageId',
            'MetaFacebookFields',
            'MetaFacebookLimit',

            'MetaInstagram',
            'MetaInstagramLastSync',
            'MetaInstagramLongAccessTokenLastRefresh',
            'MetaInstagramAccessTokenExpiresIn',
            'MetaInstagramAccessToken',
            'MetaInstagramLongAccessToken',
            'MetaInstagramAppSecret',
            'MetaInstagramFields',
            'MetaInstagramLimit',
        ]);

        $fields->addFieldsToTab('Root.API', [

            UploadField::create(
              'DefaultPostImage',
              'Default post image',
            )->setDescription('for posts that do not have an image, or by some reason return nothing'),

            CompositeField::create(

                CheckboxField::create('MetaFacebook', 'Facebook API'),
                Wrapper::create(

                    LiteralField::create('MetaFacebookRef', 'refer to <a href="https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived/" target="_blank">developers.facebook.com/docs</a><br/><br/>'),

                    TextField::create('MetaFacebookAppSecret', 'App Secret')->setDescription('Get the key in <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Apps</a> / App settings / Basic'),

                    TextareaField::create('MetaFacebookAccessToken', 'Access Token')->setDescription('<a href="https://developers.facebook.com/tools/explorer/">Get access token</a> with `pages_manage_posts` permission'),

                    TextareaField::create('MetaFacebookLongAccessToken', 'Long-Lived Access Token')->setDescription('To get long-lived token use task'),

                    TextField::create('MetaFacebookAppId', 'App ID')->setDescription('Get ID in <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Apps</a>'),

                    TextField::create('MetaFacebookPageId', 'Page ID')->setDescription('<a href="https://www.facebook.com/help/1503421039731588" target="_blank">Find your Facebook Page ID</a>'),

                    TextareaField::create('MetaFacebookFields', 'Fields')->setDescription('Get the full <a href="https://developers.facebook.com/docs/graph-api/reference/v18.0/page/feed#readfields" target="_blank">list of fields here</a>'),

                    TextField::create('MetaFacebookLimit', 'Limit'),

                    FieldGroup::create(

                      DatetimeField::create('MetaFacebookLongAccessTokenLastRefresh', 'Last Long-Lived Access Token Refresh')->setReadonly(true),
                      LiteralField::create('MetaFacebookAcceLongssTokenLastRefresh_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 23px;height: 38px;padding-top: 8px;"><span class="btn__title">Refresh</span></a>'),
                      DatetimeField::create('MetaFacebookLastSync', 'Last Posts Sync')->setReadonly(true),
                      LiteralField::create('MetaFacebookLastSync_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 23px;height: 38px;padding-top: 8px;"><span class="btn__title">Sync</span></a>'),

                    )->setDescription(
                      ($this->MetaFacebookLongAccessTokenLastRefresh ? ('Token refreshed ' . Carbon::parse($this->MetaFacebookLongAccessTokenLastRefresh)->diffForHumans() . '') : '<div></div>')
                      .
                      ($this->MetaFacebookLastSync ? ('Posts synced ' . Carbon::parse($this->MetaFacebookLastSync)->diffForHumans() . '') : '<div></div>')
                      .
                      ($this->MetaFacebookAccessTokenExpiresIn ? 'Current token expires in ' . Carbon::parse($this->MetaFacebookAccessTokenExpiresIn)->diffForHumans() . '' : '')
                    ),

                )->displayIf('MetaFacebook')->isChecked()->end(),

            ),

            CompositeField::create(

                CheckboxField::create('MetaInstagram', 'Instagram API'),
                Wrapper::create(

                    LiteralField::create('MetaFacebookRef', 'refer to <a href="https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived/" target="_blank">developers.facebook.com/docs</a><br/><br/>'),

                    TextField::create('MetaInstagramAppSecret', 'App Secret')->setDescription('Get the key in <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Apps</a> / <strong>Instagram Basic Display</strong>'),

                    TextareaField::create('MetaInstagramAccessToken', 'Access Token'),

                    TextareaField::create('MetaInstagramLongAccessToken', 'Long-Lived Access Token')->setDescription('Get the key in <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Apps</a> / <strong>Instagram Basic Display</strong> / <strong>User Token Generator</strong>'),

                    TextareaField::create('MetaInstagramFields', 'Fields')->setDescription('Get the full <a href="https://developers.facebook.com/docs/instagram-basic-display-api/reference/media/#fields" target="_blank">list of fields here</a>'),

                    TextField::create('MetaInstagramLimit', 'Limit'),

                    FieldGroup::create(

                      DatetimeField::create('MetaInstagramLongAccessTokenLastRefresh', 'Last Long-Lived Access Token Refresh')->setReadonly(true),
                      LiteralField::create('MetaInstagramLongAccessTokenLastRefresh_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 23px;height: 38px;padding-top: 8px;"><span class="btn__title">Refresh</span></a>'),
                      DatetimeField::create('MetaInstagramLastSync', 'Last Posts Sync')->setReadonly(true),
                      LiteralField::create('MetaInstagramLastSync_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 23px;height: 38px;padding-top: 8px;"><span class="btn__title">Sync</span></a>'),

                    )->setDescription(
                      ($this->MetaInstagramLongAccessTokenLastRefresh ? ('Token refreshed ' . Carbon::parse($this->MetaInstagramLongAccessTokenLastRefresh)->diffForHumans() . '') : '<div></div>')
                      .
                      ($this->MetaInstagramLastSync ? ('Posts synced ' . Carbon::parse($this->MetaInstagramLastSync)->diffForHumans() . '') : '<div></div>')
                      .
                      ($this->MetaInstagramAccessTokenExpiresIn ? 'Current token expires in ' . Carbon::parse($this->MetaInstagramAccessTokenExpiresIn)->diffForHumans() . '' : '')
                    ),

                )->displayIf('MetaInstagram')->isChecked()->end(),

            )

        ]);

        $fields->dataFieldByName('DefaultPostImage')->setFolderName('social-media');

        $fields->addFieldsToTab('Root.Main', [

            CompositeField::create(

                CheckboxField::create('GeneralFacebook', 'Facebook'),
                TextField::create('GeneralFacebookURL', '')->setAttribute('placeholder', 'https://facebook.com/...')->displayIf('GeneralFacebook')->isChecked()->end(),

            )->setName('GeneralFacebookHolder'),

            CompositeField::create(

                CheckboxField::create('GeneralInstagram', 'Instagram'),
                TextField::create('GeneralInstagramURL', '')->setAttribute('placeholder', 'https://www.instagram.com/...')->displayIf('GeneralInstagram')->isChecked()->end(),

            )->setName('GeneralInstagramHolder'),

            CompositeField::create(

                CheckboxField::create('GeneralTwitter', 'Twitter'),
                TextField::create('GeneralTwitterURL', '')->setAttribute('placeholder', 'https://twitter.com/...')->displayIf('GeneralTwitter')->isChecked()->end(),

            )->setName('GeneralTwitterHolder'),

            CompositeField::create(

                CheckboxField::create('GeneralLinkedIn', 'LinkedIn'),
                TextField::create('GeneralLinkedInURL', '')->setAttribute('placeholder', 'https://www.linkedin.com/...')->displayIf('GeneralLinkedIn')->isChecked()->end(),

            )->setName('GeneralLinkedInHolder'),

            CompositeField::create(

                CheckboxField::create('GeneralYouTube', 'YouTube'),
                TextField::create('GeneralYouTubeURL', '')->setAttribute('placeholder', 'https://www.youtube.com/...')->displayIf('GeneralYouTube')->isChecked()->end(),

            )->setName('GeneralYouTubeHolder'),

            ]);//->findTab('Root.Main')->setTitle('General links');

        // Set Encrypted Data
        $this->nestEncryptedData($fields);

        return $fields;
    }

    protected function nestEncryptedData(FieldList &$fields)
    {
        foreach($this::$db as $name => $type)
        {
            if (EncryptHelper::isEncryptedField(get_class($this->owner), $name))
            {
                $this->$name = $this->dbObject($name)->getValue();
            }
        }
    }
}
