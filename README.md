[![Build Status](https://travis-ci.org/xRubin/unapi-fssp.svg?branch=master)](https://travis-ci.org/xRubin/unapi-fssp)
# Unapi FSSP
Модуль для работы с сервисами [Федеральной службы судебных приставов](http://fssprus.ru)
Являтся частью библиотеки [Unapi](https://github.com/xRubin/unapi)
Для прохождения капчи нужен любой модуль, реализующий **unapi\anticaptcha\common\AnticaptchaInterface**, например [Unapi Antigate](https://github.com/xRubin/unapi-anticaptcha-antigate)

### Подключение к банку данных исполнительных производств
    <?php
    use unapi\fssp\common\Anticaptcha;
    use unapi\fssp\ip\Service;
    use unapi\fssp\ip\requests;

    $service = new Service([
      'anticaptcha' => new Anticaptcha(new AntigateService([...]),
    ]);

### Поиск исполнительных производств по физику
Сервис требует указания региона для поиска.
Справочник регионов есть в [unapi\fssp\ip\RegionSelector](https://github.com/xRubin/unapi-fssp/blob/master/src/ip/RegionSelector.php).

    <?php
    /** @var Execution[] $executions */
    $executions = $service->findExecutions(
      new requests\ByIndividualRequest(67, new FullName('Анжелика', 'Гинзбург', 'Иосифовна'))
    )->wait();

### Поиск исполнительных производств по юридическому лицу
Сервис требует указания региона для поиска.

    <?php
    /** @var Execution[] $executions */
    $executions = $service->findExecutions(
      new requests\ByLegalRequest(67, 'Газпром')
    )->wait();

### Поиск исполнительных производств по номеру исполнительного производства
    <?php
    /** @var Execution[] $executions */
    $executions = $service->findExecutions(
      new requests\ByExecutionRequest('76735/17/67036-ИП')
    )->wait();

### Поиск исполнительных производств по номеру исполнительного документа
Сервис требует указания региона для поиска.

    <?php
    /** @var Execution[] $executions */
    $executions = $service->findExecutions(
      new requests\ByDocumentRequest(67, 'ФС 015507071')
    )->wait();