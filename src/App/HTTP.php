<?php
/**
 * Класс HTTP. Выполняет НТТP запросы
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/
 * @license   MIT
 *
 * @version 2.7.2
 *
 * v1.0.0 (21.06.2019) Начальный релиз
 * v2.0.0 (21.07.2019) Изменения для App
 * v2.1.0 (28.08.2019) Добавлен параметр $curlOptions
 * v2.2.0 (01.10.2019) Добавлен метод getHTTPCode()
 * v2.3.0 (01.10.2019) Добавлен параметр $useCookies
 * v2.4.0 (04.10.2019) Добавлено удаление BOM перед декодированием JSON
 * v2.4.1 (13.10.2019) Изменен момент сохранения lastRequestTime для троттлинга.
 *                     Замена метода: getAbsoluteFilePath() на getAbsoluteFileName()
 * v2.5.0 (16.10.2019) Добавлен метод getResponse()
 * v2.5.1 (11.11.2019) Исправлен баг в методе isSuccess()
 * v2.6.0 (17.11.2019) Добавлен параметр $addBOM
 * v2.7.0 (12.05.2020) Свойство $throttle теперь это число запросов в секунду.
                       Свойство $useCookies теперь по умолчанию false
 * v2.7.1 (22.05.2020) Исправлен метод throttleCurl(). Изменены отладочные сообщения
 * v2.7.2 (10.05.2020) Рефракторинг
 *
 */

declare(strict_types = 1);

namespace App;

class HTTP
{
    /**
     * Битовые маски для указания уровня вывода отладочной информации (для параметра $debugLevel)
     * @var int
     */
    const DEBUG_NONE    = 0; // 000 - не выводить
    const DEBUG_URL     = 1 << 0; // 001 - URL запросов/ответов
    const DEBUG_HEADERS = 1 << 1; // 010 - заголовки запросов/ответов
    const DEBUG_CONTENT = 1 << 2; // 100 - содержимое запросов/ответов

    /**
     * Уровень вывода отладочной информации
     * \App\HTTP::DEBUG_URL | \App\HTTP::DEBUG_HEADERS | \App\HTTP::DEBUG_CONTENT
     * @var int
     */
    public $debugLevel = self::DEBUG_NONE;

    /**
     * Максимальное число HTTP запросов в секунду
     * @var float
     */
    public $throttle = 1E6;

    /**
     * Флаг добавления маркера ВОМ UTF-8 (EFBBBF) к запросам в формате JSON
     * @var boolean
     */
    public $addBOM = false;

    /**
     * Флаг использования cookie в запросах
     * @var boolean
     */
    public $useCookies = false;

    /**
     * Путь к файлу для хранения cookies
     * @var string
     */
    public $cookieFile = 'temp/cookies.txt';

    /**
     * Флаг включения проверки SSL-сертификата сервера
     * @var bool
     */
    public $verifySSLCerfificate = true;

    /**
     * Файл SSL-сертификатов X.509 корневых удостоверяющих центров (относительно каталога файла данного класса)
     * (null - файл, указанный в настройках php.ini)
     * @var string | null - файл из конфигурации php.ini
     */
    public $sslCertificateFile = 'cacert.pem';

    /**
     * UserAgent в запросах
     * @var string
     */
    public $userAgent = 'HTTP-client/2.x.x';

    /**
     * Таймаут соединения для cUrl, секунды
     * @var integer
     */
    public $curlTimeout = 60;

    /**
     * Коды статуса НТТР при успешном выполнении запроса
     * @var array
     */
    public $successStatusCodes = [ 200 ];

    /**
     * Ресурс cURL
     * @var resourse
     */
    protected $curl;

    /**
     * Информация о последней операции curl
     * @var array
     */
    protected $curlInfo = [];

    /**
     * Тело последнего ответа
     * @var array
     */
    protected $response;

    /**
     * Заголовки последнего ответа
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * Время последнего запроса, микросекунды
     * @var float
     */
    protected $lastRequestTime = 0;

    /**
     * Счетчик числа запросов для отладочных сообщений
     * @var integer
     */
    protected $requestCounter = 0;

