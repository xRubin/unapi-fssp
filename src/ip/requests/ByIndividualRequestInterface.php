<?php

namespace unapi\fssp\ip\requests;

use unapi\helper\fullname\FullName;
use DateTimeInterface;

interface ByIndividualRequestInterface extends RequestInterface
{
    /**
     * @return string
     */
    public function getRegionKey(): string;

    /**
     * @return FullName
     */
    public function getFullName(): FullName;

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthDate(): ?DateTimeInterface;
}