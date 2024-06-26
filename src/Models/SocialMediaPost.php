<?php

namespace Goldfinch\SocialMedia\Models;

use Carbon\Carbon;
use Goldfinch\SocialMedia\Configs\SocialMediaConfig;
use PhpTek\JSONText\ORM\FieldType\JSONText;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\View\ArrayData;

class SocialMediaPost extends DataObject
{
    private static $table_name = 'SocialMediaPost';

    private static $db = [
        'Type' => 'Enum("facebook,instagram", "facebook")',
        'PostID' => 'Varchar',
        'PostDate' => 'Datetime',
        'Data' => JSONText::class,
    ];

    private static $summary_fields = [
        'summaryThumbnail' => 'Image',
        'summarySummary' => 'Text',
        'postType' => 'Post Type',
        'postDateAgo' => 'Posted at',
        'Type' => 'Type',
    ];

    private static $default_sort = 'PostDate DESC';

    public function summaryThumbnail()
    {
        $img = $this->postImage();

        $link =
            '<a onclick="window.open(\''.
            $this->postLink().
            '\');" href="'.
            $this->postLink().
            '" target="_blank">';

        if ($img) {
            $img =
                $link.
                '<img class="action-menu__toggle" src="'.
                $img.
                '" alt="Post image" width="140" height="140" style="object-fit: cover" /></a>';
        } else {
            $img = $link.'(no image)</a>';
        }

        $html = DBHTMLText::create();
        $html->setValue($img);

        return $html;
    }

    public function summarySummary()
    {
        $str = DBText::create();
        $str->setValue($this->postText());

        return $str->LimitCharacters(200);
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

        $cfg = SocialMediaConfig::current_config();

        if ($this->isInstagram()) {
            if ($dr->media_type == 'VIDEO') {
                $return = $dr->thumbnail_url;
            } else {
                $return = $dr->media_url;
            }
        } elseif ($this->isFacebook()) {
            $return = $dr->full_picture;
        }

        if ($return) {
            // ! avoid using ` && is_array(@getimagesize($return))`, creates bottlenecks
            return $return;
        } elseif ($cfg->DefaultPostImage()->exists()) {
            return $cfg->DefaultPostImage()->getURL();
        } else {
            return null;
        }
    }

    public function postLink()
    {
        $dr = $this->postData();

        if ($this->isInstagram()) {
            return $dr->permalink;
        } elseif ($this->isFacebook()) {
            return $dr->permalink_url;
        }
    }

    public function postText()
    {
        $dr = $this->postData();

        if ($this->isInstagram()) {
            return $this->instagramParser($dr->caption);
        } elseif ($this->isFacebook()) {
            return $dr->message;
        }
    }

    protected function instagramParser($text)
    {
        $cfg = $this->config();

        if ($cfg->get('post_tag_link')) {
            $text = preg_replace(
                '/#([\w.]+)/u',
                '<a href="https://www.instagram.com/explore/tags/$1/" target="_blank">#$1</a>',
                $text
            );
        }

        if ($cfg->get('post_at_link')) {
            $text = preg_replace(
                '/@([\w.]+)/u',
                '<a href="https://www.instagram.com/$1/" target="_blank">@$1</a>',
                $text
            );
        }

        if ($cfg->get('post_links')) {
            $text = preg_replace(
                "#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is",
                '\\1<a href="\\2" target="_blank" rel="nofollow">\\2</a>',
                $text
            );
        }

        if ($cfg->get('post_no_break')) {
            $text = str_replace(PHP_EOL, '', $text);
        }

        return $text;
    }

    public function postDate($format = 'Y-m-d H:i:s')
    {
        $dr = $this->postData();

        if ($this->isInstagram()) {
            return Carbon::parse($dr->timestamp)
                ->timezone(date_default_timezone_get())
                ->format($format);
        } elseif ($this->isFacebook()) {
            return Carbon::parse($dr->created_time)
                ->timezone(date_default_timezone_get())
                ->format($format);
        }
    }

    public function postDateAgo()
    {
        $dr = $this->postData();

        if ($this->isInstagram()) {
            return Carbon::parse($dr->timestamp)
                ->timezone(date_default_timezone_get())
                ->diffForHumans();
        } elseif ($this->isFacebook()) {
            return Carbon::parse($dr->created_time)
                ->timezone(date_default_timezone_get())
                ->diffForHumans();
        }
    }

    public function postType()
    {
        $dr = $this->postData();

        if ($this->isInstagram()) {
            return $dr->media_type;
        } elseif ($this->isFacebook()) {
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

        if ($this->isInstagram()) {
            return false;
        } elseif ($this->isFacebook()) {
            if ($dr->message_tags) {
                return new ArrayList($dr->message_tags);
            }
        }

        return false;
    }

    public function postCounter($type)
    {
        $dr = $this->postData();

        if ($this->isInstagram()) {
            return;
        } elseif ($this->isFacebook()) {
            if ($type == 'comments' && $dr->comments) {
                return $dr->comments->summary->total_count;
            } elseif ($type == 'likes' && $dr->likes) {
                return $dr->likes->summary->total_count;
            } elseif ($type == 'shares' && $dr->shares) {
                return $dr->shares->count;
            }
        }

    }
}
