<?php

namespace unapi\fssp\ip\requests;

class ByExecutionRequest implements ByExecutionRequestInterface
{
    /** @var string */
    private $executionNumber;

    /**
     * @param string $executionNumber
     */
    public function __construct(string $executionNumber)
    {
        $this->executionNumber = $executionNumber;
    }

    /**
     * @return string
     */
    public function getExecutionNumber(): string
    {
        return $this->executionNumber;
    }
}