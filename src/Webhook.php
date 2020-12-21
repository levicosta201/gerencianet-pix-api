<?php

namespace Gerencianet\Pix;

use Gerencianet\Pix\Core\ProccessCurl;

class Webhook
{
    /**
     * @var
     */
    private $tokenType;

    /**
     * @var
     */
    private $accessToken;

    /**
     * @var
     */
    private $certfile;

    /**
     * @var
     */
    private $webhookUrl;

    /**
     * @var
     */
    private $key;

    public function create()
    {
        return ProccessCurl::runWebhook(Constants::URL_WEBHOOK_PROD . '/' . $this->getKey(), $this->getCertfile(), $this->getWebhookUrl(), $this->getAccessToken(), $this->getTokenType());
    }

    /**
     * @return mixed
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param mixed $tokenType
     */
    public function setTokenType($tokenType): self
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCertfile()
    {
        return $this->certfile;
    }

    /**
     * @param mixed $certfile
     */
    public function setCertfile($certfile): self
    {
        $this->certfile = $certfile;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getWebhookUrl()
    {
        return $this->webhookUrl;
    }

    /**
     * @param mixed $webhookUrl
     */
    public function setWebhookUrl($webhookUrl): self
    {
        $this->webhookUrl = $webhookUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): self
    {
        $this->key = $key;
        return $this;
    }



}
