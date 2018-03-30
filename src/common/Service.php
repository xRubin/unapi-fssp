<?php

namespace unapi\fssp\common;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use unapi\anticaptcha\common\AnticaptchaInterface;
use unapi\interfaces\ServiceInterface;

abstract class Service implements ServiceInterface, LoggerAwareInterface
{
    /** @var Client */
    private $client;
    /** @var AnticaptchaInterface */
    private $anticaptcha;
    /** @var LoggerInterface */
    private $logger;
    /** @var string */
    private $responseClass = Execution::class;

    protected const URL = 'https://fssprus.ru/iss/ip/';
    protected const AJAX_URL = 'http://is.fssprus.ru/ajax_search';

    /**
     * @param array $config Service configuration settings.
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['client'])) {
            $this->client = new Client();
        } elseif ($config['client'] instanceof ClientInterface) {
            $this->client = $config['client'];
        } else {
            throw new \InvalidArgumentException('Client must be instance of ClientInterface');
        }

        if (!isset($config['logger'])) {
            $this->logger = new NullLogger();
        } elseif ($config['logger'] instanceof LoggerInterface) {
            $this->setLogger($config['logger']);
        } else {
            throw new \InvalidArgumentException('Logger must be instance of LoggerInterface');
        }

        if (!isset($config['anticaptcha'])) {
            throw new \InvalidArgumentException('Anticaptcha required');
        } elseif ($config['anticaptcha'] instanceof AnticaptchaInterface) {
            $this->anticaptcha = $config['anticaptcha'];
        } else {
            throw new \InvalidArgumentException('Anticaptcha must be instance of AnticaptchaInterface');
        }

        if (isset($config['responseClass']))
            $this->responseClass = $config['responseClass'];
    }

    /**
     * @inheritdoc
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
    /**
     * @return AnticaptchaInterface
     */
    public function getAnticaptcha(): AnticaptchaInterface
    {
        return $this->anticaptcha;
    }

    /**
     * @param ClientInterface $client
     * @return PromiseInterface
     */
    protected function initialPage(ClientInterface $client)
    {
        return $client->requestAsync('GET', self::URL);
    }

    /**
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    protected function parseResponse(ResponseInterface $response): PromiseInterface
    {
        $body = $response->getBody()->getContents();

        if (!preg_match("/(.*)Найдено записей: <b>(\d*)<\/b>(.*)/is", $body, $matches))
            return new FulfilledPromise([]);

        if (!preg_match('/jQuery17207971608308143914_1444115108291\((.*)\);/is', $body, $matches))
            return new RejectedPromise('Parse error');

        $response = json_decode($matches[1]);

        if (!$response->data)
            return new RejectedPromise('Parse error');

        $result = [];
        $dom = new \DOMDocument;
        $dom->loadHTML(mb_convert_encoding($response->data, 'HTML-ENTITIES', "UTF-8"));
        $responseClass = $this->responseClass;

        foreach ($dom->getElementsByTagName('tr') as $execution) {
            /** @var \DOMElement $execution */
            if ($columns = $execution->getElementsByTagName('td')) {
                $data = [];
                foreach ($columns as $column) {
                    /** @var \DOMElement $column */
                    $data[] = $column->textContent;
                }
                if (count($data) === 8)
                    $result[] = $responseClass::toDto($data[0], $data[1], $data[2], $data[3], $data[5], $data[6], $data[7]);
            }
        }

        return new FulfilledPromise($result);
    }
}