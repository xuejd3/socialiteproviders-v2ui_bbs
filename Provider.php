<?php

namespace SocialiteProviders\V2uiBbs;

use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const URL = 'https://bbs.v2ui.com';

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(self::URL.'/oauth/authorize', $state);
    }

    protected function getTokenUrl(): string
    {
        return self::URL.'/oauth/access_token';
    }

    protected function getRequestOptions($token): array
    {
        return [
            RequestOptions::HEADERS => [
                // 'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => 'Bearer '.$token,
            ],
        ];
    }

    protected function getUserByToken($token)
    {
        $userUrl = self::URL.'/api/user';

        $response = $this->getHttpClient()->get(
            $userUrl,
            $this->getRequestOptions($token)
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['id'],
            'nickname' => null,
            'name'     => $user['name'],
            'phone'    => $user['phone'],
            'email'    => $user['email'],
            'avatar'   => $user['avatar'],
        ]);
    }
}
