<?php

namespace SocialiteProviders\IPSCommunity;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

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
        return $this->buildAuthUrlFromBase('https://forums.aurorastation.org/oauth/authorize', $state); //TODO: Make this link configurable
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://forums.aurorastation.org/oauth/token/'; //TODO: Make this link configurable
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://forums.aurorastation.org/api/core/me', [ //TODO: Make this link configurable
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $response_data = json_decode($response->getBody(), true);

        $response2 = $this->getHttpClient()->get('https://forums.aurorastation.org/api/core/me/email', [ //TODO: Make this link configurable
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $response2_data = json_decode($response2->getBody(), true);

        return array_merge($response_data, $response2_data);
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
            'linkedAccounts'    => $user['customFields'][3]['fields'], //TODO: Make this id configurable
            'primaryGroup'      => $user['primaryGroup']['name'],
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
