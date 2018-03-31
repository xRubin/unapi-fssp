<?php

namespace unapi\fssp\ip\requests;

interface ByLegalRequestInterface extends RequestInterface
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