    /**
     * Устанавливает параметры по умолчанию для cURL
     * @return void
     */
    protected function setDefaultCurlOptions()
    {
        $this->responseHeaders = [];

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($this->curl, CURLOPT_HEADER, false);

        // Использование cookies
        if ($this->useCookies) {
            $cookieFile = $this->getAbsoluteFileName($this->cookieFile);
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookieFile);
        }

        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->curlTimeout);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, [ $this, 'storeResponseHeaders' ]);

        // Включение проверки SSL-сертификата сервера
        if ($this->verifySSLCerfificate) {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
            if ($this->sslCertificateFile) {
                $sslCertificateFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->sslCertificateFile;
                curl_setopt($this->curl, CURLOPT_CAINFO, $sslCertificateFile);
            }
        } else {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
    }

    /**
     * Отправляет HTTP запрос
     * @param string $url Адрес запроса
     * @param string $type Тип запроса
     * @param array $params Парметры запроса
     * @param array $requestHeaders Заголовки запроса
     * @param array $curlOptions Дополнителльные опции для cURL
     * @return mixed
     */
    public function request(
        string $url,
        string $type = 'GET',
        array $params = [],
        array $requestHeaders = [],
        array $curlOptions = []
    ) {
        // Увеличиваем счетчик числа отправленных запросов
        $this->requestCounter++;

        // Инициализацируем cURL и устанавливаем опции по умолчанию
        $this->curl = curl_init();
        $this->setDefaultCurlOptions();

        // Установливаем дополнительные опции cURL
        if (count($curlOptions)) {
            curl_setopt_array($this->curl, $curlOptions);
        }

        // Формируем тело запроса
        $query = $this->buildQuery($params, $requestHeaders);

        // Устанавливаем заголовки запроса
        if (count($requestHeaders)) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $requestHeaders);
        }
        
        switch ($type) {
            case 'GET':
            case 'HEAD':
                if ($query !== '') {
                    $url .= '?' . $query;
                }
                $this->debug("[{$this->requestCounter}] ===> {$type} {$url}", self::DEBUG_URL);
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $this->debug("[{$this->requestCounter}] ===> {$type} {$url}", self::DEBUG_URL);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $type);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);
                break;
            default:
                throw new AppException("Неизвестный метод запроса {$type}");
        }

        // Устанавливаем URL запроса
        curl_setopt($this->curl, CURLOPT_URL, $url);

        // Отправляем запрос
        $this->response = $this->throttleCurl();
        $deltaTime = sprintf('%0.4f', microtime(true) - $this->lastRequestTime);

        // Сохраняем информацию cURL и завершаем сеанс cURL
        $this->curlInfo = curl_getinfo($this->curl);
        $code = (int) curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $errno = curl_errno($this->curl);
        $error = curl_error($this->curl);
        curl_close($this->curl);

        // Проверяем ошибки cURL
        if ($errno) {
            throw new AppException("Ошибка cURL #{$errno} ({$url}): {$error}");
        }

        // Выводим заголовки и тело запроса
        $this->debug($this->curlInfo['request_header'] ?? 'REQUEST HEADERS ???', self::DEBUG_HEADERS);
        if ($type !== 'GET') {
            $this->debug($query . PHP_EOL, self::DEBUG_CONTENT);
        }

        // Выводим строку, заголовки и тело ответа
        $this->debug("[{$this->requestCounter}] <=== RESPONSE {$deltaTime}s ({$code})", self::DEBUG_URL);
        $this->debug(implode(PHP_EOL, $this->responseHeaders), self::DEBUG_HEADERS);
        $this->debug($this->response . PHP_EOL, self::DEBUG_CONTENT);

        return $this->decodeResponse($this->response, $code);
    }

    /**
     * Возвращает флаг успешности выполнения запроса
     * @param array $successStatisCodes Статус коды успешного выполнения запроса
     * @return boolean
     */
    public function isSuccess(array $successStatusCodes = []) :bool
    {
        if (! count($successStatusCodes)) {
            $successStatusCodes = $this->successStatusCodes;
        }

        $code = (int) ($this->curlInfo['http_code'] ?? 0);
        return in_array($code, $successStatusCodes);
    }

    /**
     * Возвращает HTTP Code последнего запроса
     * @return int
     */
    public function getHTTPCode()
    {
        return (int) $this->curlInfo['http_code'] ?? 0;
    }

    /**
     * Возвращает тело последнего ответа
     * @return string
     */
    public function getResponse(bool $isRaw = true)
    {
        return $this->response;
    }

    /**
     * Возвращает заголовки последнего ответа
     * @return array
     */
    public function getResponseHeaders() :array
    {
        return $this->responseHeaders;
    }

    /**
     * Возвращает информацию о последней операции cURL
     * @return array
     */
    public function getCurlInfo() :array
    {
        return $this->curlInfo;
    }

    /**
     * Формирует строку запроса
     * @param  array  $params Параметры запроса
     * @param  array  $requestHeaders Заголовки запроса
     * @return string
     */
    protected function buildQuery(array $params, array $requestHeaders) :string
    {
        if (! count($params)) {
            return '';
        }

        $contentType = $this->getContentType($requestHeaders);
        switch ($contentType) {
            case 'json':
                $jsonParams = json_encode($params);
                if ($jsonParams === false) {
                    $errorMessage = json_last_error_msg();
                    throw new AppException("Не удалось закодировать в JSON ({$errorMessage}): " . print_r($params, true));
                }
                // Добавляем маркер BOM
                if ($this->addBOM) {
                    $jsonParams = chr(239) . chr(187) . chr(191) . $jsonParams;
                }
                return $jsonParams;
            default:
                return http_build_query($params);
        }
    }

    /**
     * Декодирует тело ответа
     * @param  string $response Тело ответа
     * @param  int    $code Статус код ответа
     * @return mixed
     */
    protected function decodeResponse(string $response, int $code)
    {
        if ($code === 204) {
            return $response;
        }

        $contentType = $this->getContentType($this->responseHeaders);
        switch ($contentType) {
            case 'json':
                // Удаляем маркер ВОМ если он есть
                $response = ltrim($response, chr(239) . chr(187) . chr(191));
                $decodedResponse = json_decode($response, true);
                if (is_null($decodedResponse)) {
                    $errorMessage = json_last_error_msg();
                    throw new AppException("Не удалось декодировать JSON ({$errorMessage}): {$response}");
                }
                break;
            default:
                $decodedResponse = $response;
        }

        return $decodedResponse;
    }

    /**
     * Возвращает тип контента из заголовков запроса/ответа
     * @param  array  $headers Заголовки запроса/ответа
     * @return string | null
     */
    protected function getContentType(array $headers)
    {
        foreach ($headers as $header) {
            $header = explode(':', $header, 2);

            // Пропускаем ошибочные заголовки
            if (count($header) < 2) {
                continue;
            }

            $name = strtolower(trim($header[0]));

            // Content-Type:
            if (stripos($name, 'content-type') === 0) {
                $value = strtolower(trim($header[1]));

                // application/json, application/hal+json, ...
                if (stripos($value, 'json') !== false) {
                    return 'json';
                }

                return $value;
            }
        }

        return null;
    }

    /**
     * Обеспечивает троттлинг HTTP запросов
     * @return string|false $response
     */
    protected function throttleCurl()
    {
        do {
            // Вычисляем необходимое время задержки перед отправкой запроса, микросекунды
            $usleep = intval(1E6 * ($this->lastRequestTime + 1/$this->throttle - microtime(true)));
            if ($usleep <= 0) {
                break;
            }

            $sleep = sprintf('%0.4f', $usleep/1E6);
            $this->debug("[{$this->requestCounter}] ++++ THROTTLE ({$this->throttle}) {$sleep}s", self::DEBUG_URL);

            usleep($usleep);
        } while (false);

        $this->lastRequestTime = microtime(true);

        $response = curl_exec($this->curl);

        return $response;
    }

    /**
     * Сохраняет заголовки ответа
     * @param  resource $curl
     * @param  string $header Строка заголовка
     * @return int Длина заголовка
     * @see https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request
     */
    protected function storeResponseHeaders($curl, string $header) :int
    {
        $this->responseHeaders[] = trim($header);
        return strlen($header);
    }

    /**
     * Выводит в STDOUT отладочные сообщения на заданном уровне вывода отладочной информации
     * @param string $message
     * @param int Заданный уровень вывода отладочной информации
     * @return void
     */
    protected function debug(string $message, int $debugLevel)
    {
        if (! ($this->debugLevel & $debugLevel)) {
            return;
        }

        echo $message . PHP_EOL;
    }

    /**
     * Возвращает абсолютное имя файла и создает каталоги при необходимости
     * @param string $relativeFileName Относительное имя файла
     * @param bool $createDir Создавать каталоги при необходимости?
     * @return string|null Абсолютное имя файла
     * @see http://php.net/manual/ru/function.stream-resolve-include-path.php#115229
     */
    private function getAbsoluteFileName(string $relativeFileName, bool $createDir = true)
    {
        $includePath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePath as $path) {
            $absoluteFileName = $path . DIRECTORY_SEPARATOR . $relativeFileName;
            $checkDir = dirname($absoluteFileName);
            if (is_dir($checkDir)) {
                return $absoluteFileName;
            }
            if ($createDir) {
                if (! mkdir($checkDir, $mode = 0755, $recursive = true)) {
                    throw new AppException("Не удалось создать каталог {$checkDir}");
                }
                return $absoluteFileName;
            }
        }
        return null;
    }
}
