<?php

namespace Goldfinch\SocialKit\Extensions;

use Carbon\Carbon;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use LeKoala\Encrypt\EncryptHelper;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\ORM\DataExtension;
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

class SiteConfigExtension extends DataExtension
{
    use HasEncryptedFields;

    private static $db = [
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

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Configurations', [

            CompositeField::create(

                CheckboxField::create('MetaFacebook', 'Meta Facebook'),
                Wrapper::create(

                    LiteralField::create('MetaFacebookRef', 'refer to <a href="https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived/" target="_blank">developers.facebook.com/docs</a><br/><br/>'),

                    TextField::create('MetaFacebookAppSecret', 'App Secret'),

                    TextareaField::create('MetaFacebookAccessToken', 'Access Token'),

                    TextareaField::create('MetaFacebookLongAccessToken', 'Long-Lived Access Token'),

                    TextField::create('MetaFacebookAppId', 'App ID'),

                    TextField::create('MetaFacebookPageId', 'Page ID'),

                    TextareaField::create('MetaFacebookFields', 'Fields'),

                    TextField::create('MetaFacebookLimit', 'Limit'),

                    FieldGroup::create(

                      DatetimeField::create('MetaFacebookLongAccessTokenLastRefresh', 'Last Long-Lived Access Token Refresh')->setReadonly(true),
                      LiteralField::create('MetaFacebookAcceLongssTokenLastRefresh_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 20px"><span class="btn__title">Refresh</span></a>'),
                      DatetimeField::create('MetaFacebookLastSync', 'Last Posts Sync')->setReadonly(true),
                      LiteralField::create('MetaFacebookLastSync_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 20px"><span class="btn__title">Sync</span></a>'),

                    )->setDescription(
                      ($this->owner->MetaFacebookLongAccessTokenLastRefresh ? ('Token refreshed ' . Carbon::parse($this->owner->MetaFacebookLongAccessTokenLastRefresh)->diffForHumans() . '') : '')
                      .
                      ($this->owner->MetaFacebookLastSync ? ('Posts synced ' . Carbon::parse($this->owner->MetaFacebookLastSync)->diffForHumans() . '') : '')
                      .
                      ($this->owner->MetaFacebookAccessTokenExpiresIn ? 'Current token expires in ' . Carbon::parse($this->owner->MetaFacebookAccessTokenExpiresIn)->diffForHumans() . '' : '')
                    ),

                )->displayIf('MetaFacebook')->isChecked()->end(),

            ),

            CompositeField::create(

                CheckboxField::create('MetaInstagram', 'Meta Instagram'),
                Wrapper::create(

                    LiteralField::create('MetaFacebookRef', 'refer to <a href="https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived/" target="_blank">developers.facebook.com/docs</a><br/><br/>'),

                    TextField::create('MetaInstagramAppSecret', 'App Secret'),

                    TextareaField::create('MetaInstagramAccessToken', 'Access Token'),

                    TextareaField::create('MetaInstagramLongAccessToken', 'Long-Lived Access Token'),

                    TextareaField::create('MetaInstagramFields', 'Fields'),

                    TextField::create('MetaInstagramLimit', 'Limit'),

                    FieldGroup::create(

                      DatetimeField::create('MetaInstagramLongAccessTokenLastRefresh', 'Last Long-Lived Access Token Refresh')->setReadonly(true),
                      LiteralField::create('MetaInstagramLongAccessTokenLastRefresh_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 20px"><span class="btn__title">Refresh</span></a>'),
                      DatetimeField::create('MetaInstagramLastSync', 'Last Posts Sync')->setReadonly(true),
                      LiteralField::create('MetaInstagramLastSync_Btn', '<a href="#" class="btn action btn-primary font-icon-sync" style="margin-bottom: 20px"><span class="btn__title">Sync</span></a>'),

                    )->setDescription(
                      ($this->owner->MetaInstagramLongAccessTokenLastRefresh ? ('Token refreshed ' . Carbon::parse($this->owner->MetaInstagramLongAccessTokenLastRefresh)->diffForHumans() . '') : '')
                      .
                      ($this->owner->MetaInstagramLastSync ? ('Posts synced ' . Carbon::parse($this->owner->MetaInstagramLastSync)->diffForHumans() . '') : '')
                      .
                      ($this->owner->MetaInstagramAccessTokenExpiresIn ? 'Current token expires in ' . Carbon::parse($this->owner->MetaInstagramAccessTokenExpiresIn)->diffForHumans() . '' : '')
                    ),

                )->displayIf('MetaInstagram')->isChecked()->end(),

            ),

        ]);

        // Set Encrypted Data
        $this->nestEncryptedData($fields);
    }

    public function validate(ValidationResult $validationResult)
    {
        // $validationResult->addError('Error message');
    }

    protected function nestEncryptedData(FieldList $fields)
    {
        foreach($this::$db as $name => $type)
        {
            if (EncryptHelper::isEncryptedField(get_class($this->owner), $name))
            {
                $this->owner->$name = $this->owner->dbObject($name)->getValue();
            }
        }
    }
}
