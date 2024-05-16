<?php

namespace Goldfinch\SocialMedia\Blocks;

use DNADesign\Elemental\Models\BaseElement;
use Goldfinch\Helpers\Traits\BaseElementTrait;

class SocialMediaBlock extends BaseElement
{
    use BaseElementTrait;

    private static $table_name = 'SocialMediaBlock';

    private static $singular_name = 'Social Media';

    private static $plural_name = 'Social Media';

    private static $db = [
        'FeedType' => 'Enum("facebook,instagram,mixed", "mixed")',
        'FeedLimit' => 'Int',
    ];

    private static $inline_editable = false;

    private static $description = 'Social Media block handler';

    private static $icon = 'font-icon-block-instagram';

    private static $field_labels = [
        'FeedType' => 'Type',
        'FeedLimit' => 'Post limit',
    ];
}
