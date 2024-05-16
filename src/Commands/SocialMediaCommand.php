<?php

namespace Goldfinch\SocialMedia\Commands;

use Goldfinch\Taz\Console\GeneratorCommand;
use Goldfinch\Taz\Services\Templater;

#[AsCommand(name: 'vendor:social-media:templates')]
class SocialMediaCommand extends GeneratorCommand
{
    protected static $defaultName = 'vendor:social-media:templates';

    protected $description = 'Publish [goldfinch/social-media] templates';

    protected $no_arguments = true;

    protected function execute($input, $output): int
    {
        $templater = Templater::create($input, $output, $this, 'goldfinch/social-media');

        $theme = $templater->defineTheme();

        if (is_string($theme)) {
            $componentPathTemplates = BASE_PATH.'/vendor/goldfinch/social-media/templates/';
            $componentPath = $componentPathTemplates.'Goldfinch/Component/Products/';
            $themeTemplates = 'themes/'.$theme.'/templates/';
            $themePath = $themeTemplates.'Goldfinch/Component/Products/';

            $files = [
                [
                    'from' => $componentPathTemplates.'Views/SocialFeed.ss',
                    'to' => $themeTemplates.'Views/SocialFeed.ss',
                ],
                [
                    'from' => $componentPathTemplates.'Views/FacebookFeed.ss',
                    'to' => $themeTemplates.'Views/FacebookFeed.ss',
                ],
                [
                    'from' => $componentPathTemplates.'Views/InstagramFeed.ss',
                    'to' => $themeTemplates.'Views/InstagramFeed.ss',
                ],
            ];

            return $templater->copyFiles($files);
        } else {
            return $theme;
        }
    }
}
