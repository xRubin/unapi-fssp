<?php

namespace unapi\fssp\physical;

use unapi\helper\fullname\FullName;
use DateTimeInterface;

interface RequestInterface
{
    public function getRegionKey(): string;
    public function getFullName(): FullName;
    public function getBirthDate(): ?DateTimeInterface;
}