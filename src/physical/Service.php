<?php

namespace unapi\fssp\physical;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedInterface;

use function GuzzleHttp\json_decode;

class Service extends \unapi\fssp\common\Service
{
    const URL = 'https://fssprus.ru/iss/ip/';
    const AJAX_URL = 'http://is.fssprus.ru/ajax_search';

    public function getExecutions(RequestInterface $request): PromiseInterface
    {
        return $this->initialPage($this->getClient())->then(function () use ($request) {
            return $this->submitForm($request)->then(function (ResponseInterface $response) use ($request) {
                return $this->getAnticaptcha()->getAnticaptchaPromise($this->getClient(), $response)->then(function (CaptchaSolvedInterface $solved) use ($request) {
                    return $this->submitForm($request, $solved)->then(function (ResponseInterface $response) {
                        return $this->parseResponse($response);
                    });
                });
            });
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
                'is' => [
                    'extended' => 1,
                    'variant' => 1,
                    'region_id' => [
                        0 => $request->getRegionKey()
                    ],
                    'last_name' => $request->getFullName()->getSurname(),
                    'first_name' => $request->getFullName()->getName(),
                    'patronymic' => $request->getFullName()->getPatronymic(),
                    'date' => $request->getBirthdate() ? $request->getBirthdate()->format('d.m.Y') : null,
                ],
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

        foreach ($dom->getElementsByTagName('tr') as $execution) {
            /** @var \DOMElement $execution */
            if ($columns = $execution->getElementsByTagName('td')) {
                $data = [];
                foreach ($columns as $column) {
                    /** @var \DOMElement $column */
                    $data[] = $column->textContent;
                }
                if (count($data) === 8)
                    $result[] = new ResponseExecution($data[0], $data[1], $data[2], $data[3], $data[5], $data[6], $data[7]);
            }
        }

        return new FulfilledPromise($result);
    }
}