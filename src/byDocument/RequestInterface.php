<?php

namespace unapi\fssp\byDocument;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getExecutionDocument(): string;
}