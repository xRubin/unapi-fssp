<?php

namespace unapi\fssp\legal;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getRegionKey(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return null|string
     */
    public function getAddress(): ?string;
}