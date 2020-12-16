<?php

namespace Gerencianet\Pix;

use Gerencianet\Pix\Core\ProccessCurl;

class Connect
{
    /**
     * Path of cert file Gerencianet (Only Path)
     * @var string
     */
    private $certFile;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $environment;


    /**
     * Connect constructor.
     * @param string $environment
     */
    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Function only return Auth Token
     * @return array
     */
    public function proccess(): array
    {
        $response = ProccessCurl::runCurl(Constants::URL_AUTH_PROD, $this->getClientId(), $this->getClientSecret(), $this->getCertFile());
        return [
            'data' => [
                'accessToken' => $response->data->access_token,
                'tokenType' => $response->data->token_type,
            ]
        ];
    }

    /**
     * @return string
     */
    public function getCertFile(): string
    {
        return $this->certFile;
    }

    /**
     * @param string $certFile
     */
    public function setCertFile(string $certFile): self
    {
        $this->certFile = $certFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }


}