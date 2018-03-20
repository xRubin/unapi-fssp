<?php

namespace unapi\fssp\common;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\AnticaptchaInterface;
use unapi\anticaptcha\common\AnticaptchaServiceInterface;
use unapi\anticaptcha\common\task\ImageTask;

class Anticaptcha implements AnticaptchaInterface
{
    /** @var AnticaptchaServiceInterface */
    private $service;

    public function __construct(AnticaptchaServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param ClientInterface $client
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function getAnticaptchaPromise(ClientInterface $client, ResponseInterface $response): PromiseInterface
    {
        preg_match('/src=\\\"data:image\/jpeg;base64,(.*)\\\"(.*)/isU', $response->getBody()->getContents(), $matches);

        if (!array_key_exists(1, $matches))
            throw new \RuntimeException('Captcha not found');

        return $this->service->resolve(new ImageTask([
            'body' => base64_decode($matches[1]),
            'numeric' => ImageTask::NUMERIC_DEFAULT,
            'minLength' => 5,
            'maxLength' => 6
        ]));
    }
}