<?php

namespace unapi\fssp\legal;

class Request implements RequestInterface
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
     * @param string $regionKey
     * @return RequestInterface
     */
    public function setRegionKey(string $regionKey): RequestInterface
    {
        $this->regionKey = $regionKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return RequestInterface
     */
    public function setName(string $name): RequestInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $address
     * @return RequestInterface
     */
    public function setAddress(string $address): RequestInterface
    {
        $this->address = $address;
        return $this;
    }
}