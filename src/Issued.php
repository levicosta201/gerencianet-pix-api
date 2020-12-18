<?php

namespace Gerencianet\Pix;

use Gerencianet\Pix\Core\ProccessCurl;

class Issued
{
    /**
     * @var
     */
    private $filterCpf;

    /**
     * @var
     */
    private $filterCnpj;

    /**
     * @var
     */
    private $filterStatus;

    /**
     * @var
     */
    private $paginate;

    /**
     * @var
     */
    private $itensPerPage;

    /**
     * @var
     */
    private $initDate;

    /**
     * @var
     */
    private $endDate;

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
    private $certFile;

    public function list()
    {
        $mountFilter = Helper::mountFilter(
            self::getInitDate(),
            self::getEndDate(),
            self::getFilterCpf(),
            self::getFilterCnpj(),
            self::getPaginate(),
            self::getItensPerPage()
        );

        return ProccessCurl::runCurlListIssued(Constants::URL_PIX_PROD, $mountFilter, $this->getCertFile(), $this->getTokenType(), $this->getAccessToken());

    }

    /**
     * @return mixed
     */
    public function getFilterCpf()
    {
        return $this->filterCpf;
    }

    /**
     * @param mixed $filterCpf
     */
    public function setFilterCpf($filterCpf): self
    {
        $this->filterCpf = $filterCpf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterCnpj()
    {
        return $this->filterCnpj;
    }

    /**
     * @param mixed $filterCnpj
     */
    public function setFilterCnpj($filterCnpj): self
    {
        $this->filterCnpj = $filterCnpj;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterStatus()
    {
        return $this->filterStatus;
    }

    /**
     * @param mixed $filterStatus
     */
    public function setFilterStatus($filterStatus): self
    {
        $this->filterStatus = $filterStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaginate()
    {
        return $this->paginate;
    }

    /**
     * @param mixed $paginate
     */
    public function setPaginate($paginate): self
    {
        $this->paginate = $paginate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItensPerPage()
    {
        return $this->itensPerPage;
    }

    /**
     * @param mixed $itensPerPage
     */
    public function setItensPerPage($itensPerPage): self
    {
        $this->itensPerPage = $itensPerPage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInitDate()
    {
        return $this->initDate;
    }

    /**
     * @param mixed $initDate
     */
    public function setInitDate($initDate): self
    {
        $this->initDate = $initDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): self
    {
        $this->endDate = $endDate;
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
    public function getCertFile()
    {
        return $this->certFile;
    }

    /**
     * @param mixed $certFile
     */
    public function setCertFile($certFile): self
    {
        $this->certFile = $certFile;
        return $this;
    }

}
