<?php

namespace unapi\fssp;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedInterface;
use unapi\fssp\dto\FsspPhysicalSearchDtoInterface;

/**
 * Class FsspPhysicalService
 */
class FsspPhysicalService extends FsspCommonService
{
    const URL = 'https://fssprus.ru/iss/ip/';
    const AJAX_URL = 'http://is.fssprus.ru/ajax_search';

    public function getExecutions(FsspPhysicalSearchDtoInterface $searchDto): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', self::URL)->then(function (ResponseInterface $response) use ($searchDto) {
            return $this->getClient()->requestAsync('GET', self::AJAX_URL, [
                'query' => [
                    'callback' => 'jQuery17207971608308143914_1444115108291',
                    'system' => 'ip',
                    'is' => [
                        'extended' => 1,
                        'variant' => 1,
                        'region_id' => [
                            0 => $searchDto->getRegion()->getKey()
                        ],
                        'last_name' => $searchDto->getFullName()->getLastName(),
                        'first_name' => $searchDto->getFullName()->getFirstName(),
                        'patronymic' => $searchDto->getFullName()->getPatronymic(),
                        'date' => $searchDto->getBirthdate()->format('d.m.Y'),
                    ],
                    'nocache' => 1,
                    '_' => '1444115160353'
                ]
            ])->then(function(ResponseInterface $response) {
                return $this->getAnticaptcha()->getAnticaptchaPromise($this->getClient(), $response)->then(function (CaptchaSolvedInterface $solved) {



                    return $this->submitForm($this->getClient(), $passport, $solved)->then(function (ResponseInterface $response) {
                        return $this->statusFactory->factory($response);
                    });
                });
            });
        });
    }
}