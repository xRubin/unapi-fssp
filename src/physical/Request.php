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
     * RequestDto constructor.
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
     * @return Request
     */
    public function setRegionKey(string $regionKey): Request
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
     * @return Request
     */
    public function setFullName(FullName $fullName): Request
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
     * @return Request
     */
    public function setBirthDate(DateTimeInterface $birthDate): Request
    {
        $this->birthDate = $birthDate;
        return $this;
    }
}