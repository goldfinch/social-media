<?php

namespace Goldfinch\SocialMedia\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use Goldfinch\SocialMedia\Models\SocialMediaPost;
use Goldfinch\SocialMedia\Configs\SocialMediaConfig;

class SocialMeta
{
    /**
     *
     * Graph-API Changelog
     * https://developers.facebook.com/docs/graph-api/changelog/
     *
     * Long live tokens (Facebook)
     * https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived/
     *
     * Fields
     * https://developers.facebook.com/docs/graph-api/reference/v17.0/page/feed
     *
     * Long live tokens (Instagram)
     * https://developers.facebook.com/docs/instagram-basic-display-api/guides/long-lived-access-tokens
     *
     * Fields
     * https://developers.facebook.com/docs/instagram-basic-display-api/reference/media
     *
     */

    const FACEBOOK_GRAPH = 'https://graph.facebook.com/';
    const INSTAGRAM_GRAPH = 'https://graph.instagram.com/';

    protected $facebook = [];
    protected $instagram = [];
    protected $cfg;
    protected $client;

    public function __construct()
    {
        $this->configInit();
        $this->clientInit();
    }

    private function clientInit()
    {
        $this->client = new Client();
    }

    private function configInit()
    {
        $this->cfg = SocialMediaConfig::current_config();

        $this->facebook = [
            'page_id' => $this->cfg->dbObject('MetaFacebookPageId')->getValue(),
            'app_id' => $this->cfg->dbObject('MetaFacebookAppId')->getValue(),
            'app_secret' => $this->cfg->dbObject('MetaFacebookAppSecret')->getValue(),
            'access_token' => $this->cfg->dbObject('MetaFacebookAccessToken')->getValue(),
            'long_access_token' => $this->cfg->dbObject('MetaFacebookLongAccessToken')->getValue(),
            'limit' => $this->cfg->dbObject('MetaFacebookLimit')->getValue(),
            'fields' => $this->cfg->dbObject('MetaFacebookFields')->getValue(),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];

        $this->instagram = [
            'app_secret' => $this->cfg->dbObject('MetaInstagramAppSecret')->getValue(),
            'access_token' => $this->cfg->dbObject('MetaInstagramAccessToken')->getValue(),
            'long_access_token' => $this->cfg->dbObject('MetaInstagramLongAccessToken')->getValue(),
            'limit' => $this->cfg->dbObject('MetaInstagramLimit')->getValue(),
            'fields' => $this->cfg->dbObject('MetaInstagramFields')->getValue(),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];
    }

