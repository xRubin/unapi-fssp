<?php

namespace unapi\fssp\byDocument;

class Request implements RequestInterface
{
    /** @var string */
    private $executionDocument;

    /**
     * @param string $executionDocument
     */
    public function __construct(string $executionDocument)
    {
        $this->executionDocument = $executionDocument;
    }

    /**
     * @return string
     */
    public function getExecutionDocument(): string
    {
        return $this->executionDocument;
    }

    /**
     * @param string $executionDocument
     * @return RequestInterface
     */
    public function setRegionKey(string $executionDocument): RequestInterface
    {
        $this->executionDocument = $executionDocument;
        return $this;
    }
}