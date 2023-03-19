<?php

/**
 * Класс Bitrix24API. Выполняет запросы к REST API системы Битрикс24 с использованием механизма входящих вебхуков.
 *
 * @author    andrey-tech
 * @copyright 2019-2023 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.7.0
 *
 * v1.0.0 (13.10.2019) Начальный релиз
 * v1.1.0 (31.10.2019) Добавлен метод getLastResponse()
 * v1.2.0 (04.11.2019) Добавлено свойство $debugLevel
 * v1.2.1 (07.11.2019) Имя файла лога перенесено в свойство $debugFileName
 * v1.2.2 (09.11.2019) В метод to JSON добавлен параметр prettyPrint; добавлено свойство $enableDebugLog
 * v1.2.3 (11.11.2019) Удалено свойство $enableDebugLog
 * v1.2.4 (17.11.2019) Изменен формат логирования запросов и ответов
 * v1.2.5 (25.11.2019) Теперь свойства debugLogger и http публичные
 * v1.2.6 (03.12.2019) Теперь метод request() публичный
 * v1.3.0 (09.06.2020) Изменен метод логирования запросов и ответов, добавлен метод fetchList()
 * v1.3.1 (15.06.2020) Исправлено логирование ответа
 * v1.3.2 (23.01.2021) Исправлены сообщения об ошибках
 * v1.4.0 (03.02.2021) Добавлены свойства класса, задающие имена полей связанных сущностей
 * v1.5.0 (06.02.2021) Изменения для классов: HTTP 3.0 и DebugLogger 2.0; добавлен метод setLogger()
 * v1.6.0 (20.02.2021) Добавлены трейты Lead, ProductRow
 * v1.7.0 (19.03.2023) В методы добавлены параметры dataExtractor и idKey
 *
 */

declare(strict_types=1);

namespace App\Bitrix24;

use App\HTTP\HTTP;
use App\HTTP\HTTPException;
use App\DebugLogger\DebugLoggerInterface;
use Generator;

class Bitrix24API
{
    use Company;
    use Contact;
    use Deal;
    use Product;
    use Catalog;
    use ProductSection;
    use User;
    use Disk;
    use Activity;
    use Task;
    use Lead;
    use ProductRow;

    /**
     * Имя поля для массива связанных сущностей типа контакт
     *
     * @var string
     */
    public static $WITH_CONTACTS = 'CONTACTS';

    /**
     * Имя поля для массива связанных сущностей типа компания
     *
     * @var string
     */
    public static $WITH_COMPANIES = 'COMPANIES';

    /**
     * Имя поля для массива связанных сущностей типа товар
     *
     * @var string
     */
    public static $WITH_PRODUCTS = 'PRODUCTS';

    /**
     * Объект класса, выполняющего логирование
     *
     * @var DebugLoggerInterface
     */
    public $logger;

    /**
     * Количество команд в одном пакете запросов (batch)
     *
     * @var integer
     */
    public $batchSize = 50;

    /**
     * Объект класса, выполняющего HTTP-запросы
     *
     * @var HTTP
     */
    public $http;

    /**
     * URL входящего вебхука
     *
     * @var string
     */
    protected $webhookUrl;

    /**
     * Последний ответ от API Битрикс24
     *
     * @var mixed
     */
    protected $lastResponse;

    /**
     * Конструктор
     *
     * @param string $webhookUrl URL входящего вебхука
     */
    public function __construct(string $webhookUrl)
    {
        // Нормализация URL для / в конце
        $this->webhookUrl = rtrim($webhookUrl, '/');

        $this->http = new HTTP();
        // Не более 2-х запросов в секунду (https://dev.1c-bitrix.ru/rest_help/rest_sum/index.php)
        $this->http->throttle = 2;
        $this->http->useCookies = false;
    }

