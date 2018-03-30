<?php

namespace unapi\fssp\physical;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedInterface;

class Service extends \unapi\fssp\common\Service
{
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
}