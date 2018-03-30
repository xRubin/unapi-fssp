<?php

namespace unapi\fssp\physical;

use unapi\helper\fullname\FullName;
use DateTimeInterface;

class Request implements RequestInterface
{
    /** @var string */
    private $regionKey;
    /** @var FullName */
    private $fullName;
    /** @var \DateTimeInterface */
    private $birthDate;

    /**
     * @param string $regionKey
     * @param FullName $fullName
     * @param \DateTimeInterface $birthDate
     */
    public function __construct(string $regionKey, FullName $fullName, DateTimeInterface $birthDate = null)
    {
        $this->regionKey = $regionKey;
        $this->fullName = $fullName;
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getRegionKey(): string
    {
        return $this->regionKey;
    }

    /**
     * @param string $regionKey
     * @return RequestInterface
     */
    public function setRegionKey(string $regionKey): RequestInterface
    {
        $this->regionKey = $regionKey;
        return $this;
    }

    /**
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName $fullName
     * @return RequestInterface
     */
    public function setFullName(FullName $fullName): RequestInterface
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param DateTimeInterface $birthDate
     * @return RequestInterface
     */
    public function setBirthDate(DateTimeInterface $birthDate): RequestInterface
    {
        $this->birthDate = $birthDate;
        return $this;
    }
}