    public function FacebookFeed()
    {
        if (!$this->cfg->MetaFacebook)
        {
            return;
        }

        if (!$this->facebook['long_access_token'] || !$this->facebook['limit'] || !$this->facebook['fields'] || !$this->facebook['page_id'])
        {
            return $this->returnFailed('Missing configuration', 403);
        }

        try {
            $response = $this->client->request('GET', self::FACEBOOK_GRAPH . $this->facebook['page_id'] . '/feed', [
                'query' => [
                    'access_token' => $this->facebook['long_access_token'],
                    'limit' => $this->facebook['limit'],
                    'fields' => $this->facebook['fields'],
                    // 'published'=> 1,
                ],
                'headers' => $this->facebook['headers'],
            ]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300)
        {
            $feeds = json_decode($response->getBody(), true)['data'];

            $this->cfg->MetaFacebookLastSync = date('Y-m-d H:i:s');
            $this->cfg->write();

            foreach ($feeds as $feed)
            {
                $this->syncPost($feed, 'facebook');
            }

            return $this->returnSuccess(true);

        }
        else
        {
            return $this->returnFailed($response, $response->getStatusCode());
        }
    }

    public function InstagramFeed()
    {
        if (!$this->cfg->MetaInstagram)
        {
            return;
        }

        if (!$this->instagram['long_access_token'] || !$this->instagram['limit'] || !$this->instagram['fields'])
        {
            return $this->returnFailed('Missing configuration', 403);
        }

        try {
            $response = $this->client->request('GET', self::INSTAGRAM_GRAPH . 'me/media', [
                'query' => [
                    'access_token' => $this->instagram['long_access_token'],
                    'limit' => $this->instagram['limit'],
                    'fields' => $this->instagram['fields'],
                ],
                'headers' => $this->instagram['headers'],
            ]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300)
        {
            $feeds = json_decode($response->getBody(), true)['data'];

            $this->cfg->MetaInstagramLastSync = date('Y-m-d H:i:s');
            $this->cfg->write();

            foreach ($feeds as $feed)
            {
                $this->syncPost($feed, 'instagram');
            }

            return $this->returnSuccess(true);
        }
        else
        {
            return $this->returnFailed($response, $response->getStatusCode());
        }
    }

    public function InstagramRefreshLongToken()
    {
        if (!$this->instagram['long_access_token'])
        {
            return $this->returnFailed('Missing credentials', 403);
        }

        try {
            $response = $this->client->request('GET', self::INSTAGRAM_GRAPH . 'refresh_access_token', [
                'query' => [
                    'grant_type' => 'ig_refresh_token',
                    'access_token' => $this->instagram['long_access_token'],
                ],
                'headers' => $this->instagram['headers'],
            ]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300)
        {
            $data = json_decode($response->getBody(), true);

            if ($data['access_token'])
            {
                if (isset($data['expires_in']))
                {
                    $this->cfg->MetaInstagramAccessTokenExpiresIn = Carbon::now()->addSeconds($data['expires_in'])->format('Y-m-d H:i:s');
                }

                $this->cfg->MetaInstagramLongAccessToken = $data['access_token'];
                $this->cfg->MetaInstagramLongAccessTokenLastRefresh = Carbon::now()->format('Y-m-d H:i:s');
                $this->cfg->write();

                return $this->returnSuccess(true);
            }
        }
        else
        {
            return $this->returnFailed($response, $response->getStatusCode());
        }
    }

    public function InstagramGetLongLiveToken()
    {
        if (!$this->instagram['access_token'] || $this->instagram['app_secret'])
        {
            return $this->returnFailed('Missing credentials', 403);
        }

        try {
            $response = $this->client->request('GET', self::INSTAGRAM_GRAPH . 'access_token', [
                'query' => [
                    'grant_type' => 'ig_exchange_token',
                    'client_secret' => $this->instagram['app_secret'],
                    'access_token' => $this->instagram['access_token'],
                ],
                'headers' => $this->instagram['headers'],
            ]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300)
        {
            $data = json_decode($response->getBody(), true);

            if ($data['access_token'])
            {
                if (isset($data['expires_in']))
                {
                    $this->cfg->MetaInstagramAccessTokenExpiresIn = Carbon::now()->addSeconds($data['expires_in'])->format('Y-m-d H:i:s');
                }

                $this->cfg->MetaInstagramLongAccessToken = $data['access_token'];
                $this->cfg->MetaInstagramLongAccessTokenLastRefresh = Carbon::now()->format('Y-m-d H:i:s');
                $this->cfg->write();

                return $this->returnSuccess(true);
            }
        }
        else
        {
            return $this->returnFailed($response, $response->getStatusCode());
        }
    }

    public function FacebookGetAccessToken()
    {
        if (!$this->facebook['page_id'] || !$this->facebook['access_token'])
        {
            return $this->returnFailed('Missing credentials', 403);
        }

        try {
            $response = $this->client->request('GET', self::FACEBOOK_GRAPH . $this->facebook['page_id'], [
                'query' => [
                    'access_token' => $this->facebook['access_token'],
                    'fields' => 'access_token',
                ],
                'headers' => $this->facebook['headers'],
            ]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300)
        {
            $data = json_decode($response->getBody(), true);

            if ($data['access_token'])
            {
                $this->cfg->MetaFacebookAccessToken = $data['access_token'];
                // $this->cfg->MetaFacebookLongAccessToken = $data['access_token'];
                // $this->cfg->MetaFacebookLongAccessTokenLastRefresh = Carbon::now()->format('Y-m-d H:i:s');
                $this->cfg->write();

                $this->facebook['access_token'] = $this->cfg->MetaFacebookAccessToken;

                return $this->returnSuccess(true);
            }
        }
        else
        {
            return $this->returnFailed($response);
        }
    }

    public function FacebookGetLongLiveToken()
    {
        if (!$this->facebook['app_id'] || !$this->facebook['app_secret'] || !$this->facebook['access_token'])
        {
            return $this->returnFailed('Missing credentials', 403);
        }

        // Get access_token through Initial User Access Token (from https://developers.facebook.com/tools/explorer/)
        $this->FacebookGetAccessToken();

        try {
            $response = $this->client->request('GET', self::FACEBOOK_GRAPH . 'oauth/access_token', [
                'query' => [
                    'grant_type' => 'fb_exchange_token',
                    'client_id' => $this->facebook['app_id'],
                    'client_secret' => $this->facebook['app_secret'],
                    'fb_exchange_token' => $this->facebook['access_token'],
                ],
                'headers' => $this->facebook['headers'],
            ]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300)
        {
            $data = json_decode($response->getBody(), true);

            if ($data['access_token'])
            {
                if (isset($data['expires_in']))
                {
                    $this->cfg->MetaFacebookAccessTokenExpiresIn = Carbon::now()->addSeconds($data['expires_in'])->format('Y-m-d H:i:s');
                }

                $this->cfg->MetaFacebookLongAccessToken = $data['access_token'];
                $this->cfg->MetaFacebookLongAccessTokenLastRefresh = Carbon::now()->format('Y-m-d H:i:s');
                $this->cfg->write();

                return $this->returnSuccess(true);
            }
        }
        else
        {
            return $this->returnFailed($response);
        }
    }

    private function syncPost($feed, $type)
    {
        $post = SocialMediaPost::get()->filter([
          'Type' => ucfirst($type),
          'PostID' => $feed['id'],
        ])->first();

        if ($post)
        {
            $post->Data = json_encode($feed);
            $post->write();
        }
        else
        {
            if ($type == 'facebook')
            {
                $date = Carbon::parse($feed['created_time']);
            }
            else if ($type == 'instagram')
            {
                $date = Carbon::parse($feed['timestamp']);
            }
            else
            {
                $date = Carbon::now();
            }

            $post = new SocialMediaPost;
            $post->PostID = $feed['id'];
            $post->PostDate = $date->format('Y-m-d H:i:s');
            $post->Type = $type;
            $post->Data = json_encode($feed);
            $post->write();
        }
    }

    private function returnSuccess($data = null, $code = 200)
    {
        print_r([
            'error' => false,
            'status_code' => $code,
            'data' => $data
        ]);
    }

    private function returnFailed($message = null, $code = 500)
    {
        if($message instanceof Response)
        {
            $message = json_decode($message->getBody()->getContents())->error->message;
            $code = 403;
        }
        else if(is_object($message))
        {
            $code = $message->status();
        }

        print_r([
            'error' => true,
            'status_code' => $code,
            'message' => ($message) ? $message['error']['message'] ?? $message : 'Unexpected error occurred'
        ]);
    }
}
