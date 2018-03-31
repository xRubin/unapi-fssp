<?php

namespace unapi\fssp\ip\requests;

interface ByDocumentRequestInterface extends RequestInterface
{
    /**
     * @return string
     */
    public function getRegionKey(): string;

    /**
     * @return string
     */
    public function getExecutionDocument(): string;
}