<?php

namespace unapi\fssp\ip\requests;

class ByDocumentRequest implements ByDocumentRequestInterface
{
    /** @var string */
    private $regionKey;
    /** @var string */
    private $executionDocument;

    /**
     * @param string $regionKey
     * @param string $executionDocument
     */
    public function __construct(string $regionKey, string $executionDocument)
    {
        $this->regionKey = $regionKey;
        $this->executionDocument = $executionDocument;
    }

    /**
     * @return string
     */
    public function getRegionKey(): string
    {
        return $this->regionKey;
    }

    /**
     * @return string
     */
    public function getExecutionDocument(): string
    {
        return $this->executionDocument;
    }
}