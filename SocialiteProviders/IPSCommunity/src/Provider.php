<?php

namespace SocialiteProviders\IPSCommunity;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use Illuminate\Support\Facades\Log;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'IPSCOMMUNITY';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['profile'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('aurora.forum_url').'oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return config('aurora.forum_url').'oauth/token/';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('aurora.forum_url').'api/core/me', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $response_data = json_decode($response->getBody(), true);
        $user_id = $response_data["id"];


        $response2 = $this->getHttpClient()->get(config('aurora.forum_url').'api/core/members/'.$user_id.'?key='.config("aurora.ipb_auth"), [ //TODO: Make this link configurable

        ]);

        $response2_data = json_decode($response2->getBody(), true);
        Log::debug('login.datafetch - Fetched Userdata', ['user_id' => $user_id, 'userdata' => $response2_data]);

        return $response2_data;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'                => $user['id'],
            'formattedName'     => $user['formattedName'],
            'nickname'          => $user['name'],
            'email'             => $user['email'],
            'avatar'            => $user['photoUrl'],
            'linkedAccounts'    => $user['customFields'][3]['fields'], //TODO: Make this (and additional mappings) configurable
            'primaryGroup'      => $user['primaryGroup'],
            'secondaryGroups'   => (array_key_exists('secondaryGroups',$user)) ? $user['secondaryGroups'] : []
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
