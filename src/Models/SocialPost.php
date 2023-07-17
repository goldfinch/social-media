<?php

namespace Goldfinch\SocialKit\Models;

use Carbon\Carbon;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use PhpTek\JSONText\ORM\FieldType\JSONText;

class SocialPost extends DataObject
{
    private static $singular_name = null;

    private static $plural_name = null;

    private static $table_name = 'SocialPost';

    private static $cascade_deletes = [];

    private static $cascade_duplicates = [];

    private static $db = [
        'Type' => 'Enum("facebook,instagram", "facebook")',
        'PostID' => 'Varchar',
        'PostDate' => 'Datetime',
        'Data' => JSONText::class,
    ];

    private static $casting = [];

    private static $indexes = null;

    private static $defaults = [];

    private static $has_one = [];
    private static $belongs_to = [];
    private static $has_many = [];
    private static $many_many = [];
    private static $many_many_extraFields = [];
    private static $belongs_many_many = [];

    private static $default_sort = 'PostDate DESC';

    private static $searchable_fields = null;

    private static $field_labels = [];

    private static $summary_fields = [];

    public function validate()
    {
        $result = parent::validate();

        // $result->addError('Error message');

        return $result;
    }

    public function onBeforeWrite()
    {
        // ..

        parent::onBeforeWrite();
    }

    public function onBeforeDelete()
    {
        // ..

        parent::onBeforeDelete();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        //

        return $fields;
    }

    public function SchemaData()
    {
        // Spatie\SchemaOrg\Schema
    }

    public function OpenGraph()
    {
        // Astrotomic\OpenGraph\OpenGraph
    }

    public function isInstagram()
    {
        return $this->Type == 'instagram';
    }

    public function isFacebook()
    {
        return $this->Type == 'facebook';
    }

    public function postData()
    {
        return new ArrayData($this->dbObject('Data')->getStoreAsArray());
    }

    public function postImage()
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            if ($dr->media_type == 'VIDEO')
            {
                return $dr->thumbnail_url;
            }
            else
            {
                return $dr->media_url;
            }
        }
        else if ($this->isFacebook())
        {
            return $dr->full_picture;
        }
    }

    public function postLink()
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            return $dr->permalink;
        }
        else if ($this->isFacebook())
        {
            return $dr->permalink_url;
        }
    }

    public function postText()
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            return $dr->caption;
        }
        else if ($this->isFacebook())
        {
            return $dr->message;
        }
    }

    public function postDate($format = 'Y-m-d H:i:s')
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            return Carbon::parse($dr->timestamp)->timezone(date_default_timezone_get())->format($format);
        }
        else if ($this->isFacebook())
        {
            return Carbon::parse($dr->created_time)->timezone(date_default_timezone_get())->format($format);
        }
    }

    public function postDateAgo()
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            return Carbon::parse($dr->timestamp)->timezone(date_default_timezone_get())->diffForHumans();
        }
        else if ($this->isFacebook())
        {
            return Carbon::parse($dr->created_time)->timezone(date_default_timezone_get())->diffForHumans();
        }
    }

    public function postType()
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            if ($dr->media_type == 'VIDEO')
            {
                return 'video';
            }
            else if ($dr->media_type == 'IMAGE')
            {
                return 'image';
            }
            else
            {
                return $dr->media_type;
            }
        }
        else if ($this->isFacebook())
        {
            if ($dr->status_type == 'added_video')
            {
                return 'video';
            }
            else if ($dr->status_type == 'added_photos')
            {
                return 'image';
            }
            else
            {
                return $dr->status_type;
            }
        }
    }

    public function postTags()
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            return false;
        }
        else if ($this->isFacebook())
        {
            if ($dr->message_tags)
            {
                return new ArrayList($dr->message_tags);
            }
        }

        return false;
    }

    public function postCounter($type)
    {
        $dr = $this->postData();

        if ($this->isInstagram())
        {
            return;
        }
        else if ($this->isFacebook())
        {
            if ($type == 'comments' && $dr->comments)
            {
                return $dr->comments->summary->total_count;
            }
            else if ($type == 'likes' && $dr->likes)
            {
                return $dr->likes->summary->total_count;
            }
            else if ($type == 'shares' && $dr->shares)
            {
                return $dr->shares->count;
            }
        }

        return;
    }
}
