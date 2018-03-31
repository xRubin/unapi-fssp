<?php

use unapi\fssp\ip\Service;
use unapi\fssp\common\Client;
use unapi\fssp\ip\requests;
use unapi\fssp\ip\Execution;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Promise\FulfilledPromise;
use PHPUnit\Framework\TestCase;
use unapi\helper\fullname\FullName;
use unapi\anticaptcha\common\AnticaptchaInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedDto;

class IpServiceTest extends TestCase
{
    protected function getAnticaptcha(): AnticaptchaInterface
    {
        /** @var AnticaptchaInterface|\PHPUnit\Framework\MockObject\MockObject $anticaptcha */
        $anticaptcha = $this->createMock(AnticaptchaInterface::class);
        $anticaptcha
            ->method('getAnticaptchaPromise')
            ->willReturn(
                new FulfilledPromise(
                    new CaptchaSolvedDto('00000')
                )
            );

        return $anticaptcha;
    }

    protected function getService(HandlerStack $handler)
    {
        return new Service([
            'client' => new Client(['handler' => $handler]),
            'anticaptcha' => $this->getAnticaptcha(),
        ]);
    }

    public function testFindExecutionsByIndividual()
    {
        $service = $this->getService(
            HandlerStack::create(
                new MockHandler([
                    new Response(200, [], ''), // as initial page
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/common/service-captcha')),
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/ip/service-individual-data')),
                ])
            )
        );

        /** @var Execution[] $executions */
        $executions = $service->findExecutions(
            new requests\ByIndividualRequest(67, new FullName('Анжелика', 'Гинзбург', 'Иосифовна'))
        )->wait();

        $this->assertInternalType('array', $executions);
        $this->assertCount(2, $executions);
        $this->assertEquals(new Execution(
            'ГИНЗБУРГ АНЖЕЛИКА ИОСИФОВНА' . PHP_EOL . '13.12.1971' . PHP_EOL . ', РОССИЯ, , , Г. СМОЛЕНСК, , , , ,',
            '65478/15/67032-ИП от 12.10.2015',
            'Исполнительный лист от 24.08.2015 № ФС 006239960' . PHP_EOL . 'ЛЕНИНСКИЙ РАЙОННЫЙ СУД Г. СМОЛЕНСКА',
            '10.03.2016' . PHP_EOL . 'ст. 46' . PHP_EOL . 'ч. 1' . PHP_EOL . 'п. 4',
            'Задолженность по платежам за жилую площадь, коммунальные платежи, включая пени',
            'Ленинский РОСП г.Смоленска УФССП России по Смоленской области' . PHP_EOL . '214029, Смоленский р-н, Смоленская обл., г. Смоленск, ш. Краснинское, д. 35',
            'КОЛОМЕЙЦЕВ А. В.' . PHP_EOL . '84812648898' . PHP_EOL . '+7(4812)30-74-30' . PHP_EOL . '+7(4812)30-74-51'
        ), $executions[0]);
    }

    public function testFindExecutionsByLegal()
    {
        $service = $this->getService(
            HandlerStack::create(
                new MockHandler([
                    new Response(200, [], ''), // as initial page
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/common/service-captcha')),
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/ip/service-legal-data')),
                ])
            )
        );

        /** @var Execution[] $executions */
        $executions = $service->findExecutions(
            new requests\ByLegalRequest(67, 'Газпром')
        )->wait();

        $this->assertInternalType('array', $executions);
        $this->assertCount(6, $executions);
        $this->assertEquals(new Execution(
            'ООО ГАЗПРОМ ТРАНСГАЗ САНКТ-ПЕТЕРБУРГ,' . PHP_EOL . 'РОССИЯ,, , ,СМОЛЕНСК Г, ,ИНДУСТРИАЛЬНАЯ УЛ,8,,',
            '43714/15/67036-ИП от 21.09.2015',
            'Акт по делу об административном правонарушении от 28.03.2015 № 18810167150328002862' . PHP_EOL . 'ЦАФАП  ГИБДД УМВД РОССИИ ПО СМОЛЕНСКОЙ ОБЛАСТИ',
            '30.11.2016' . PHP_EOL . 'ст. 46' . PHP_EOL . 'ч. 1' . PHP_EOL . 'п. 4',
            'Штраф ГИБДД',
            'Промышленный РОСП г.Смоленска УФССП России по Смоленской области' . PHP_EOL . '214029, Смоленская область, г. Смоленск, Краснинское шоссе, д. 35',
            'ГУЖАЕВА Ю. В.' . PHP_EOL . '74812382455' . PHP_EOL . '+7(4812)30-74-76' . PHP_EOL . '+7(4812)30-74-49'
        ), $executions[0]);
    }

