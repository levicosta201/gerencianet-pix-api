<?php

namespace Gerencianet\Pix;

use Gerencianet\Pix\Core\ProccessCurl;

class Charge
{
    /**
     * Name of who will pay
     * @var string
     */
    private $nameDebtor;

    /**
     * CPF/CNPJ of who will pay
     * @var string
     */
    private $cpfCnpjDebtor;

    /**
     * @var string
     */
    private $cityDebtor;

    /**
     * @var string
     */
    private $cepDebtor;

    /**
     * @var bool
     */
    private $freeValue;

    /**
     * @var int
     */
    private $dimenQrCoode;

    /**
     * @var bool
     */
    private $uniquePay;

    /**
     * Expires time of Qr Code
     * @var string
     */
    private $expiresTimeQrCode;

    /**
     * Charge amount
     * @var float
     */
    private $value;

    /**
     * Service Description
     * @var string
     */
    private $descriptionService;

    /**
     * Access token getting with Connect
     * @var string
     */
    private $accessToken;

    /**
     * Define if is dinamic or static
     * @var string
     */
    private $type;

    /**
     *
     * @var string
     */
    private $environment;

    /**
     * Your Key Pix
     * @var string
     */
    private $keyPix;

    private $tokenType;

    /**
     * Path of cert file Gerencianet (Only Path)
     * @var string
     */
    private $certFile;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function create()
    {
        $randonIdTransaction = Helper::getTaxId($this->getType());

        if ($this->environment === 'production') {
            $pixUrlCob = Constants::URL_PIX_COB_PROD . "/" . $randonIdTransaction;
        } else {
            $pixUrlCob = Constants::URL_PIX_COB_SANDBOX . "/" . $randonIdTransaction;
        }

        $body = Helper::mountBody($this->getKeyPix(), $this->getCpfCnpjDebtor(), $this->getNameDebtor(), $this->getValue(), $this->getDescriptionService(), $this->getExpiresTimeQrCode());

        if ($this->getType() === "dinamico") {
            $dadosPix = ProccessCurl::runCurlCharge($pixUrlCob, $this->getCertFile(), $this->getAccessToken(), json_encode($body), $this->getTokenType(), 'PUT');
        } else {
            $dadosPix = $body;
            $dadosPix["txid"] = $randonIdTransaction;
        }
        return Helper::createBarCode($dadosPix, $this->getType(), $this->isUniquePay(), $this->getNameDebtor(), $this->getCityDebtor(), $this->getCepDebtor(), $this->isFreeValue(), $this->getDimenQrCoode(), $dadosPix['data']['valor']['original']);
    }

    /**
     * @return string
     */
    public function getNameDebtor(): string
    {
        return $this->nameDebtor;
    }

    /**
     * @param string $nameDebtor
     */
    public function setNameDebtor(string $nameDebtor): self
    {
        $this->nameDebtor = $nameDebtor;
        return $this;
    }

    /**
     * @return string
     */
    public function getCpfCnpjDebtor(): string
    {
        return $this->cpfCnpjDebtor;
    }

    /**
     * @param string $cpfCnpjDebtor
     */
    public function setCpfCnpjDebtor(string $cpfCnpjDebtor): self
    {
        $this->cpfCnpjDebtor = $cpfCnpjDebtor;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiresTimeQrCode(): string
    {
        return $this->expiresTimeQrCode;
    }

    /**
     * @param string $expiresTimeQrCode
     */
    public function setExpiresTimeQrCode(int $expiresTimeQrCode): self
    {
        $this->expiresTimeQrCode = $expiresTimeQrCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionService(): string
    {
        return $this->descriptionService;
    }

    /**
     * @param string $descriptionService
     */
    public function setDescriptionService(string $descriptionService): self
    {
        $this->descriptionService = $descriptionService;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * estatico | dinamico
     * @param string $type
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment(string $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getKeyPix(): string
    {
        return $this->keyPix;
    }

    /**
     * @param string $keyPix
     */
    public function setKeyPix(string $keyPix): self
    {
        $this->keyPix = $keyPix;
        return $this;
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
    public function getCityDebtor(): string
    {
        return $this->cityDebtor;
    }

    /**
     * @param string $cityDebtor
     */
    public function setCityDebtor(string $cityDebtor): self
    {
        $this->cityDebtor = $cityDebtor;
        return $this;
    }

    /**
     * @return string
     */
    public function getCepDebtor(): string
    {
        return $this->cepDebtor;
    }

    /**
     * @param string $cepDebtor
     */
    public function setCepDebtor(string $cepDebtor): self
    {
        $this->cepDebtor = $cepDebtor;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFreeValue(): bool
    {
        return $this->freeValue;
    }

    /**
     * @param bool $freeValue
     */
    public function setFreeValue(bool $freeValue): self
    {
        $this->freeValue = $freeValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getDimenQrCoode(): int
    {
        return $this->dimenQrCoode;
    }

    /**
     * @param int $dimenQrCoode
     */
    public function setDimenQrCoode(int $dimenQrCoode): self
    {
        $this->dimenQrCoode = $dimenQrCoode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUniquePay(): bool
    {
        return $this->uniquePay;
    }

    /**
     * @param bool $uniquePay
     */
    public function setUniquePay(bool $uniquePay): self
    {
        $this->uniquePay = $uniquePay;
        return $this;
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
}
