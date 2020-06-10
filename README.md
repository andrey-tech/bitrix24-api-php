# Bitrix24 API PHP Wrapper

![Bitrix24 logo](./assets/bitrix24-logo.png)

Обертка на PHP7+ для работы с REST API [Битрикс24](https://dev.1c-bitrix.ru/rest_help/) с использованием механизма входящих вебхуков, 
троттлингом запросов к серверу и логированием.

**Документация находится в процессе разработки.**

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Класс `\App\Bitrix24\Bitrix24API`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-appbitrix24bitrix24api)
    - [Описание общих методов класса](#%D0%9E%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BE%D0%B1%D1%89%D0%B8%D1%85-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D0%BE%D0%B2-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
    - [Дополнительные параметры](#%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B)
- [Методы работы с сущностями Битрикс24](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124)
    - [Методы работы со сделками](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
        - [Описание методов](#%D0%9E%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D0%BE%D0%B2)
    - [Обработка ошибок](#%D0%9E%D0%B1%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B0-%D0%BE%D1%88%D0%B8%D0%B1%D0%BE%D0%BA)
- [Примеры](#%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B)
    - [Работа со сделками](#%D0%A0%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
- [Автор](#%D0%90%D0%B2%D1%82%D0%BE%D1%80)
- [Лицензия](#%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F)

<!-- /MarkdownTOC -->

<a id="%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F"></a>
## Требования

- PHP >= 7.0.
- Произвольный автозагрузчик классов, реализующий стандарт [PSR-4](https://www.php-fig.org/psr/psr-4/).

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-appbitrix24bitrix24api"></a>
## Класс `\App\Bitrix24\Bitrix24API`

Список общих методов класса:

- `__construct(string $webhookURL)`
- `request(string $function, array $params = []) :?array`  
- `getList(string $function, array $params = []) :\Generator`  
- `fetchList(string $function, array $params = []) :\Generator` 
- `batchRequest(array $commands, $halt = true) :array`  
- `buildCommands(string $function, array $items) :array`  
- `buildCommand(string $function, array $params) :string`
- `getLastResponse() :?array`  

<a id="%D0%9E%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BE%D0%B1%D1%89%D0%B8%D1%85-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D0%BE%D0%B2-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0"></a>
### Описание общих методов класса

- `__construct(string $webhookURL)`  
    Конструктор класса `Bitrix24API`.
    - `$webhookURL` - URL входящего вебхука Битрикс24.

- `request(string $function, array $params = []) :?array`  
    Отправляет запрос в API и возвращает ответ сервера.
    - `$function` - имя метода (функции) запроса;
    - `$params` - параметры запроса.

- `getList(string $function, array $params = []) :\Generator`  
    Загружает все сущности заданного типа.
    Возвращает объект типа `\Generator` для последующей выборки параметров загруженных сушностей.
    - `$function` - имя метода (функции) запроса;
    - `$params` - параметры запроса.

- `fetchList(string $function, array $params = []) :\Generator`  
    Загружает все сущности заданного типа.
    Возвращает объект типа `\Generator` для последующей выборки параметров загруженных сушностей.
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при
    работе с большими объемами данных.
    - `$function` - имя метода (функции) запроса;
    - `$params` - параметры запроса.

- `batchRequest(array $commands, $halt = true) :array`  
    Отправляет пакет запросов в API. Возвращает ответ сервера.
    - `$commands` - пакет команд;
    - `$halt` - определяет прерывать ли последовательность запросов в случае ошибки.

- `buildCommands(string $function, array $items) :array`  
    Возвращает массив одинаковых команд для метода `batchRequest()`.
    - `$function` - имя метода (функции) запроса;
    - `$items` - массив полей запросов.

- `buildCommand(string $function, array $params) :string`  
    Возвращает строку одной команды пакета запросов для метода `buildCommands()`.
    - `$function` - имя метода (функции) запроса;
    - `$params` - массив параметров команды.

- `getLastResponse() :?array`  
    Возвращает последний ответ сервера.

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

Работа с сущностями Битрикс24 строится с помощью методов класса `Bitrix24API`.

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8"></a>
### Методы работы со сделками

Список доступных методов:

- `getDeal($dealId, array $with = []) :array`  
- `addDeal(array $fields = [], array $params = []) :int`   
- `updateDeal($dealId, array $fields = [], array $params = []) :int`
- `deleteDeal($dealId) :int`
- `getDealList(array $filter = [], array $select = [], array $order = []) :\Generator`  
- `fetchDealList(array $filter = [], array $select = [], array $order = []) :\Generator`
- `addDeals(array $deals = [], array $params = []) :array`
- `updateDeals(array $deals = [], array $params = []) :array`
- `deleteDeals(array $dealIds = []) :array`
- `setDealFile($dealId, $userFieldId, string $fileName, string $fileContent, bool $isBase64FileData = true) :int` 
- `setDealFiles($dealId, $userFieldId, array $files = [], bool $isBase64FileData = true) :int`
- `getDealContactItems($dealId) :array`
- `setDealContactItems($dealId, array $contacts) :array`
- `getDealProductRows($dealId) :array`
- `setDealProductRows($dealId, array $products) :array`
- `getDealProductRowFields() :array`
- `getDealFields() :array`

<a id="%D0%9E%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D0%BE%D0%B2"></a>
#### Описание методов

- `getDeal($dealId, array $with = []) :array`  
    Возвращает параметры сделки по ID.  
    - `$dealId` - ID сделки;
    - `$with` - имена связанных сущностей, возвращаемых вместе со сделкой:
        - `CONTACTS` - контакты;
        - `PRODUCTS` - товарные позиции.

- `addDeal(array $fields = [], array $params = []) :int`   
    Добавляет новую сделку. Возвращает ID сделки.  
    - `$fields` - набор полей сделки;
    - `$params` - набор параметров сделки.

- `updateDeal($dealId, array $fields = [], array $params = []) :int`  
    Обновляет существующую сделку. Возвращает ID сделки.  
    - `$dealId` - ID сделки;
    - `$fields` - набор полей сделки;
    - `$params` - набор параметров сделки.

- `deleteDeal($dealId) :int`  
    Удаляет существующую сделку. Возвращает ID сделки.
    - `$dealId` - ID сделки.

- `getDealList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.
    Возвращает объект типа `\Generator` для последующей выборки параметров загруженных сделок.
    - `$filter` - параметры фильтрации;
    - `$order` - параметры сортировки;
    - `$select` - параметры выборки полей.

- `fetchDealList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.
    Возвращает объект типа `\Generator` для последующей выборки параметров загруженных сделок.
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при
    работе с большими объемами данных.
    - `$filter` - параметры фильтрации;
    - `$order` - параметры сортировки;
    - `$select` - параметры выборки.

- `addDeals(array $deals = [], array $params = []) :array`  
    Пакетно добавляет новые сделки со связанными товарными позициями. Возвращает массив ID сделок.
    - `$deals` - массив наборов полей сделок со связанными товараными позициями `PRODUCTS`;
    - `$params` - набор параметров сделки.

- `updateDeals(array $deals = [], array $params = []) :array`
    Пакетно обновляет существующие сделки со связанными товарными позициями. Возвращает массив ID сделок.
    - `$deals` - массив наборов полей сделок со связанными товарными позициями `PRODUCTS`;
    - `$params` - набор параметров сделки.

- `deleteDeals(array $dealIds = []) :array`
    Пакетно удаляет сделки. Возвращает массив ID сделок.
    - `$dealIds` - набор ID сделок.

- `setDealFile($dealId, $userFieldId, string $fileName, string $fileContent, bool $isBase64FileData = true) :int`  
    Устанавливает файл в НЕ множественное пользовательское поле типа файл (файл нельзя удалить).
    Возвращает ID сделки.  
    - `$dealId` - ID cделки;
    - `$userFieldId` ID НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX');
    - `$fileName` - имя файла;
    - `$fileContent` - RAW данные файла;
    - `$isBase64FileData` - RAW данные файла закодированны BASE64.

-  `setDealFiles($dealId, $userFieldId, array $files = [], bool $isBase64FileData = true) :int`  
    Устанавливает файлы во множественное пользовательское поле типа файл (файлы можно удалить).
    Возвращает ID сделки.
    - `$dealId` - ID cделки;
    - `$userFieldId` ID НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX');
    - `$files` - массив параметров файлов ([ [  < Имя файла >, < RAW данные файла > ], ... ]) (пустой массив для удаления всех файлов);
    - `$isBase64FileData` - RAW данные файла закодированны BASE64.

- `getDealContactItems($dealId) :array`  
    Возвращает массив параметров контактов, связанных со сделкой по ID сделки.  
    - `$dealId` - ID cделки.

- `setDealContactItems($dealId, array $contacts) :array`  
    Устанавливает контакты, связанные со сделкой по ID сделки. Возвращает ответ сервера.
    - `$dealId` - ID cделки;
    - `$contacts` - массив параметров контактов.

- `setDealProductRows($dealId, array $products) :array`  
    Устанавливает товарые позиции, связанные со сделкой по ID сделки.  Возвращает ответ сервера.
    - `$dealId` - ID cделки;
    - `$products` - массив параметров товарных позиций.

- `getDealProductRows($dealId) :array`  
    Возвращает массив параметров товарных позиций, связанных со сделкой по ID сделки.
    - `$dealId` - ID cделки.

- `getDealProductRowFields() :array`  
    Возвращает описание полей товарных позиций.

- `getDealFields() :array`  
    Возвращает описание полей cделки, в том числе пользовательских.

<a id="%D0%9E%D0%B1%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B0-%D0%BE%D1%88%D0%B8%D0%B1%D0%BE%D0%BA"></a>
### Обработка ошибок

При возникновении ошибок при запросах к API выбрасывается исключение типа `Bitrix24APIException`.

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
            print($deal);
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
