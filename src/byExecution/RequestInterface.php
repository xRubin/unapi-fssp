<?php

namespace unapi\fssp\byExecution;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getExecutionNumber(): string;
}