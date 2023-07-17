<?php

namespace Goldfinch\SocialKit\Controllers;

use SilverStripe\Security\Security;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\Permission;
use Goldfinch\SocialKit\Services\SocialMeta;

class ApiMetaController extends Controller
{
    private static $url_segment = 'api/meta';

    private static $allowed_actions = [
        'metaGetLongLivedToken',
        'metaRefreshLongToken',
        'metaSyncPosts',
    ];

    private static $url_handlers = [
        '$Platform/get-long-token' => 'metaGetLongLivedToken',
        '$Platform/token-refresh' => 'metaRefreshLongToken',
        '$Platform/sync' => 'metaSyncPosts',
    ];

    protected $smService;

    public function init()
    {
        parent::init();

        $member = Security::getCurrentUser();

        if (!Permission::check('ADMIN', 'any', $member)) {
            exit;
        }

        $this->smService = new SocialMeta;
    }

    public function metaGetLongLivedToken(HTTPRequest $request)
    {
        if ($request->param('Platform') == 'facebook')
        {
            $this->smService->FacebookGetLongLiveToken();
        }
        else if ($request->param('Platform') == 'instagram')
        {
            $this->smService->InstagramGetLongLiveToken();
        }
    }

    public function metaRefreshLongToken(HTTPRequest $request)
    {
        if ($request->param('Platform') == 'facebook')
        {
            // just get a new long-lived token
            $this->metaGetLongLivedToken($request);
        }
        else if ($request->param('Platform') == 'instagram')
        {
            $this->smService->InstagramRefreshLongToken();
        }
    }

    public function metaSyncPosts(HTTPRequest $request)
    {
        if ($request->param('Platform') == 'facebook')
        {
            $this->smService->FacebookFeed();
        }
        else if ($request->param('Platform') == 'instagram')
        {
            $this->smService->InstagramFeed();
        }
    }
}
