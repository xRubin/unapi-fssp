<?php

namespace unapi\fssp\byExecution;

class Request implements RequestInterface
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

    /**
     * @param string $executionNumber
     * @return RequestInterface
     */
    public function setRegionKey(string $executionNumber): RequestInterface
    {
        $this->executionNumber = $executionNumber;
        return $this;
    }
}