<?php

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use unapi\fssp\physical\Service;
use unapi\fssp\common\Client;
use unapi\fssp\physical\Request;
use unapi\helper\fullname\FullName;
use unapi\fssp\common\Execution;
use unapi\anticaptcha\common\AnticaptchaInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedDto;
use GuzzleHttp\Promise\FulfilledPromise;

class ServicePhysicalTest extends TestCase
{
    public function testExecution()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], ''), // as initial page
            new Response(200, [], file_get_contents(__DIR__ . '/responses/service-physical-captcha')),
            new Response(200, [], file_get_contents(__DIR__ . '/responses/service-physical-data')),
        ]));

        $anticaptcha = $this->createMock(AnticaptchaInterface::class);
        $anticaptcha
            ->method('getAnticaptchaPromise')
            ->willReturn(
                new FulfilledPromise(
                    new CaptchaSolvedDto('00000')
                )
            );

        $service = new Service([
            'client' => new Client(['handler' => $handler]),
            'anticaptcha' => $anticaptcha,
        ]);

        /** @var Execution[] $executions */
        $executions = $service->getExecutions(new Request(67, new FullName('Анжелика', 'Гинзбург', 'Иосифовна')))->wait();

        $this->assertInternalType('array', $executions);
        $this->assertCount(2, $executions);
        $this->assertEquals(new Execution(
            'ГИНЗБУРГ АНЖЕЛИКА ИОСИФОВНА 13.12.1971 , РОССИЯ, , , Г. СМОЛЕНСК, , , , ,',
            '65478/15/67032-ИП от 12.10.2015',
            'Исполнительный лист от 24.08.2015 № ФС 006239960ЛЕНИНСКИЙ РАЙОННЫЙ СУД Г. СМОЛЕНСКА',
            '10.03.2016ст. 46ч. 1п. 4',
            'Задолженность по платежам за жилую площадь, коммунальные платежи, включая пени',
            'Ленинский РОСП г.Смоленска УФССП России по Смоленской области214029, Смоленский р-н, Смоленская обл., г. Смоленск, ш. Краснинское, д. 35',
            'КОЛОМЕЙЦЕВ А. В.84812648898+7(4812)30-74-30+7(4812)30-74-51'
        ), $executions[0]);
    }
}