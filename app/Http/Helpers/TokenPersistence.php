<?php

namespace App\Http\Helpers;

use Psr\SimpleCache\CacheInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\SimpleCache\InvalidArgumentException;

class TokenPersistence
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $cacheKey;

    public function __construct(CacheInterface $cache, $cacheKey = 'guzzle-oauth2-token')
    {
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
    }

    public function saveToken(AccessTokenInterface $token)
    {
        $this->cache->forever($this->cacheKey, $token->jsonSerialize());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function restoreToken(): ?AccessToken
    {
        $data = $this->cache->get($this->cacheKey);

        if (!is_array($data)) {
            return null;
        }
        return new AccessToken([
            'access_token' => $data['access_token'],
            'expires' => $data['expires']
        ]);
    }

    public function deleteToken()
    {
        $this->cache->forget($this->cacheKey);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hasToken(): bool
    {
        return $this->cache->has($this->cacheKey);
    }
}
