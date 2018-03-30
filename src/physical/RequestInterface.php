<?php

namespace unapi\fssp\physical;

use unapi\helper\fullname\FullName;
use DateTimeInterface;

interface RequestInterface
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