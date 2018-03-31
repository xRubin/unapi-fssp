<?php

namespace unapi\fssp\ip\requests;

interface ByExecutionRequestInterface extends RequestInterface
{
    /**
     * @return string
     */
    public function getExecutionNumber(): string;
}