    /**
     * Устанавливает объект класса, выполняющего логирование
     *
     * @param DebugLoggerInterface $logger
     */
    public function setLogger(DebugLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Возвращает последний ответ от API Битрикс24
     *
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Отправляет запрос в API системы Битрикс24
     *
     * @param string $function Имя метода (функции) запроса
     * @param array $params Параметры запроса
     * @param callable $dataExtractor Функция для извлечения данных из результатов запроса
     *
     * @return array|int|null
     *
     * @throws Bitrix24APIException
     *
     * @throws HTTPException
     */
    public function request(string $function, array $params = [], callable $dataExtractor = null)
    {
        $function .= '.json';
        $url = $this->webhookUrl . '/' . $function;

        // Логирование запроса
        if (isset($this->logger)) {
            $jsonParams = urldecode($this->toJSON($params, true));
            $this->logger->save(
                sprintf('ЗАПРОС: %s%s%s', $function, PHP_EOL, $jsonParams),
                $this
            );
        }

        // POST запрос
        $this->lastResponse = $this->http->request($url, 'POST', $params);

        // Логирование ответа
        if (isset($this->logger)) {
            $jsonResponse = $this->toJSON($this->lastResponse, true);
            $this->logger->save(
                sprintf('ОТВЕТ: %s%s%s', $function, PHP_EOL, $jsonResponse),
                $this
            );
        }

        // Проверка кода состояния HTTP
        if (! $this->http->isSuccess()) {
            $httpCode = $this->http->getHTTPCode();
            $jsonParams = $this->toJSON($params);
            $jsonResponse = $this->toJSON($this->lastResponse);
            throw new Bitrix24APIException(
                sprintf(
                    "Ошибка: HTTP код %s при запросе '%s' (%s): %s",
                    $httpCode,
                    $function,
                    $jsonParams,
                    $jsonResponse
                )
            );
        }

        // Проверка наличия ошибок в ответе
        if (! empty($this->lastResponse['error']) || ! empty($this->lastResponse['error_description'])) {
            $jsonParams = $this->toJSON($params);
            $jsonResponse = $this->toJSON($this->lastResponse);
            throw new Bitrix24APIException(
                sprintf(
                    "Ошибка при запросе '%s' (%s): %s",
                    $function,
                    $jsonParams,
                    $jsonResponse
                )
            );
        }

        return $dataExtractor ? $dataExtractor($this->lastResponse['result']) : $this->lastResponse['result'];
    }

    /**
     * Возвращает список всех сущностей
     *
     * @param string $function Имя метода (функции) запроса
     * @param array $params Параметры запроса
     * @param callable $dataExtractor Функция для извлечения данных из результатов запроса
     *
     * @return Generator
     *
     * @throws Bitrix24APIException
     * @throws HTTPException
     *
     * @see https://dev.1c-bitrix.ru/rest_help/general/lists.php Особенности списочных методов и пакетного метода Batch
     */
    public function getList(string $function, array $params = [], callable $dataExtractor = null): Generator
    {
        do {
            // До 50 штук на 1 запрос
            $result = $this->request(
                $function,
                $params,
                $dataExtractor
            );

            $start = $params['start'] ?? 0;
            if ($this->logger) {
                $this->logger->save(
                    sprintf(
                        "По запросу (getList) %s (start: %d) получено сущностей: %u, всего существует: %u",
                        $function,
                        $start,
                        count($result),
                        $this->lastResponse['total']
                    ),
                    $this
                );
            }

            yield $result;

            if (empty($this->lastResponse['next'])) {
                break;
            }

            $params['start'] = $this->lastResponse['next'];

        } while (true);
    }

    /**
     * Возвращает список всех сущностей используя быстрый метод
     *
     * @param string $function Имя метода (функции) запроса
     * @param array $params Параметры запроса
     * @param callable $dataExtractor Функция для извлечения данных из результатов запроса
     * @param string $idKey Имя поля ID сущности ('ID' или 'id')
     *
     * @return Generator
     *
     * @throws Bitrix24APIException
     * @throws HTTPException
     *
     * @see https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php Как правильно выгружать большие объемы данных
     */
    public function fetchList(
        string $function,
        array $params = [],
        callable $dataExtractor = null,
        string $idKey = 'ID'
    ): Generator  {

        $moreIdKey = '>' . $idKey;

        $params['order'][ $idKey ] = 'ASC';
        $params['filter'][ $moreIdKey ] = 0;
        $params['start'] = -1;

        $totalCounter = 0;

        do {
            // До 50 штук на 1 запрос
            $result = $this->request(
                $function,
                $params,
                $dataExtractor
            );

            $resultCounter = count($result);
            $totalCounter += $resultCounter;
            if ($this->logger) {
                $this->logger->save(
                    sprintf(
                        "По запросу (fetchList) %s получено сущностей: %u, всего получено: %u",
                        $function,
                        $resultCounter,
                        $totalCounter
                    ),
                    $this
                );
            }

            yield $result;

            if ($resultCounter < 50) {
                break;
            }

            $params['filter'][ $moreIdKey ] = $result[ $resultCounter - 1 ][ $idKey ];

        } while (true);
    }

    /**
     * Отправляет пакет запросов в API системы Битрикс24
     *
     * @param array $commands Пакет команд
     * @param bool $halt Определяет прерывать ли последовательность запросов в случае ошибки (0|1, true|false)
     * @param callable $dataExtractor Функция для извлечения данных из результатов запроса
     *
     * @return array|null
     *
     * @throws Bitrix24APIException
     * @throws HTTPException
     *
     * @see https://dev.1c-bitrix.ru/rest_help/general/batch.php Выполнение пакета запросов
     */
    public function batchRequest(array $commands, $halt = true, callable $dataExtractor = null)
    {
        // До 50 штук на 1 запрос
        $result = $this->request(
            'batch',
            [
                'halt' => (int) $halt,
                'cmd'  => $commands
            ],
            $dataExtractor
        );

        // Проверка наличия ошибок в ответе от запроса batch
        if (! empty($result['result_error'])) {
            $jsonCommands = $this->toJSON($commands);
            $jsonResponse = $this->toJSON($this->lastResponse);
            throw new Bitrix24APIException(
                sprintf(
                    "Ошибка при запросе batch (%s): %s",
                    $jsonCommands,
                    $jsonResponse
                )
            );
        }

        return $result['result'];
    }

    /**
     * Формирует массив одинаковых команд для метода пакетных запросов batchRequest()
     *
     * @param string $function Имя метода (функции) запроса
     * @param array  $items    Массив полей запросов
     *
     * @return array
     */
    public function buildCommands(string $function, array $items): array
    {
        $commands = [];
        foreach ($items as $fields) {
            $commands[] = $this->buildCommand($function, $fields);
        }

        return $commands;
    }

    /**
     * Формирует строку одной команды для пакета запросов
     *
     * @param string $function Имя метода (функции) запроса
     * @param array  $params   Массив параметров команды
     *
     * @return string
     *
     * @see https://dev.1c-bitrix.ru/rest_help/general/batch.php Выполнение пакета запросов
     */
    public function buildCommand(string $function, array $params): string
    {
        return $function . '?' . http_build_query($params);
    }

    /**
     * Создает и возвращает результат со связанными сущностями
     *
     * @param array  $result Результат
     * @param string $base   Имя базовой сущности
     * @param array  $with   Имена связанных сущностей
     *
     * @return array
     */
    protected function createResultWith(array $result, string $base, array $with): array
    {
        $resultWith = $result[ $base ];
        foreach ($with as $name) {
            $resultWith[ $name ] = $result[ $name ];
        }

        return $resultWith;
    }

    /**
     * Преобразует данные в строку JSON для сообщений об ошибке или лога
     *
     * @param mixed $data        Данные для преобразования
     * @param bool  $prettyPrint Включает/выключает "pretty print" для JSON
     *
     * @return string
     */
    protected function toJSON($data, bool $prettyPrint = false): string
    {
        $encodeOptions = JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR;

        if ($prettyPrint) {
            $encodeOptions |= JSON_PRETTY_PRINT;
        }

        $jsonParams = json_encode($data, $encodeOptions);
        if ($jsonParams === false) {
            $jsonParams = print_r($data, true);
        }

        return $jsonParams;
    }
}