    public function testFindExecutionsByExecution()
    {
        $service = $this->getService(
            HandlerStack::create(
                new MockHandler([
                    new Response(200, [], ''), // as initial page
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/common/service-captcha')),
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/ip/service-execution-data')),
                ])
            )
        );

        /** @var Execution[] $executions */
        $executions = $service->findExecutions(
            new requests\ByExecutionRequest('76735/17/67036-ИП')
        )->wait();

        $this->assertInternalType('array', $executions);
        $this->assertCount(1, $executions);
        $this->assertEquals(new Execution(
            'ООО "ГАЗПРОМ МЕЖРЕГИОНГАЗ СМОЛЕНСК",' . PHP_EOL . 'РОССИЯ,214019, , ,СМОЛЕНСК Г, ,ИСАКОВСКОГО УЛ,28,,',
            '76735/17/67036-ИП от 21.09.2017',
            'Исполнительный лист от 09.03.2017 № ФС 015507071' . PHP_EOL . 'АРБИТРАЖНЫЙ СУД СМОЛЕНСКОЙ ОБЛАСТИ',
            '30.11.2017' . PHP_EOL . 'ст. 46' . PHP_EOL . 'ч. 1' . PHP_EOL . 'п. 3',
            'Госпошлина, присужденная судом',
            'Промышленный РОСП г.Смоленска УФССП России по Смоленской области' . PHP_EOL . '214029, Смоленская область, г. Смоленск, Краснинское шоссе, д. 35',
            'АБРАМОВИЧ М. А.' . PHP_EOL . '8 (4812) 307476' . PHP_EOL . '+7(4812)30-74-76' . PHP_EOL . '+7(4812)30-74-49'
        ), $executions[0]);
    }

    public function testFindExecutionsByDocument()
    {
        $service = $this->getService(
            HandlerStack::create(
                new MockHandler([
                    new Response(200, [], ''), // as initial page
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/common/service-captcha')),
                    new Response(200, [], file_get_contents(__DIR__ . '/responses/ip/service-document-data')),
                ])
            )
        );

        /** @var Execution[] $executions */
        $executions = $service->findExecutions(
            new requests\ByDocumentRequest(67, 'ФС 015507071')
        )->wait();

        $this->assertInternalType('array', $executions);
        $this->assertCount(1, $executions);
        $this->assertEquals(new Execution(
            'ООО "ГАЗПРОМ МЕЖРЕГИОНГАЗ СМОЛЕНСК",' . PHP_EOL . 'РОССИЯ,214019, , ,СМОЛЕНСК Г, ,ИСАКОВСКОГО УЛ,28,,',
            '76735/17/67036-ИП от 21.09.2017',
            'Исполнительный лист от 09.03.2017 № ФС 015507071' . PHP_EOL . 'АРБИТРАЖНЫЙ СУД СМОЛЕНСКОЙ ОБЛАСТИ',
            '30.11.2017' . PHP_EOL . 'ст. 46' . PHP_EOL . 'ч. 1' . PHP_EOL . 'п. 3',
            'Госпошлина, присужденная судом',
            'Промышленный РОСП г.Смоленска УФССП России по Смоленской области' . PHP_EOL . '214029, Смоленская область, г. Смоленск, Краснинское шоссе, д. 35',
            'АБРАМОВИЧ М. А.' . PHP_EOL . '8 (4812) 307476' . PHP_EOL . '+7(4812)30-74-76' . PHP_EOL . '+7(4812)30-74-49'
        ), $executions[0]);
    }
}