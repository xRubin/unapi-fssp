<?php

namespace unapi\fssp\ip\requests;

use unapi\helper\fullname\FullName;
use DateTimeInterface;

class ByIndividualRequest implements ByIndividualRequestInterface
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
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * @return DateTimeInterface
     */
    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }
}