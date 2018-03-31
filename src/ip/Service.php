<?php

namespace unapi\fssp\ip;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use unapi\anticaptcha\common\AnticaptchaInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedInterface;
use unapi\fssp\common\Client;
use unapi\fssp\ip\requests\RequestInterface;
use unapi\interfaces\ServiceInterface;

class Service implements ServiceInterface, LoggerAwareInterface
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
     * @param RequestInterface $request
     * @return PromiseInterface
     */
    public function findExecutions(RequestInterface $request): PromiseInterface
    {
        return $this->initialPage($this->getClient())->then(function () use ($request) {
            return $this->submitForm($request);
        })->then(function (ResponseInterface $response) {
            return $this->getAnticaptcha()->getAnticaptchaPromise($this->getClient(), $response);
        })->then(function (CaptchaSolvedInterface $solved) use ($request) {
            return $this->submitForm($request, $solved);
        })->then(function (ResponseInterface $response) {
            return $this->parseResponse($response);
        });
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
     * @param RequestInterface $request
     * @param CaptchaSolvedInterface|null $captcha
     * @return PromiseInterface
     */
    protected function submitForm(RequestInterface $request, CaptchaSolvedInterface $captcha = null): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', self::AJAX_URL, [
            'query' => [
                'callback' => 'jQuery17207971608308143914_1444115108291',
                'system' => 'ip',
                'is' => FormFactory::getForm($request),
                'nocache' => 1,
                '_' => '1444115160353',
                'code' => $captcha ? $captcha->getCode() : null,
            ]
        ]);
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
                    $data[] = implode(PHP_EOL, array_filter(
                        array_map('trim', explode(PHP_EOL, preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $this->getDomElementHtml($column))))
                    ));
                }
                if (count($data) === 8)
                    $result[] = $responseClass::toDto($data[0], $data[1], $data[2], $data[3], $data[5], $data[6], $data[7]);
            }
        }

        return new FulfilledPromise($result);
    }

    /**
     * @param \DOMElement $element
     * @return string
     */
    private function getDomElementHtml(\DOMElement $element): string
    {
        $innerHTML = '';
        foreach ($element->childNodes as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }
}