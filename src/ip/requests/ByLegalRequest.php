<?php

namespace unapi\fssp\ip\requests;

class ByLegalRequest implements ByLegalRequestInterface
{
    /** @var string */
    private $regionKey;
    /** @var string */
    private $name;
    /** @var string */
    private $address;

    /**
     * @param string $regionKey
     * @param string $name
     * @param string|null $address
     */
    public function __construct(string $regionKey, string $name, string $address = null)
    {
        $this->regionKey = $regionKey;
        $this->name = $name;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->name;
    }
}