<?php

namespace Goldfinch\SocialKit\Models;

use Carbon\Carbon;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use PhpTek\JSONText\ORM\FieldType\JSONText;

class SocialPost extends DataObject
{
    private static $table_name = 'SocialPost';

    private static $db = [
        'Type' => 'Enum("facebook,instagram", "facebook")',
        'PostID' => 'Varchar',
        'PostDate' => 'Datetime',
        'Data' => JSONText::class,
    ];

    private static $default_sort = 'PostDate DESC';

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

        // ..

        return $fields;
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
            return $this->instagramParser($dr->caption);
        }
        else if ($this->isFacebook())
        {
            return $dr->message;
        }
    }

    protected function instagramParser($text)
    {
        $cfg = $this->config();

        if ($cfg->get('post_tag_link'))
        {
            $text = preg_replace('/#([\w.]+)/u', '<a href="https://www.instagram.com/explore/tags/$1/" target="_blank">#$1</a>', $text);
        }

        if ($cfg->get('post_at_link'))
        {
            $text = preg_replace('/@([\w.]+)/u', '<a href="https://www.instagram.com/$1/" target="_blank">@$1</a>', $text);
        }

        if ($cfg->get('post_links'))
        {
            $text = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $text);
        }

        if ($cfg->get('post_no_break'))
        {
            $text = str_replace(PHP_EOL, '', $text);
        }

        return $text;
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
            return $dr->media_type;
        }
        else if ($this->isFacebook())
        {
            return $dr->status_type;
        }
    }

    public function postIconType()
    {
        return $this->renderWith('Partials/IconType');
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
