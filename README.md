# Bitrix24 API PHP Wrapper

![Bitrix24 logo](./assets/bitrix24-logo.png)

Обертка на PHP7+ для работы с [REST API Битрикс24](https://dev.1c-bitrix.ru/rest_help/) с использованием механизма [входящих вебхуков](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=99&LESSON_ID=8581), 
троттлингом запросов к API и логированием в файл.

**Документация находится в процессе разработки.**

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Класс `Bitrix24API`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-bitrix24api)
    - [Базовые методы класса](#%D0%91%D0%B0%D0%B7%D0%BE%D0%B2%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
    - [Дополнительные параметры](#%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B)
- [Методы работы с сущностями Битрикс24](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124)
    - [Методы работы со сделками](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с контактами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с компаниями](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8)
    - [Методы для работы с каталогами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с товарами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%D0%BC%D0%B8)
    - [Методы работы с разделами товаров](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%80%D0%B0%D0%B7%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BE%D0%B2)
    - [Методы для работы с пользователями](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F%D0%BC%D0%B8)
    - [Методы работы с задачами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с делами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с диском](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B8%D1%81%D0%BA%D0%BE%D0%BC)
- [Вспомогательные классы](#%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D1%8B)
    - [Класс `\App\HTTP`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-apphttp)
    - [Класс `\App\DebugLogger`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-appdebuglogger)
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
При возникновении ошибок выбрасывается исключение с объектом класса `\App\Bitrix24\Bitrix24APIException`.  
В настоящее время класс содержит методы для работы со следующими сущностями Битрикс24:

- [Сделки](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
- [Контакты](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B0%D0%BC%D0%B8)
- [Компании](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8)
- [Каталог](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3%D0%B0%D0%BC%D0%B8)
- [Товары](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%D0%BC%D0%B8)
- [Разделы товаров](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%80%D0%B0%D0%B7%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BE%D0%B2)
- [Задачи](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B0%D0%BC%D0%B8)
- [Дела](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8)
- [Пользователи](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F%D0%BC%D0%B8)
- [Диск](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B8%D1%81%D0%BA%D0%BE%D0%BC)

<a id="%D0%91%D0%B0%D0%B7%D0%BE%D0%B2%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0"></a>
### Базовые методы класса

Базовые методы находятся в классе `\App\Bitrix24\Bitrix24API`:

- `__construct(string $webhookURL)` Конструктор класса.
- `request(string $function, array $params = []) :?array` Отправляет запрос в API.
- `getList(string $function, array $params = []) :\Generator` Загружает все сущности заданного типа.
- `fetchList(string $function, array $params = []) :\Generator` Загружает все сущности [быстрым методом](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php).
- `batchRequest(array $commands, $halt = true) :array` Отправляет пакет запросов в API. 
- `buildCommands(string $function, array $items) :array` Создает массив команд для пакетного запроса.
- `buildCommand(string $function, array $params) :string` Возвращает команду для пакетного запроса.
- `getLastResponse() :?array`  Возвращает последний ответ от API.

Параметры методов:

+ `$webhookURL` - URL входящего вебхука;
+ `$function` - имя метода (функции) запроса;
+ `$params` - параметры запроса;
+ `$commands` - пакет команд;
+ `$items` - массив полей запросов;
+ `$halt` - прерывать последовательность запросов в случае ошибки.

<a id="%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B"></a>
### Дополнительные параметры

Дополнительные параметры доступны через публичные свойства класса `Bitrix24API`.

Свойство                | По умолчанию       | Описание
----------------------- | ------------------ | --------
`$batchSize`            | 50                 | Устанавливает количество команд в одном пакете запросов (batch)
`$logger`               | null               | Хранит объект класса `\App\DebugLogger`, выполняющего логирование запросов и ответов к API в файл.
`$http`                 | `object \App\HTTP` | Хранит объект класса `\App\HTTP`, отправляющего запросы к API

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124"></a>
## Методы работы с сущностями Битрикс24

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8"></a>
### Методы работы со сделками

Методы для работы со сделками находятся в трейте `\App\Bitrix24\Deal`:

- `getDeal($dealId, array $with = []) :array` Возвращает сделку по ее ID.
- `addDeal(array $fields = [], array $params = []) :int` Добавляет сделку и возвращает ее ID.  
- `updateDeal($dealId, array $fields = [], array $params = []) :int` Обновляет сделку и возвращает ее ID.
- `deleteDeal($dealId) :int`  Удаляет сделку и возвращает ее ID.
- `getDealList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.
- `fetchDealList(array $filter = [], array $select = [], array $order = []) :\Generator`  
     Загружает все сделки с возможностью фильтрации, сортировки и выборки полей.  
     Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.    
- `addDeals(array $deals = [], array $params = []) :array`  
   Пакетно добавляет сделки со связанными товарными позициями и возвращает массив ID сделок.
- `updateDeals(array $deals = [], array $params = []) :array`  
    Пакетно обновляет сделки со связанными товарными позициями и возвращает массив ID сделок.
- `deleteDeals(array $dealIds = []) :array` Пакетно удаляет сделки и возвращает массив ID сделок.
- `setDealFile($dealId, $userFieldId, string $fileName, string $fileContent, bool $isBase64FileData = true) :int`  
    Устанавливает файл в НЕ множественное пользовательское поле типа файл (файл нельзя удалить) и возвращает ID сделки.
- `setDealFiles($dealId, $userFieldId, array $files = [], bool $isBase64FileData = true) :int`  
    Устанавливает файлы во множественное пользовательское поле типа файл (файлы можно удалить) и возвращает ID сделки.
- `getDealContactItems($dealId) :array` Возвращает массив параметров контактов, связанных со сделкой.
- `setDealContactItems($dealId, array $contacts) :array` Устанавливает контакты, связанные со сделкой.
- `setDealProductRows($dealId, array $products) :array` Устанавливает товарые позиции, связанные со сделкой.
- `getDealProductRows($dealId) :array` Возвращает массив параметров товарных позиций, связанных со сделкой.
- `getDealProductRowFields() :array` Возвращает описание полей товарных позиций.
- `getDealFields() :array` Возвращает описание полей cделки, в том числе пользовательских.

Параметры методов:

+ `$dealId` - ID сделки;
+ `$dealIds` - массив ID сделок;
+ `$with` - имена связанных сущностей, возвращаемых вместе со сделкой;
    * `CONTACTS` - контакты;
    * `PRODUCTS` - товарные позиции;
- `$fields` - набор полей сделки;
- `$params` - набор параметров сделки;
- `$filter` - параметры фильтрации;
- `$order` - параметры сортировки;
- `$select` - параметры выборки полей;
- `$userFieldId` ID НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX');
- `$files` - массив параметров файлов ([ [  < Имя файла >, < RAW данные файла > ], ... ]) (пустой массив для удаления всех файлов);
- `$isBase64FileData` - RAW данные файла закодированны BASE64;
- `$contacts` - массив параметров контактов;
- `$products` - массив параметров товарных позиций.

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
    $dealIds = $bx24->addDeals([
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

    // Пакетно удаляем сделки
     $bx24->deleteDeals($dealIds);

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с контактами

Методы для работы с контактами находятся в трейте `\App\Bitrix24\Contact`:


<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8"></a>
### Методы для работы с компаниями

Методы для работы с компаниями находятся в трейте `\App\Bitrix24\Company`:



<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с каталогами

Методы для работы с товарными каталогами находятся в трейте `\App\Bitrix24\Catalog`:

- `getCatalogList(array $filter = [], array $select = [], array $order = []) :\Generator`   
    Загружает все каталоги с возможностью фильтрации, сортировки и выборки полей.
- `fetchCatalogList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все каталоги с возможностью фильтрации, сортировки и выборки полей.  
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.    

Параметры методов:

+ `$filter` - параметры фильтрации;
+ `$order` - параметры сортировки;
+ `$select` - параметры выборки полей.

```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Загружаем все товарные каталоги используя быстрый метод при работе с большими объемами данных
    $generator = $bx24->fetchCatalogList();
    foreach ($generator as $catalogs) {
        foreach($catalogs as $catalog) {
            print_r($catalog);
        }
    }

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с товарами

Методы для работы с товарами находятся в трейте `\App\Bitrix24\Product`:

- `getProduct($productId) :array` Возвращает товар по ID.
- `addProduct(array $fields = []) :int` Добавляет товар и возвращает его ID.
- `updateProduct($productId, array $fields = []) :int` Обовляет товар и возвращает его ID.
- `deleteProduct($productId) :int` Удаляет товар и возвращает его ID.
-  `getProductList(array $filter = [], array $select = [ '*', 'PROPERTY_*' ], array $order = []) :\Generator`  
    Загружает все товары с возможностью фильтрации, сортировки и выборки полей.
-  `fetchProductList(array $filter = [], array $select = [ '*', 'PROPERTY_*' ], array $order = []) :\Generator`  
    Загружает все товары с возможностью фильтрации, сортировки и выборки полей.  
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.
- `addProducts(array $products = []) :array` Пакетно добавляет товары.
- `updateProducts(array $products = []) :array` Пакетно обновляет товары.
- `deleteProducts(array $productIds = []) :array` Пакетно удаляет товары.
- `getProductFields() :array` Возвращает описание полей товара, в том числе пользовательских.

Параметры методов:

+ `$productId` - ID товара.
+ `$productIds` - массив ID товаров.
+ `$fields` - набор полей товара.
+ `$filter` - параметры фильтрации;
+ `$select` - параметры выборки полей;
+ `$order` - параметры сортировки.
+ `$products` - массив наборов полей товара.

```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Получаем товар по его ID
    $product = $bx24->getProduct(2396);
    print_r($product);

    // Обновляем товар
    $bx24->updateProduct(2396, [
        "PRICE" => 4900
    ]);

    // Удаляем товар
    $bx24->deleteProduct(2396);

    // Загружаем все товары c фильтрацией по полю SECTION_ID
    $generator = $bx24->fetchProductList([ 'SECTION_ID' => 13 ]);
    foreach ($generator as $users) {
        foreach($users as $user) {
            print_r($user);
        }
    }
       
    // Пакетно обновляем товары
    $bx24->updateProducts([
        [   
            "ID"          => 27,
            "NAME"        => "Тестовый товар 11",
            "CURRENCY_ID" => "RUB",
            "PRICE"       => 4900,
            "SORT"        => 500,
            "SECTION_ID"  => 13
        ],
        [ 
            "ID"          => 28,
            "NAME"        => "Тестовый товар 12",
            "CURRENCY_ID" => "RUB",
            "PRICE"       => 900,
            "SORT"        => 100,
            "SECTION_ID"  => 13
        ],
        [
            "ID"          => 29,
            "NAME"        => "Тестовый товар 13",
            "CURRENCY_ID" => "RUB",
            "PRICE"       => 2900,
            "SORT"        => 300,
            "SECTION_ID"  => 13
        ]
    ]);

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%80%D0%B0%D0%B7%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BE%D0%B2"></a>
### Методы работы с разделами товаров

Методы для работы с разделами товаров находятся в трейте `\App\Bitrix24\ProductSection`:

- `getProductSection($productSectionId) :array` Возвращает раздел товаров по ID.
- `addProductSection(array $fields = []) :int` Добавляет раздел товаров и возвращает его ID.
- `updateProductSection($productSectionId, array $fields = []): int` Обновляет раздел товаров и возвращает его ID.
- `deleteProductSection($productSectionId) :int` Удаляет раздел товаров и возвращает его ID.
- `getProductSectionList(array $filter = [], array $select = [], array $order = []) :\Generator`  
   Загружает все разделы товаров с возможностью фильтрации, сортировки и выборки полей.
- `fetchProductSectionList(array $filter = [], array $select = [], array $order = []) :\Generator`  
   Загружает все разделы товаров с возможностью фильтрации, сортировки и выборки полей.
   Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.
- `addProductSections(array $productSections = []) :array` Пакетно добавляет разделы товаров.
- `updateProductSections(array $productSections = []) :array` Пакетно обновляет разделы товаров.
- `deleteProducts(array $productSectionIds = []) :array` Пакетно удаляет разделы товаров.
- `getProductSectionFields() :array` Возвращает описание полей раздела товара.

Параметры методов:

+ `$productSectionId` - ID раздела товаров;
+ `$productSectionIds` - массив ID разделов товаров;
+ `$fields` - набор полей раздела товаров;
+ `$filter` - параметры фильтрации;
+ `$select` - параметры выборки полей;
+ `$order` - параметры сортировки.

```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Получаем раздел товаров по его ID
    $productSection = $bx24->getProductSection(16);
    print_r($productSection);

    // Обновляем раздел товаров
    $bx24->updateProductSection(16, [
        'NAME' => 'Раздел товаров 1'
    ]);

    // Удаляем раздел товаров
    $bx24->deleteProductSection(16);

    // Загружаем все разделы товаров c фильтрацией по полю CATALOG_ID
    $generator = $bx24->fetchProductSectionList([ 'CATALOG_ID' => 2 ]);
    foreach ($generator as $productSections) {
        foreach($productSections as $productSection) {
            print_r($productSection);
        }
    }
      
    // Пакетно добавляем разделы товаров
    $productSectionIds = $bx24->addProductSections([
        [   
            "NAME"       => "Раздел товаров 3",
            'CATALOG_ID' => 2
        ],
        [   
            "NAME"       => "Раздел товаров 4",
            'CATALOG_ID' => 2
        ]
    ]);

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F%D0%BC%D0%B8"></a>
### Методы для работы с пользователями

Методы для работы с пользователями находятся в трейте `\App\Bitrix24\User`:

- `getUser($userId) ?:array` Возвращает пользователя по ID.
- `getUsers(array $filter = [], string $order = 'ASC', string $sort = '', bool $adminMode = false) :\Generator`  
    Загружает всех пользователей с возможностью фильтрации, сортировки и выборки полей.
- `getUserFields() :array` Возвращает описание полей пользователя.

Параметры методов:

+ `$userId` - ID пользователя;
+ `$filter` - параметры фильтрации;
+ `$order` - направление сортировки (ASC|DESC);
+ `$sort` -  поле, по которому сортируются результаты;
+ `$select` - параметры выборки полей;
+ `$adminMode` - включает режим администратора для получения данных о любых пользователях.


```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Получаем пользователя по ID
    $user = $bx24->getUser(34782);
    print_r($user);

    // Получаем всех пользователей типа сотрудник с сортировкой по имени
    $generator = $bx24->getUsers(
        [ 'USER_TYPE' => 'employee' ],
        $order = 'ASC',
        $sort = 'NAME' 
    );
    foreach ($generator as $users) {
        foreach($users as $user) {
            print_r($user);
        }
    }

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B0%D0%BC%D0%B8"></a>
### Методы работы с задачами

Методы для работы с задачами находятся в трейте `\App\Bitrix24\Task`:

- `getTask($taskId, array $select = []) :?array` Возращает задачу по ID.
- `addTask(array $fields = []) :int` Добавляет новую задачу.
- `addTasks(array $tasks = []) :array` Пакетно добавляет задачи.
- `getTaskFields() :array` Возвращает описание полей задачи.

Параметры методов:

+ `$taskId` - ID задачи;
+ `$select` - параметры выборки полей;
+ `$fields` - набор полей задачи;
+ `$tasks` - массив наборов полей задач.

```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Получаем задачу по ID
    $task = $bx24->getTask(4325);
    print_r($task);

    // Создаем новую задачу
    $taskId = $bx24->addTask([
        'TITLE'           => 'Новая задача №123', // Название задачи
        'DESCRIPTION'     => 'Описание задачи', // Описание задачи
        'RESPONSIBLE_ID'  => 43242, // ID ответственного пользователя
        'UF_CRM_TASK'     => [ 'D_' . 38492 ], // Привязка задачи к сделке
        'START_DATE_PLAN' => '09.08.2005', // Плановая дата начала.
        'END_DATE_PLAN'   => '09.09.2005', // Плановая дата завершения
        'DEADLINE'        => '2005-09-09T18:31:42+03:30' // Крайний срок
    ]);
    print_r($taskId);

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с делами

Методы для работы с делами (активностями) находятся в трейте `\App\Bitrix24\Activity`:

- `getActivity($activityId) :?array` Возвращает дело по ID.
- `addActivity(array $fields = []) :int` Создает новое дело и возвращает его ID.
- `addActivities(array $activities = []) :array` Пакетно создает дела.
- `getActivityFields() :array` Возвращает описание полей дела.

Параметры методов:

+ `$activityId` - ID дела;
+ `$fields` - набор полей дела;
+ `$activities` - массив наборов полей дел.

```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Добавляем новое дело типа письмо
    $activityId = $bx24->addActivity([
        'SUBJECT'          => 'Заголовок письма', // Email subject
        'DESCRIPTION'      => 'Описание активности', // Email body
        'DESCRIPTION_TYPE' => 2, // Тип тела email: 1- Plain text, 2 - bbCode, 3 - HTML (crm.enum.contenttype)
        'COMPLETED'        => 'N', // Флаг немедленной отправки: Y|N
        'DIRECTION'        => 2, // Направление: 1 - входящее, 2 - исходящее (crm.enum.activitydirection)
        'OWNER_TYPE_ID'    => 2, // Тип сущности: 2 - Сделка, 3 - контакт, 4 - Компания,... (crm.enum.ownertype)
        'OWNER_ID'         => 39293, // ID сущности (сделки)
        'TYPE_ID'          => 4, // Тип активности: 4 - Письмо (crm.enum.activitytype)
        'RESPONSIBLE_ID'   => 4852, // ID ответственного пользователя
        'START_TIME'       => '2005-08-09T18:31:42+03:30', // Время начала
        'END_TIME'         => '2005-09-10T18:31:42+03:30', // Время окончания
        'COMMUNICATIONS' => [ // Параметры получателей письма
            [
                'VALUE'          => 'test@example.com', // Email компании
                'ENTITY_ID'      => 58938, // ID компании
                'ENTITY_TYPE_ID' => 4 // Тип сущности: 4 - Компания ('crm.enum.ownertype');
            ]
        ],
        'SETTINGS' => [
            'MESSAGE_FROM' => 'from@example.com'
        ]
    ]);
    print_r($activityId);

    // Получаем дело по ID
    $activity = $bx24->getActivity($activityId);
    print_r($activity);

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B8%D1%81%D0%BA%D0%BE%D0%BC"></a>
### Методы для работы с диском

Методы для работы с Диском находятся в трейте `\App\Bitrix24\Disk`:

-  `getDiskStorageList(array $filter = []) :\Generator`  
    Загружает список доступных хранилищ c возможностью фильтрации.
- `getDiskStorageChildren($storageId, array $filter = []) :array`  
    Возвращает список файлов и папок, которые находятся непосредственно в корне хранилища c возможностью фильтрации.
- `uploadfileDiskFolder($folderId, string $fileContent, array $data, bool $isBase64FileData = true) :array`
    Загружает новый файл в указанную папку на Диск.

Параметры методов:

+ `$filter` - параметры фильтрации;
+ `$storageId` - ID хранилища;
+ `$filter` - параметры фильтрации;
+ `$folderId` - ID папки;
+ `$fileContent` - RAW данные файла;
+ `$data` - набор параметров, описывающих файл (обязательное поле NAME - имя нового файла);
+ `$isBase64FileData` - RAW данные файла закодированны BASE64.


```php
use \App\Bitrix24\Bitrix24API;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Загружаем список доступных хранилищ
    $generator = $bitrix->getDiskStorageList();
    foreach ($generator as $storages) {
        foreach ($storages as $storage) {
            print_r($storage);
        }
    }

    // Загружаем список файлов и папок, которые находятся непосредственно в корне хранилища
    $files = $bitrix->getDiskStorageChildren($storageId = 2);
    foreach ($files as $file) {
        print_r($file);
    }

    // Загружаем файл в указанную папку на Диск
    $bx24->uploadfileDiskFolder(
        $filderId = 4709,
        $rawFile = file_get_contents('./schet.pdf'),
        [ 'NAME' => 'schet.pdf' ],
        $isBase64FileData = false
    );

} catch (\App\Bitrix24\Bitrix24APIException | \App\AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D1%8B"></a>
## Вспомогательные классы

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-apphttp"></a>
### Класс `\App\HTTP`

Класс `\App\HTTP` обеспечивает:

- формирование POST запросов к API Битрикс 24 по протоколу HTTPS;
- троттлинг запросов к API на требуемом уровне - [не более 2-х запросов в секунду](https://dev.1c-bitrix.ru/rest_help/rest_sum/index.php);
- вывод отладочной информации о запросах к API в STDOUT.

При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`.  
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

```php
use \App\Bitrix24\Bitrix24API;
use \App\HTTP;

$webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
$bx24 = new Bitrix24API($webhookURL);

// Устанавливаем максимальный уровень вывода отладочных сообщений в STDOUT
$bx24->http->debugLevel = HTTP::DEBUG_URL |  HTTP::DEBUG_HEADERS | HTTP::DEBUG_CONTENT;

// Устанавливаем троттлинг запросов на уровне не более 1 запроса в 2 секунды
$bx24->http->throttle = 0.5;

// Устанавливаем таймаут соединения в 30 секунд
$bx24->http->curlTimeout = 30;
```

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-appdebuglogger"></a>
### Класс `\App\DebugLogger`

Класс `\App\DebugLogger` обеспечивает логирование запросов и ответов к API в файл.  
При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`. 

Список методов класса:

- `static instance(string $logFileName = 'debug.log') :\App\DebugLogger` Возвращает объект класса.
    + `$logFileName` - имя лог файла.
- `save(mixed $info, $object = null) :void` Сохраняет подлежащую логированию информацию в файл.
    + $info - строка, массив или объект для логирования;
    + $object - ссылка на объект класса в котором выполняется логирование.

Дополнительные параметры логирования доступы через публичные свойства класса `\App\DebugLogger`.

Свойство                | По умолчанию  | Описание
----------------------- | ------------- | --------
`$isActive`             | false         | Включает или выключает логирование
`$logFileDir`           | `temp/`       | Устанавливает каталог в котором сохраняются лог файлы



```php
use \App\Bitrix24\Bitrix24API;
use \App\DebugLogger;

$webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
$bx24 = new Bitrix24API($webhookURL);

$logFileName = 'debug_bitrix24api.log'
$bx24->logger = DebugLogger::instance($logFileName);

// Устанавливаем каталог для сохранения лог файлов
$bx24->logger->logFileDir = 'logs/';

// Включаем логирование
$bx24->logger->isActive = true;

```

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80"></a>
## Автор

© 2019-2020 andrey-tech

<a id="%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F"></a>
## Лицензия

Данная библиотека распространяется на условиях лицензии [MIT](./LICENSE).
