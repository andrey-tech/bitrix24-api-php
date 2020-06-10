# Bitrix24 API PHP Wrapper

![Bitrix24 logo](./assets/bitrix24-logo.png)

Обертка на PHP7+ для работы с REST API [Битрикс24](https://dev.1c-bitrix.ru/rest_help/) с использованием механизма входящих вебхуков, 
троттлингом запросов к серверу и логированием.

**Документация находится в процессе разработки.**

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Класс `Bitrix24API`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-bitrix24api)
    - [Базовые методы класса](#%D0%91%D0%B0%D0%B7%D0%BE%D0%B2%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
    - [Дополнительные параметры](#%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B)
- [Методы работы с сущностями Битрикс24](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124)
    - [Методы работы со сделками](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
    - [Обработка ошибок](#%D0%9E%D0%B1%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B0-%D0%BE%D1%88%D0%B8%D0%B1%D0%BE%D0%BA)
- [Класс `\App\HTTP`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-apphttp)
- [Примеры](#%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B)
    - [Работа со сделками](#%D0%A0%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
- [Автор](#%D0%90%D0%B2%D1%82%D0%BE%D1%80)
- [Лицензия](#%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F)

<!-- /MarkdownTOC -->

<a id="%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F"></a>
## Требования

- PHP >= 7.0.
- Произвольный автозагрузчик классов, реализующий стандарт [PSR-4](https://www.php-fig.org/psr/psr-4/).

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-bitrix24api"></a>
## Класс `Bitrix24API`

Для работы с REST API Битрикс24 используется класс `\App\Bitrix24\Bitrix24API`.

<a id="%D0%91%D0%B0%D0%B7%D0%BE%D0%B2%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0"></a>
### Базовые методы класса

- `__construct(string $webhookURL)` Конструктор класса.
    + `$webhookURL` - URL входящего вебхука Битрикс24.
- `request(string $function, array $params = []) :?array` Отправляет запрос в API.
    + `$function` - имя метода (функции) запроса;
    + `$params` - параметры запроса. 
- `getList(string $function, array $params = []) :\Generator` Загружает все сущности заданного типа.
    + `$function` - имя метода (функции) запроса;
    + `$params` - параметры запроса.
- `fetchList(string $function, array $params = []) :\Generator` Загружает все сущности [быстрым методом](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php).
    + `$function` - имя метода (функции) запроса;
    + `$params` - параметры запроса.
- `batchRequest(array $commands, $halt = true) :array` Отправляет пакет запросов в API. 
    + `$commands` - пакет команд;
    + `$halt` - прерывать последовательность запросов в случае ошибки.
- `buildCommands(string $function, array $items) :array` Создает массив команд для пакетного запроса.
    + `$function` - имя метода (функции) запроса;
    + `$items` - массив полей запросов.
- `buildCommand(string $function, array $params) :string` Возвращает команду для пакетного запроса.
    + `$function` - имя метода (функции) запроса;
    + `$items` - массив полей запросов.
- `getLastResponse() :?array`  Возвращает последний ответ от API.

<a id="%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B"></a>
### Дополнительные параметры

Дополнительные параметры доступны через публичные свойства класса `Bitrix24API`.

Свойство                | По умолчанию       | Описание
----------------------- | ------------------ | --------
`$batchSize`            | 50                 | Устанавливает количество команд в одном пакете запросов (batch)
`$logger`               | null               | Хранит объект класса, выполняющего логирование запросов и ответов API. Например, `\App\DebugLogger`
`$http`                 | `object \App\HTTP` | Хранит объект класса `\App\HTTP`, отправляющего запросы к API

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124"></a>
## Методы работы с сущностями Битрикс24

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8"></a>
### Методы работы со сделками

- `getDeal($dealId, array $with = []) :array` Возвращает параметры сделки по ее ID.
    + `$dealId` - ID сделки;
    + `$with` - имена связанных сущностей, возвращаемых вместе со сделкой.
        * `CONTACTS` - контакты;
        * `PRODUCTS` - товарные позиции.
- `addDeal(array $fields = [], array $params = []) :int` Добавляет сделку и возвращает ее ID.  
    - `$fields` - набор полей сделки;
    - `$params` - набор параметров сделки.
- `updateDeal($dealId, array $fields = [], array $params = []) :int` Обновляет сделку и возвращает ее ID.
    - `$dealId` - ID сделки;
    - `$fields` - набор полей сделки;
    - `$params` - набор параметров сделки.
- `deleteDeal($dealId) :int`  Удаляет сделку и возвращает ее ID.
    - `$dealId` - ID сделки.
- `getDealList(array $filter = [], array $select = [], array $order = []) :\Generator` Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.
    Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.
    - `$filter` - параметры фильтрации;
    - `$order` - параметры сортировки;
    - `$select` - параметры выборки полей.
- `fetchDealList(array $filter = [], array $select = [], array $order = []) :\Generator` Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.
    - `$filter` - параметры фильтрации;
    - `$order` - параметры сортировки;
    - `$select` - параметры выборки.
- `addDeals(array $deals = [], array $params = []) :array` Пакетно добавляет сделки со связанными товарными позициями и возвращает массив ID сделок.
    - `$deals` - массив наборов полей сделок со связанными товараными позициями `PRODUCTS`;
    - `$params` - набор параметров сделки.
- `updateDeals(array $deals = [], array $params = []) :array` Пакетно обновляет сделки со связанными товарными позициями и возвращает массив ID сделок.
    - `$deals` - массив наборов полей сделок со связанными товарными позициями `PRODUCTS`;
    - `$params` - набор параметров сделки.
- `deleteDeals(array $dealIds = []) :array` Пакетно удаляет сделки и возвращает массив ID сделок.
    - `$dealIds` - набор ID сделок.
- `setDealFile($dealId, $userFieldId, string $fileName, string $fileContent, bool $isBase64FileData = true) :int` Устанавливает файл в НЕ множественное пользовательское поле типа файл (файл нельзя удалить) и возвращает ID сделки.
    - `$dealId` - ID cделки;
    - `$userFieldId` ID НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX');
    - `$fileName` - имя файла;
    - `$fileContent` - RAW данные файла;
    - `$isBase64FileData` - RAW данные файла закодированны BASE64.
-  `setDealFiles($dealId, $userFieldId, array $files = [], bool $isBase64FileData = true) :int` Устанавливает файлы во множественное пользовательское поле типа файл (файлы можно удалить) и возвращает ID сделки.
    - `$dealId` - ID cделки;
    - `$userFieldId` ID НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX');
    - `$files` - массив параметров файлов ([ [  < Имя файла >, < RAW данные файла > ], ... ]) (пустой массив для удаления всех файлов);
    - `$isBase64FileData` - RAW данные файла закодированны BASE64.
- `getDealContactItems($dealId) :array` Возвращает массив параметров контактов, связанных со сделкой.
    - `$dealId` - ID cделки.
- `setDealContactItems($dealId, array $contacts) :array` Устанавливает контакты, связанные со сделкой.
    - `$dealId` - ID cделки;
    - `$contacts` - массив параметров контактов.
- `setDealProductRows($dealId, array $products) :array` Устанавливает товарые позиции, связанные со сделкой.
    - `$dealId` - ID cделки;
    - `$products` - массив параметров товарных позиций.
- `getDealProductRows($dealId) :array` Возвращает массив параметров товарных позиций, связанных со сделкой.
    - `$dealId` - ID cделки.
- `getDealProductRowFields() :array` Возвращает описание полей товарных позиций.
- `getDealFields() :array` Возвращает описание полей cделки, в том числе пользовательских.

<a id="%D0%9E%D0%B1%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B0-%D0%BE%D1%88%D0%B8%D0%B1%D0%BE%D0%BA"></a>
### Обработка ошибок

При возникновении ошибок при запросах к API выбрасывается исключение с объектом типа `\App\Bitrix24\Bitrix24APIException`.

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-apphttp"></a>
## Класс `\App\HTTP`

Вспомогательный класс `\App\HTTP` обеспечивает:

- формирование запросов к API Битрикс 24;
- троттлинг запросов к API на требуемом уровне [не более 2-х запросов в секунду](https://dev.1c-bitrix.ru/rest_help/rest_sum/index.php);
- вывод отладочной информации о запросах к API в STDOUT.

Дополнительные параметры доступны через публичные свойства класса `\App\HTTP`.

Свойство                | По умолчанию            | Описание
----------------------- | ----------------------- | --------
`$debugLevel`           | `\App\HTTP::DEBUG_NONE` | Устанавливает уровень вывода отладочной информации о запросах в STDOUT (DEBUG_NONE, DEBUG_URL, DEBUG_HEADERS, DEBUG_CONTENT)
`$throttle`             | 2                       | Максимальное число HTTP запросов в секунду
`$addBOM`               | false                   | Добавлять [маркер ВОМ](https://ru.wikipedia.org/wiki/%D0%9C%D0%B0%D1%80%D0%BA%D0%B5%D1%80_%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%D0%B4%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8_%D0%B1%D0%B0%D0%B9%D1%82%D0%BE%D0%B2) UTF-8 (EFBBBF) к запросам в формате JSON
`$useCookies`           | false                   | Использовать cookies в запросах
`$cookieFile`           | 'temp/cookies.txt'      | Путь к файлу для хранения cookies
`$verifySSLCerfificate` | true                    | Включить проверку SSL/TLS-сертификата сервера
`$SSLCertificateFile`   | 'cacert.pem'            | Устанавливает файл SSL/TLS-сертификатов X.509 корневых удостоверяющих центров (CA) в формате РЕМ (null - использовать файл, указанный в параметре curl.cainfo файла php.ini)
`$userAgent`            | 'HTTP-client/2.x.x'     | Устанавливает НТТР заголовок UserAgent в запросах
`$curlTimeout`          | 60                      | Устанавливает таймаут установения соединения, секунды
`$successStatusCodes`   | [ 200 ]                 | Коды статуса НТТР, которые считаются успешными


<a id="%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B"></a>
## Примеры

<a id="%D0%A0%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8"></a>
### Работа со сделками

```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Добавляем новую сделку
    $dealId = $bx24->addDeal([
        'TITLE'      => 'Новая сделка №1',
        'COMPANY_ID' => 6,
        'CONTACT_ID' => 312
    ]);
    print_r($dealId);

    // Устанавливаем набор связанных контактов
    $bx24->setDealContactItems($dealId, [
        [ 'CONTACT_ID' => 313 ],
        [ 'CONTACT_ID' => 454 ]
    ]);

    // Устанавливаем набор связанных товарных позиций
    $bx24->setDealProductRows($dealId, [
        [ 'PRODUCT_ID' => 1689, 'PRICE' => 1500.00, 'QUANTITY': 2 ],
        [ 'PRODUCT_ID' => 1860, 'PRICE' => 500.00, 'QUANTITY': 15 ]
    ]);

    // Обновляем существующую сделку
    $bx24->updateDeal($dealId, [
        'TITLE' => 'Новая сделка №12'
    ]);

    // Загружаем сделку по ID вместе со связанными товарами и контактами одним запросом
    $deal = $bx24->getDeal($dealId, [ 'PRODUCTS', 'CONTACTS' ]);
    print_r($deal);

    // Удаляем существующую сделку
    $bx24->deleteDeal($dealId);

    // Загружаем все сделки используя быстрый метод при работе с большими объемами данных
    $generator = $bx24->fetchDealList();
    foreach ($generator as $deals) {
        foreach($deals as $deal) {
            print_r($deal);
        }
    }

    // Пакетно добавляем сделки вместе с товарными позициями
    $ids = $bitrix->addDeals([
        [
            'TITLE' => 'Новая сделка №1121',
            'COMPANY_ID' => 6,
            'CONTACT_ID' => 312,
            'PRODUCTS' => [
                [ "PRODUCT_ID" => 27, "PRICE" => 100.00, "QUANTITY" => 11 ],
            ]

        ],
        [
            'TITLE' => 'Новая сделка №1122',
            'COMPANY_ID' => 6,
            'PRODUCTS' => [
                [ "PRODUCT_ID" => 28, "PRICE" => 200.00, "QUANTITY" => 22 ],
                [ "PRODUCT_ID" => 27, "PRICE" => 200.00, "QUANTITY" => 11 ],
            ]
        ]
    ]);
    print_r($ids);

} catch (\App\Bitrix24\Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80"></a>
## Автор

© 2019-2020 andrey-tech

<a id="%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F"></a>
## Лицензия

Данная библиотека распространяется на условиях лицензии [MIT](./LICENSE).
