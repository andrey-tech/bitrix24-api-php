# Bitrix24 API PHP Wrapper

![Bitrix24 logo](assets/bitrix24-logo.png)  

[![Latest Stable Version](https://poser.pugx.org/andrey-tech/bitrix24-api-php/v)](https://packagist.org/packages/andrey-tech/bitrix24-api-php)
[![Total Downloads](https://poser.pugx.org/andrey-tech/bitrix24-api-php/downloads)](https://packagist.org/packages/andrey-tech/bitrix24-api-php)
[![GitHub stars](https://img.shields.io/github/stars/andrey-tech/bitrix24-api-php)](https://github.com/andrey-tech/bitrix24-api-php/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/andrey-tech/bitrix24-api-php)](https://github.com/andrey-tech/bitrix24-api-php/network)
[![GitHub watchers](https://img.shields.io/github/watchers/andrey-tech/bitrix24-api-php)](https://github.com/andrey-tech/bitrix24-api-php/watchers)
[![License](https://poser.pugx.org/andrey-tech/bitrix24-api-php/license)](https://packagist.org/packages/andrey-tech/bitrix24-api-php)

Обертка на PHP7+ для работы с [REST API Битрикс24](https://dev.1c-bitrix.ru/rest_help/) с использованием механизма [входящих вебхуков](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=99&LESSON_ID=8581), 
троттлингом запросов и логированием в файл.

Разработчики на JavaScript могут воспользоваться классом-оберткой [andrey-tech/bx24-wrapper-js](https://github.com/andrey-tech/bx24-wrapper-js).

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Установка](#%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0)
- [Класс `Bitrix24API`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-bitrix24api)
    - [Базовые методы класса](#%D0%91%D0%B0%D0%B7%D0%BE%D0%B2%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
    - [Дополнительные параметры](#%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B)
- [Методы для работы с сущностями Битрикс24](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124)
    - [Методы работы со сделками](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с контактами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с компаниями](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8)
    - [Методы для работы с каталогами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с товарами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%D0%BC%D0%B8)
    - [Методы работы с разделами товаров](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%80%D0%B0%D0%B7%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BE%D0%B2)
    - [Методы работы с товарными позициями](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BD%D1%8B%D0%BC%D0%B8-%D0%BF%D0%BE%D0%B7%D0%B8%D1%86%D0%B8%D1%8F%D0%BC%D0%B8)
    - [Методы для работы с пользователями](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F%D0%BC%D0%B8)
    - [Методы работы с задачами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с делами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с диском](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B8%D1%81%D0%BA%D0%BE%D0%BC)
    - [Методы для работы с лидами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BB%D0%B8%D0%B4%D0%B0%D0%BC%D0%B8)
- [Вспомогательные классы](#%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D1%8B)
    - [Класс `HTTP`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-http)
        - [Дополнительные параметры](#%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B-1)
        - [Примеры](#%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B)
    - [Класс `DebugLogger`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-debuglogger)
        - [Методы класса](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
        - [Дополнительные параметры](#%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B-2)
        - [Примеры](#%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B-1)
            - [Формат заголовков лога](#%D0%A4%D0%BE%D1%80%D0%BC%D0%B0%D1%82-%D0%B7%D0%B0%D0%B3%D0%BE%D0%BB%D0%BE%D0%B2%D0%BA%D0%BE%D0%B2-%D0%BB%D0%BE%D0%B3%D0%B0)
- [Автор](#%D0%90%D0%B2%D1%82%D0%BE%D1%80)
- [Лицензия](#%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F)

<!-- /MarkdownTOC -->

<a id="%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F"></a>
## Требования

- PHP >= 7.0;
- класс [`HTTP`](https://github.com/andrey-tech/http-client-php) >= 3.0 - НТТР(S) клиент с троттлингом запросов, поддержкой маркера BOM в теле сообщения формата JSON и выводом отладочной информации о запросах и ответах в STDOUT;
- класс [`DebugLogger`](https://github.com/andrey-tech/debug-logger-php) >= 2.0 - логгер, сохраняющий отладочную информацию в файл вместе с данными об объеме используемой оперативной памяти и прошедшем времени;
- произвольный автозагрузчик классов, реализующий стандарт [PSR-4](https://www.php-fig.org/psr/psr-4/), необходимый в том случае, если не используется Composer.


<a id="%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0"></a>
## Установка

Установка через composer:
```
$ composer require andrey-tech/bitrix24-api-php:"^1.6"
```

или добавить

```
"andrey-tech/bitrix24-api-php": "^1.6"
```

в секцию require файла composer.json.

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-bitrix24api"></a>
## Класс `Bitrix24API`

Для работы с REST API Битрикс24 используется класс `\App\Bitrix24\Bitrix24API`.  
При возникновении ошибок выбрасывается исключение класса `\App\Bitrix24\Bitrix24APIException`.  
В настоящее время в классе реализованы методы для работы со следующими сущностями Битрикс24:

- [Сделки](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81%D0%BE-%D1%81%D0%B4%D0%B5%D0%BB%D0%BA%D0%B0%D0%BC%D0%B8)
- [Контакты](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B0%D0%BC%D0%B8)
- [Компании](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8)
- [Каталоги товаров](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3%D0%B0%D0%BC%D0%B8)
- [Товары](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%D0%BC%D0%B8)
- [Разделы товаров](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%80%D0%B0%D0%B7%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BE%D0%B2)
- [Товарные позиции](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BD%D1%8B%D0%BC%D0%B8-%D0%BF%D0%BE%D0%B7%D0%B8%D1%86%D0%B8%D1%8F%D0%BC%D0%B8)
- [Задачи](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B0%D0%BC%D0%B8)
- [Дела](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8)
- [Пользователи](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F%D0%BC%D0%B8)
- [Диск](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B8%D1%81%D0%BA%D0%BE%D0%BC)
- [Лиды](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BB%D0%B8%D0%B4%D0%B0%D0%BC%D0%B8)

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
- `setLogger($logger)` Устанавливает объект класса, выполняющего логирование отладочной информации в файл.

Параметры методов:

+ `$webhookURL` - URL входящего вебхука;
+ `$function` - имя метода (функции) запроса;
+ `$params` - параметры запроса;
+ `$commands` - пакет команд;
+ `$items` - массив полей запросов;
+ `$halt` - прерывать последовательность запросов в случае ошибки;
+ `$logger` - объект класса, реализующего интерфейс `\App\DebugLogger\DebugLoggerInterface`.

<a id="%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B"></a>
### Дополнительные параметры

Дополнительные параметры настройки доступны через публичные статические и нестатические свойства класса `\App\Bitrix24\Bitrix24API`:

Нестатическое свойство  | По умолчанию       | Описание
----------------------- | ------------------ | --------
`$batchSize`            | 50                 | Устанавливает количество команд в одном пакете запросов (batch)
`$logger`               | null               | Хранит объект класса `\App\DebugLogger\DebugLogger`, выполняющего логирование запросов и ответов к API в файл. Если null, то логирование не выполняется.
`$http`                 | `\App\HTTP\HTTP`   | Хранит объект класса `\App\HTTP\HTTP`, отправляющего запросы к API

Статическое свойство    | По умолчанию         | Описание
----------------------- | -------------------- | --------
`$WITH_CONTACTS`        | 'CONTACTS'           | Имя поля для массива возвращаемых связанных сущностей типа контакт
`$WITH_COMPANIES`       | 'COMPANIES'          | Имя поля для массива возвращаемых связанных сущностей типа компания
`$WITH_PRODUCTS`        | 'PRODUCTS'           | Имя поля для массива возвращаемых связанных сущностей типа товар

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%81%D1%83%D1%89%D0%BD%D0%BE%D1%81%D1%82%D1%8F%D0%BC%D0%B8-%D0%91%D0%B8%D1%82%D1%80%D0%B8%D0%BA%D1%8124"></a>
## Методы для работы с сущностями Битрикс24

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
   Пакетно добавляет сделки со связанными товарными позициями, возвращает массив ID сделок.
- `updateDeals(array $deals = [], array $params = []) :array`  
    Пакетно обновляет сделки со связанными товарными позициями, возвращает массив ID сделок.
- `deleteDeals(array $dealIds = []) :array` Пакетно удаляет сделки, возвращает массив ID сделок.
- `setDealFile($dealId, $userFieldId, string $fileName, string $fileContent, bool $isBase64FileData = true) :int`  
    Устанавливает файл в НЕ множественное пользовательское поле типа файл (файл нельзя удалить) и возвращает ID сделки.
- `setDealFiles($dealId, $userFieldId, array $files = [], bool $isBase64FileData = true) :int`  
    Устанавливает файлы во множественное пользовательское поле типа файл (файлы можно удалить) и возвращает ID сделки.
- `getDealContactItems($dealId) :array` Возвращает массив параметров контактов, связанных со сделкой.
- `setDealContactItems($dealId, array $contacts) :array` Устанавливает контакты, связанные со сделкой.
- `setDealProductRows($dealId, array $products) :array` Устанавливает товарные позиции, связанные со сделкой.
- `getDealProductRows($dealId) :array` Возвращает массив параметров товарных позиций, связанных со сделкой.
- `getDealFields() :array` Возвращает описание полей сделки, в том числе пользовательских.

Параметры методов:

+ `$dealId` - ID сделки;
+ `$dealIds` - массив ID сделок;
+ `$with` - имена связанных сущностей, возвращаемых вместе со сделкой:
    * `\App\Bitrix24\Bitrix24API::$WITH_CONTACTS` - контакты (возвращаются в виде массива в поле с именем, заданным публичным статическим свойством `Bitrix24API::$WITH_CONTACTS`);
    * `\App\Bitrix24\Bitrix24API::$WITH_PRODUCTS` - товарные позиции (возвращаются в виде массива в поле с именем, заданным публичным статическим свойством `Bitrix24API::$PRODUCTS`);
- `$fields` - набор полей сделки;
- `$params` - набор параметров сделки;
- `$filter` - параметры фильтрации;
- `$order` - параметры сортировки;
- `$select` - параметры выборки полей;
- `$userFieldId` ID НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX');
- `$files` - массив параметров файлов ([ [  < Имя файла >, < RAW данные файла > ], ... ]) (пустой массив для удаления всех файлов);
- `$isBase64FileData` - RAW данные файла закодированы в BASE64?;
- `$contacts` - массив параметров контактов;
- `$products` - массив параметров товарных позиций.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

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
        [ 'PRODUCT_ID' => 1689, 'PRICE' => 1500.00, 'QUANTITY' => 2 ],
        [ 'PRODUCT_ID' => 1860, 'PRICE' => 500.00, 'QUANTITY' => 15 ]
    ]);

    // Обновляем существующую сделку
    $bx24->updateDeal($dealId, [
        'TITLE' => 'Новая сделка №12'
    ]);


    // При необходимости, изменяем значение по умолчанию 'PRODUCTS' на '_PRODUCTS' для имени поля
    // со списком товарных позиций, возвращаемых вместе со сделкой
    Bitrix24API::$WITH_PRODUCTS = '_PRODUCTS';

    // Загружаем сделку по ID вместе со связанными товарами и контактами одним запросом
    $deal = $bx24->getDeal($dealId, [ Bitrix24API::$WITH_PRODUCTS, Bitrix24API::$WITH_CONTACTS ]);
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
    print_r($dealIds);

    // Пакетно удаляем сделки
    $bx24->deleteDeals($dealIds);

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с контактами

Методы для работы с контактами находятся в трейте `\App\Bitrix24\Contact`:

- `getContact($contactId, array $with = []) :array` Возвращает контакт по его ID.
- `addContact(array $fields = [], array $params = []) :int` Добавляет контакт и возвращает его ID.
- `updateContact($contactId, array $fields = [], array $params = []) :int` Обновляет контакт и возвращает его ID.
- `deleteContact($contactId) :int` Удаляет контакт и возвращает его ID.
- `getContactList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все контакты с возможностью фильтрации, сортировки и выборки полей.
- `fetchContactList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все контакты с возможностью фильтрации, сортировки и выборки полей.  
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.    
- `getContactsByPhone(int|string $phone, $select = []) :array` Возвращает контакты по номеру телефона.
- `addContacts(array $contacts = [], array $params = []) :array` Пакетно добавляет контакты.
- `updateContacts(array $contacts = [], array $params = []) :array` Пакетно обновляет контакты.
- `deleteContacts(array $contactIds = []) :array` Пакетно удаляет контакты.
- `getContactCompanyItems($contactId) :array` Возвращает компании, связанные с контактом по ID.
- `setContactCompanyItems($contactId, array $companies) :array` Устанавливает компании, связанные с контактом по ID.
- `getContactFields() :array` Возвращает описание полей контакта.

Параметры методов:

+ `$contaxctId` - ID контакта;
+ `$contactIds` - массив ID сделок;
+ `$phone` - номер телефона;
+ `$with` - имена связанных сущностей, возвращаемых вместе с контактом:
    * `\App\Bitrix24\Bitrix24API::$WITH_COMPANIES` - компании (возвращаются в виде массива в поле с именем, заданным публичным статическим свойством `Bitrix24API::$WITH_COMPANIES`);
- `$fields` - набор полей сделки;
- `$params` - набор параметров сделки;
- `$filter` - параметры фильтрации;
- `$order` - параметры сортировки;
- `$select` - параметры выборки полей;
- `$contacts` - массив параметров контактов;
- `$companies` - массив параметров компаний.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Добавляем новый контакт
    $contactId = $bx24->addContact([
        'NAME'        => 'Иван',
        'COMPANY_ID'  => 332,
        'SECOND_NAME' => 'Васильевич',
        'LAST_NAME'   => 'Петров'
    ]);
    print_r($contactId);

    // Устанавливаем набор связанных компаний
    $bx24->setContactCompanyItems($contactId, [
        [ 'COMPANY_ID' => 8483 ],
        [ 'CONPANY_ID' => 4094 ]
    ]);

    // Обновляем существующий контакт
    $bx24->updateContact($contactId, [
        'NAME' => 'Фёдор'
    ]);

    // Загружаем контакт по ID вместе со связанными компаниями
    $contact = $bx24->getContact($contactId, [ Bitrix24API::$WITH_COMPANIES ]);
    print_r($contact);

    // Удаляем существующий контакт
    $bx24->deleteContact($contactId);

    // Загружаем все контакты используя быстрый метод при работе с большими объемами данных
    $generator = $bx24->fetchContactList();
    foreach ($generator as $contacts) {
        foreach($contacts as $contact) {
            print_r($contact);
        }
    }

    // Пакетно добавляем контакты
    $contactIds = $bx24->addContacts([
        [
            'NAME'        => 'Владимир',
            'COMPANY_ID'  => 3322,
            'SECOND_NAME' => 'Вадимович',
            'LAST_NAME'   => 'Владимиров'
        ],
        [
            'NAME'        => 'Андрей',
            'COMPANY_ID'  => 1332,
            'SECOND_NAME' => 'Васильевич',
            'LAST_NAME'   => 'Иванов'
        ]
    ]);
    print_r($contactIds);

    // Пакетно удаляем контакты
    $bx24->deleteContacts($contactIds);

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8"></a>
### Методы для работы с компаниями

Методы для работы с компаниями находятся в трейте `\App\Bitrix24\Company`:

- `getCompany($companyId, array $with = [])` Возвращает компанию по ID.
- `addCompany(array $fields = [], array $params = []) :int` Добавляет компанию и возвращает ее ID.
- `updateCompany($companyId, array $fields = [], array $params = []) :int` Обновляет компанию и возвращает ее ID.
- `deleteCompany($companyId) :int` Удаляет компанию и возвращает ее ID.
- `getCompanyList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все компании с возможностью фильтрации, сортировки и выборки полей.
- `fetchCompanyList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все компании с возможностью фильтрации, сортировки и выборки полей.  
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.    
- `addCompanies(array $companies = [], array $params = []) :array` Пакетно добавляет компании.
- `updateCompanies(array $companies = [], array $params = []) :array` Пакетно обновляет компании.
- `deleteCompanies(array $companyIds = []) :array` Пакетно удаляет компании.
- `getCompanyContactItems($companyId) :array` Возвращает контакты, связанные с компанией.
- `setCompanyContactItems($companyId, array $contacts) :array` Устанавливает контакты, связанные с компанией.

Параметры методов:

- `$companyId` - ID компании;
- `$companyIds` - массив ID компаний;
+ `$with` - имена связанных сущностей, возвращаемых вместе с компанией:
    * `\App\Bitrix24\Bitrix24API::$WITH_CONTACTS` - контакты (возвращаются в виде массива в поле с именем, заданным публичным статическим свойством `Bitrix24API::$WITH_CONTACTS`);
+ `$filter` - параметры фильтрации;
+ `$order` - параметры сортировки;
+ `$select` - параметры выборки полей;
- `$contacts` - массив параметров контактов;
- `$companies` - массив параметров компаний.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Добавляем новую компанию
    $companyId = $bx24->addCompany([
        'TITLE' => 'OOO Рога и Копыта'
    ]);
    print_r($companyId);

    // Устанавливаем набор связанных контактов
    $bx24->setCompanyContactItems($companyId, [
        [ 'CONTACT_ID' => 4838 ],
        [ 'CONTACT_ID' => 8583 ]
    ]);

    // Обновляем существующую компанию
    $bx24->updateCompany($companyId, [
        'TITLE' => 'ИП Рога и Копыта'
    ]);

    // Загружаем компанию по ID вместе со связанными контактами
    $company = $bx24->getCompany($companyId, [ Bitrix24API::$WITH_CONTACTS ]);
    print_r($company);

    // Удаляем существующую компанию
    $bx24->deleteCompany($companyId);

    // Загружаем все компании используя быстрый метод при работе с большими объемами данных
    $generator = $bx24->fetchCompanyList();
    foreach ($generator as $companies) {
        foreach($companies as $company) {
            print_r($company);
        }
    }

    // Пакетно добавляем компании
    $companyIds = $bx24->addCompanies([
        [ 'TITLE' => 'ПАО Абракадабра' ],
        [ 'TITLE' => 'ЗАО Моя компания' ]
    ]);
    print_r($companyIds);

    // Пакетно удаляем компании
    $bx24->deleteCompanies($companyIds);

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с каталогами

Методы для работы с товарными каталогами находятся в трейте `\App\Bitrix24\Catalog`:

- `getCatalogList(array $filter = [], array $select = [], array $order = []) :\Generator`   
    Загружает все каталоги с возможностью фильтрации, сортировки и выборки полей.
- `fetchCatalogList(array $filter = [], array $select = [], array $order = []) :\Generator`  
    Загружает все каталоги с возможностью фильтрации, сортировки и выборки полей.  
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.    
- `getCatalogFields() :array` Возвращает описание полей каталога товаров.

Параметры методов:

+ `$filter` - параметры фильтрации;
+ `$order` - параметры сортировки;
+ `$select` - параметры выборки полей.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

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

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
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
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

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

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
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
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

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

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%BD%D1%8B%D0%BC%D0%B8-%D0%BF%D0%BE%D0%B7%D0%B8%D1%86%D0%B8%D1%8F%D0%BC%D0%B8"></a>
### Методы работы с товарными позициями

Методы для работы с товарными позициями находятся в трейте `\App\Bitrix24\ProductRow`:

- `getProductRowFields() :array` Возвращает описание полей товарных позиций.


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
+ `$sort` - поле, по которому сортируются результаты;
+ `$select` - параметры выборки полей;
+ `$adminMode` - включает режим администратора для получения данных о любых пользователях.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

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

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B0%D0%BC%D0%B8"></a>
### Методы работы с задачами

Методы для работы с задачами находятся в трейте `\App\Bitrix24\Task`:

- `getTask($taskId, array $select = []) :?array` Возвращает задачу по ID.
- `getTaskList(array $filter = [], array $select = [], array $order = []): Generator` Возвращает все задачи.
- `addTask(array $fields = []) :int` Добавляет новую задачу.
- `addTasks(array $tasks = []) :array` Пакетно добавляет задачи.
- `getTaskFields() :array` Возвращает описание полей задачи.

Параметры методов:

+ `$taskId` - ID задачи;
+ `$filter` - параметры фильтрации;
+ `$select` - параметры выборки полей;
+ `$order` - параметры сортировки;
+ `$fields` - набор полей задачи;
+ `$tasks` - массив наборов полей задач.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Получаем задачу по ID
    $task = $bx24->getTask(4325);
    print_r($task);

    // Получаем все задачи 
    $generator = $bx24->getTaskList();
    foreach ($generator as $result) {
        print_r($result);
    }

    // Создаем новую задачу
    $taskId = $bx24->addTask([
        'TITLE'           => 'Новая задача №123', // Название задачи
        'DESCRIPTION'     => 'Описание задачи', // Описание задачи
        'RESPONSIBLE_ID'  => 43242, // ID ответственного пользователя
        'UF_CRM_TASK'     => [ 'D_' . 38492 ], // Привязка задачи к сделке ('D_' - сущность сделка, 38492 - ID сделки)
        'START_DATE_PLAN' => '09.08.2005', // Плановая дата начала.
        'END_DATE_PLAN'   => '09.09.2005', // Плановая дата завершения
        'DEADLINE'        => '2005-09-09T18:31:42+03:30' // Крайний срок
    ]);
    print_r($taskId);

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
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
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

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

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B4%D0%B8%D1%81%D0%BA%D0%BE%D0%BC"></a>
### Методы для работы с диском

Методы для работы с Диском находятся в трейте `\App\Bitrix24\Disk`:

-  `getDiskStorageList(array $filter = []) :\Generator`  
    Загружает список доступных хранилищ с возможностью фильтрации.
- `getDiskStorageChildren($storageId, array $filter = []) :array`  
    Возвращает список файлов и папок, которые находятся непосредственно в корне хранилища с возможностью фильтрации.
- `uploadfileDiskFolder($folderId, string $fileContent, array $data, bool $isBase64FileData = true) :array`
    Загружает новый файл в указанную папку на Диск.

Параметры методов:

+ `$filter` - параметры фильтрации;
+ `$storageId` - ID хранилища;
+ `$filter` - параметры фильтрации;
+ `$folderId` - ID папки;
+ `$fileContent` - RAW данные файла;
+ `$data` - набор параметров, описывающих файл (обязательное поле NAME - имя нового файла);
+ `$isBase64FileData` - RAW данные файла закодированы в BASE64?

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Загружаем список доступных хранилищ
    $generator = $bx24->getDiskStorageList();
    foreach ($generator as $storages) {
        foreach ($storages as $storage) {
            print_r($storage);
        }
    }

    // Загружаем список файлов и папок, которые находятся непосредственно в корне хранилища
    $files = $bx24->getDiskStorageChildren($storageId = 2);
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

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BB%D0%B8%D0%B4%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с лидами

Методы для работы с лидами находятся в трейте `\App\Bitrix24\Lead`:

- `getLeadFields() :array` Возвращает описание полей лида, в том числе пользовательских.
- `getLead($leadId, array $with = []) :array` Возвращает лид по его ID.
- `addLead(array $fields = [], array $params = []) :int` Добавляет лид и возвращает его ID.
- `updateLead($leadId, array $fields = [], array $params = []) :int` Обновляет лид и возвращает его ID.
- `deleteLead($leadId) :int` Удаляет лид по его ID.
- `getLeadList(array $filter = [], array $select = [], array $order = []): Generator`  
    Загружает все лиды с возможностью фильтрации, сортировки и выборки полей.
- `fetchLeadList(array $filter = [], array $select = [], array $order = []): Generator`  
    Загружает все лиды с возможностью фильтрации, сортировки и выборки полей.  
    Реализует [быстрый метод](https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php) загрузки при работе с большими объемами данных.
- `getLeadProductRows($leadId) :array` Возвращает массив параметров товарных позиций, связанных с лидом.
- `setLeadProductRows($leadId, array $products) :array` Устанавливает товарные позиции, связанные с лидом.

Параметры методов:

+ `$leadId` - ID лида;
+ `$with` - имена связанных сущностей, возвращаемых вместе с лидом:
    * `\App\Bitrix24\Bitrix24API::$WITH_PRODUCTS` - товарные позиции (возвращаются в виде массива в поле с именем, заданным публичным статическим свойством `Bitrix24API::$PRODUCTS`);
- `$fields` - набор полей лида;
- `$params` - набор параметров лида;
- `$filter` - параметры фильтрации;
- `$order` - параметры сортировки;
- `$select` - параметры выборки полей;
- `$products` - массив параметров товарных позиций.

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

try {

    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Добавляем новый лид
    $leadId = $bx24->addLead([
        'TITLE'      => 'Новый лид №1'
    ]);
    print_r($leadId);

    // Устанавливаем набор связанных товарных позиций
    $bx24->setLeadProductRows($leadId, [
        [ 'PRODUCT_ID' => 1689, 'PRICE' => 1500.00, 'QUANTITY' => 2 ],
        [ 'PRODUCT_ID' => 1860, 'PRICE' => 500.00, 'QUANTITY' => 15 ]
    ]);

    // Обновляем существующий лид
    $bx24->updateLead($leadId [
        'TITLE' => 'Новый лид №12'
    ]);

    // При необходимости, изменяем значение по умолчанию 'PRODUCTS' на '_PRODUCTS' для имени поля
    // со списком товарных позиций, возвращаемых вместе с лидом
    Bitrix24API::$WITH_PRODUCTS = '_PRODUCTS';

    // Загружаем лид по ID вместе со связанными товарными позициями
    $lead = $bx24->getLead($leadId, [ Bitrix24API::$WITH_PRODUCTS ]);
    print_r($lead);

    // Удаляем существующий лид
    $bx24->deleteLead($leadId);

    // Загружаем все лиды используя быстрый метод при работе с большими объемами данных
    $generator = $bx24->fetchLeadList();
    foreach ($generator as $leads) {
        foreach($leads as $lead) {
            print_r($lead);
        }
    }

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}    
```    

<a id="%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D1%8B"></a>
## Вспомогательные классы

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-http"></a>
### Класс `HTTP`

Класс [`\App\HTTP\НТТР`](https://github.com/andrey-tech/http-client-php) обеспечивает:

- формирование POST запросов к API Битрикс 24 по протоколу HTTPS;
- троттлинг запросов к API на требуемом уровне - [не более 2-х запросов в секунду](https://dev.1c-bitrix.ru/rest_help/rest_sum/index.php);
- вывод отладочной информации о запросах к API в STDOUT.

При возникновении ошибок выбрасывается исключение класса `\App\HTTP\HTTPException`.

<a id="%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B-1"></a>
#### Дополнительные параметры

Дополнительные параметры устанавливаются через публичные свойства объекта класса `\App\HTTP\HTTP`:

Свойство                | По умолчанию            | Описание
----------------------- | ----------------------- | --------
`$debugLevel`           | `\App\HTTP\HTTP::DEBUG_NONE` | Устанавливает уровень вывода отладочной информации о запросах в STDOUT (битовая маска, составляемая из значений DEBUG_NONE, DEBUG_URL, DEBUG_HEADERS, DEBUG_CONTENT)
`$throttle`             | 0                       | Максимальное число HTTP запросов в секунду (0 - троттлинг отключен)
`$addBOM`               | false                   | Добавлять [маркер ВОМ](https://ru.wikipedia.org/wiki/%D0%9C%D0%B0%D1%80%D0%BA%D0%B5%D1%80_%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%D0%B4%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8_%D0%B1%D0%B0%D0%B9%D1%82%D0%BE%D0%B2) UTF-8 (EFBBBF) к запросам в формате JSON
`$useCookies`           | false                   | Использовать cookies в запросах
`$cookieFile`           | 'temp/cookies.txt'      | Путь к файлу для хранения cookies
`$verifySSLCertificate` | true                    | Включить проверку SSL/TLS-сертификата сервера
`$SSLCertificateFile`   | 'cacert.pem'            | Устанавливает файл SSL/TLS-сертификатов X.509 корневых удостоверяющих центров (CA) в формате РЕМ (установка в null означает использовать файл, указанный в параметре [curl.cainfo](https://www.php.net/manual/ru/curl.configuration.php) файла php.ini)
`$userAgent`            | 'HTTP-client/3.x.x'     | Устанавливает НТТР заголовок UserAgent в запросах
`$curlConnectTimeout`   | 60                      | Устанавливает таймаут соединения, секунды
`$curlTimeout`          | 60                      | Устанавливает таймаут обмена данными, секунды
`$successStatusCodes`   | [ 200 ]                 | Коды статуса НТТР, соответствующие успешному выполнению запроса


<a id="%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B"></a>
#### Примеры

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;
use App\HTTP\HTTP;

try {
    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Устанавливаем максимальный уровень вывода отладочных сообщений в STDOUT
    $bx24->http->debugLevel = HTTP::DEBUG_URL |  HTTP::DEBUG_HEADERS | HTTP::DEBUG_CONTENT;

    // Устанавливаем троттлинг запросов на уровне не более 1 запроса в 2 секунды
    $bx24->http->throttle = 0.5;

    // Устанавливаем таймаут обмена данными в 30 секунд
    $bx24->http->curlTimeout = 30;

    // Получаем компанию по ID
    $results = $bx24->getCompany(20);

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

Примеры отладочных сообщений:
```
[1] ===> POST https://www.example.com/rest/1/u7ngxagzrhpuj31a/crm.company.get.json
POST rest/1/u7ngxagzrhpuj31a/crm.company.get.json HTTP/1.1
Host: www.example.com
User-Agent: HTTP-client/2.x.x
Accept: */*
Content-Length: 5
Content-Type: application/x-www-form-urlencoded

id=20

[1] <=== RESPONSE 0.5348s (200)
HTTP/1.1 200 OK
Server: nginx/1.16.1
Date: Mon, 15 Jun 2020 13:11:33 GMT
Content-Type: application/json; charset=utf-8
Transfer-Encoding: chunked
Connection: keep-alive
P3P: policyref="/bitrix/p3p.xml", CP="NON DSP COR CUR ADM DEV PSA PSD OUR UNR BUS UNI COM NAV INT DEM STA"
X-Powered-CMS: Bitrix Site Manager (bc2cad9153cb418bb2dfd5602c3c3754)
Set-Cookie: PHPSESSID=uSBxTO1tiaVfYPd7I7BhvjPLc2H2RhuD; path=/; secure; HttpOnly
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Set-Cookie: qmb=.; path=/
Access-Control-Allow-Origin: *
Access-Control-Allow-Headers: origin, content-type, accept
X-Content-Type-Options: nosniff
X-Bitrix-Rest-Time: 0.0098488331
X-Bitrix-Rest-User-Time: 0.0042990000
X-Bitrix-Rest-System-Time: 0.0000030000
Set-Cookie: BITRIX_SM_SALE_UID=4; expires=Thu, 10-Jun-2021 13:11:33 GMT; Max-Age=31104000; path=/
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
Strict-Transport-Security: max-age=31536000; includeSubdomains
X-Bitrix-Times: 0.104/0.104/0.000
X-Bitrix-TCP: 32250/6750/20/14480
X-Bitrix-RI: 3b51dd618cb995cfc06d2016cc4c0c94
X-Bitrix-LB: lb-ru-04

{"result":{"ID":"20","COMPANY_TYPE":"CUSTOMER","LOGO":null,"LEAD_ID":null,"HAS_PHONE":"N","HAS_EMAIL":"Y"}}
```

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-debuglogger"></a>
### Класс `DebugLogger`

Класс [`\App\DebugLogger\DebugLogger`](https://github.com/andrey-tech/debug-logger-php) обеспечивает логирование запросов и ответов к API Битрикс24 в файл.  
При возникновении ошибок выбрасывается исключение класса `\App\DebugLogger\DebugLoggerException`. 

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0"></a>
#### Методы класса

- `static instance(string $logFileName = 'debug.log') :self`  
    Возвращает единственный объект данного класса **для заданного лог-файла** `$logFileName`.
    + `$logFileName` - имя лог-файла.
- `save(mixed $info, object $object = null, string $header = null) :void` Сохраняет подлежащую логированию информацию в файл.
    + `$info` - строка, массив или объект для логирования;
    + `$object` - ссылка на объект класса в котором выполняется логирование;
    + `$header` - строка заголовка для сохраняемой в лог файл информации.

<a id="%D0%94%D0%BE%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BF%D0%B0%D1%80%D0%B0%D0%BC%D0%B5%D1%82%D1%80%D1%8B-2"></a>
#### Дополнительные параметры

Дополнительные параметры устанавливаются через публичные свойства класса `\App\DebugLogger\DebugLogger`:

Нестатическое свойство  | По умолчанию  | Описание
----------------------- | ------------- | --------
`$isActive`             | false         | Включает или выключает логирование для конкретного файла, задаваемого параметром `$logFileName` метода `instance()`

Статическое свойство    | По умолчанию  | Описание
----------------------- | ------------- | --------
`$logFileDir`           | `temp/`       | Устанавливает каталог, в котором сохраняются лог-файлы
`mkdirMode`             | 0755          | Устанавливает режим доступа для создаваемых каталогов для хранения лог файлов в виде восьмеричного числа
`$uniqIdLength`         | 7             | Длина уникального буквенно-цифрового [a-z0-9]+ идентификатора объекта класса `DebugLogger` для сохранения в лог файле,  позволяющего находить записи, созданные одним и тем же процессом


<a id="%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B-1"></a>
#### Примеры

```php
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;
use App\DebugLogger\DebugLogger;

try {
    $webhookURL = 'https://www.example.com/rest/1/u7ngxagzrhpuj31a/';
    $bx24 = new Bitrix24API($webhookURL);

    // Устанавливаем каталог для сохранения лог файлов
    DebugLogger::$logFileDir = 'logs/';

    // Создаем объект класса логгера
    $logFileName = 'debug_bitrix24api.log';
    $logger = DebugLogger::instance($logFileName);

    // Включаем логирование
    $logger->isActive = true;

    // Устанавливаем логгер
    $bx24->setLogger($logger);

    // Загружаем все компании
    $bx24->fetchCompanyList();

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

Пример результатов логирования:

```
*** 92qshr5 [2020-06-14 13:32:44.993285 +00:00 Δ0.012308 s, 0.70/2.00 MiB] ********************
* Class: App\Bitrix24\Bitrix24API
ЗАПРОС: crm.company.list.json
{
    "order": {
        "ID": "ASC"
    },
    "filter": {
        ">ID": 0
    },
    "select": [],
    "start": -1
}


*** 92qshr5 [2020-06-14 13:32:46.900518 +00:00 Δ1.907233 s, 1.14/2.00 MiB] ********************
ОТВЕТ: crm.company.list.json
{
    "result": [
        {
            "ID": "2",
            "COMPANY_TYPE": "PARTNER",
            "TITLE": "ООО",
            "LOGO": {
                "id": 112,
                "showUrl": "\/bitrix\/components\/bitrix\/crm.company.show\/show_file.php?ownerId=2",
                "downloadUrl": "\/bitrix\/components\/bitrix\/crm.company.show\/show_file.php?auth=&ownerId=2"
            }
        }
    }
}

*** 92qshr5 [2020-06-14 13:32:46.902085 +00:00 Δ0.001567 s, 1.30/2.00 MiB] ********************
* Class: App\Bitrix24\Bitrix24API
По запросу (fetchList) crm.company.list получено сущностей: 50, всего получено: 50
```

<a id="%D0%A4%D0%BE%D1%80%D0%BC%D0%B0%D1%82-%D0%B7%D0%B0%D0%B3%D0%BE%D0%BB%D0%BE%D0%B2%D0%BA%D0%BE%D0%B2-%D0%BB%D0%BE%D0%B3%D0%B0"></a>
##### Формат заголовков лога

```
*** 92qshr5 [2020-06-14 13:32:46.902085 +00:00 Δ0.001567 s, 1.30/2.00 MiB] ********************
* Class: App\Bitrix24\Bitrix24API
```

- `92qshr5` - уникальный буквенно-цифровой [a-z0-9]+ идентификатор объекта класса `DebugLogger`, позволяющий находить в лог файле записи, созданные одним и тем же процессом;
- `2020-06-14 13:32:46.902085` - дата и время сохранения информации с точностью до микросекунд;
- `Δ0.001567 s` - время, прошедшее с момента предыдущего сохранения информации в секундах и микросекундах;
- `1.30/2.00 MiB` - данные об используемой оперативной памяти в единицах количества информации с [двоичными приставками](https://ru.wikipedia.org/wiki/%D0%94%D0%B2%D0%BE%D0%B8%D1%87%D0%BD%D1%8B%D0%B5_%D0%BF%D1%80%D0%B8%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B8):
    + `1.30` - максимальный объем памяти, который был выделен PHP-скрипту системой;
    + `2.00` - реальный объем памяти, выделенный PHP-скрипту системой;
- 'Class: App\Bitrix24\Bitrix24API' - полное имя класса из которого сделано сохранение в лог файл.

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80"></a>
## Автор

© 2019-2021 andrey-tech

<a id="%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F"></a>
## Лицензия

Данная библиотека распространяется на условиях лицензии [MIT](./LICENSE).
