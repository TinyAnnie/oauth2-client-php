<?php

namespace App\Http\Helpers;

use kamermans\OAuth2\Token\TokenInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class FileTokenPersistence
{
    /**
     * @var string
     */
    private $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public function saveToken(AccessTokenInterface $token)
    {
        file_put_contents($this->filepath, json_encode($token->jsonSerialize()), LOCK_EX);
    }

    public function restoreToken(): ?AccessToken
    {
        if (!file_exists($this->filepath)) {
            return null;
        }

        $data = @json_decode(@file_get_contents($this->filepath), true);

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
        if (file_exists($this->filepath)) {
            @unlink($this->filepath);
        }
    }

    public function hasToken(): bool
    {
        return file_exists($this->filepath);
    }
}
