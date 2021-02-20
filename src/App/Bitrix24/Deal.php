<?php

/**
 * Трейт Deal. Методы для работы со сделкой в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.3.0
 *
 * v1.0.0 (14.10.2019) Начальная версия
 * v1.1.0 (15.11.2019) Добавлены методы getDealFields(), getDealProductRowFields()
 * v1.2.0 (09.06.2020) Изменен метод getDeal(), добавлен метод fetchDealList()
 * v1.2.1 (03.02.2021) Исправлено имя класса исключения в методах
 * v1.3.0 (20.02.2021) Метод getDealProductRowFields() перенесен в трейт ProductRow с именем getProductRowFields()
 *
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Deal
{
    /**
     * Возвращает описание полей сделки, в том числе пользовательских
     * @return array
     */
    public function getDealFields()
    {
        return $this->request('crm.deal.fields');
    }

    /**
     * Возвращает сделку по ID
     *
     * @param  int|string $dealId ID сделки
     * @param  array      $with   Список связанных сущностей,
     *                            возвращаемых вместе со сделкой [ self::$WITH_CONTACTS, self::$WITH_PRODUCTS ]
     * @return array
     */
    public function getDeal($dealId, array $with = [])
    {
        $with = array_map('strtoupper', $with);

        if (empty($with)) {
            return $this->request(
                'crm.deal.get',
                [ 'id' => $dealId ]
            );
        }

        $commands = [
            'DEAL' => $this->buildCommand('crm.deal.get', [ 'id' => $dealId ])
        ];

        // Связанные товары
        if (in_array(self::$WITH_PRODUCTS, $with)) {
            $commands[self::$WITH_PRODUCTS] = $this->buildCommand('crm.deal.productrows.get', [ 'id' => $dealId ]);
        }

        // Связанные контакты
        if (in_array(self::$WITH_CONTACTS, $with)) {
            $commands[self::$WITH_CONTACTS] = $this->buildCommand('crm.deal.contact.items.get', [ 'id' => $dealId ]);
        }

        $result = $this->batchRequest($commands, true);

        return $this->createResultWith($result, 'DEAL', $with);
    }

    /**
     * Добавляет сделку
     *
     * @param  array $fields Список полей сделки
     * @param  array $params Параметры для сделки
     * @return int
     */
    public function addDeal(array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.deal.add',
            [
                'fields' => $fields,
                'params' => $params
            ]
        );
        return $result;
    }

    /**
     * Обновляет сделку
     *
     * @param  int|string $dealId ID сделки
     * @param  array      $fields Список полей сделки
     * @param  array      $params Параметры для сделки
     * @return int
     */
    public function updateDeal($dealId, array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.deal.update',
            [
                'id'     => $dealId,
                'fields' => $fields,
                'params' => $params
            ]
        );
        return $result;
    }

    /**
     * Удаляет сделку по ID
     *
     * @param  int|string $dealId ID сделки
     * @return int
     */
    public function deleteDeal($dealId)
    {
        $result = $this->request(
            'crm.deal.delete',
            [ 'id' => $dealId ]
        );
        return $result;
    }

    /**
     * Возвращает все сделки
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/cdeals/crm_deal_list.php
     */
    public function getDealList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.deal.list', $params);
    }

    /**
     * Возвращает все сделки используя быстрый метод
     *
     * @see    https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/cdeals/crm_deal_list.php
     */
    public function fetchDealList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.deal.list', $params);
    }

    // ------------------------------------------------------------------------

    /**
     * Возвращает контакты, связанные со сделкой по ID сделки
     *
     * @param  int|string $dealId ID сделки
     * @return array
     */
    public function getDealContactItems($dealId)
    {
        $result = $this->request(
            'crm.deal.contact.items.get',
            [ 'id' => $dealId ]
        );

        return $result;
    }

    /**
     * Устанавливает контакты, связанные со сделкой по ID сделки
     *
     * @param  int|string $dealId   ID сделки
     * @param  array      $contacts Массив контактов
     * @return array
     */
    public function setDealContactItems($dealId, array $contacts)
    {
        $result = $this->request(
            'crm.deal.contact.items.set',
            [
                'id' => $dealId,
                'items' => $contacts
            ]
        );
        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Возвращает товарные позиции, связанные со сделкой по ID сделки
     *
     * @param  int|string $dealId ID сделки
     * @return array
     */
    public function getDealProductRows($dealId)
    {
        $result = $this->request(
            'crm.deal.productrows.get',
            [ 'id' => $dealId ]
        );

        return $result;
    }

    /**
     * Устанавливает товарные позиции, связанные со сделкой по ID сделки
     *
     * @param  int|string $dealId   ID сделки
     * @param  array      $products Массив товаров
     * @return array
     */
    public function setDealProductRows($dealId, array $products)
    {
        $result = $this->request(
            'crm.deal.productrows.set',
            [
                'id' => $dealId,
                'rows' => $products
            ]
        );

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет сделки c товарными позициями
     *
     * @param  array $deals  Массив сделок (поля связанных сущностей [ 'COMPANY_ID', 'CONTACT_ID', 'PRODUCTS' ])
     * @param  array $params Параметры сделок
     * @return array Массив id сделок
     */
    public function addDeals(array $deals = [], array $params = []): array
    {
        // Id созданных сделок
        $dealResults = [];

        while ($dealsChunk = array_splice($deals, 0, $this->batchSize)) {
            // Формируем массив команд на добавление сделок
            $commandParams = [];
            foreach ($dealsChunk as $index => $deal) {
                $commandParams[ $index ] = [
                    'fields' => $deal,
                    'params' => $params
                ];
            }
            $commands = $this->buildCommands('crm.deal.add', $commandParams);
            $dealResult = $this->batchRequest($commands);

            // Сравниваем число команд и число Id созданных сделок в ответе
            $sent = count($commandParams);
            $received = count($dealResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить сделки ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            // Формируем массив команд на добавление товаров к сделкам
            $commandParams = [];
            foreach ($dealsChunk as $index => $deal) {
                // Пропускаем сделки без поля PRODUCTS или без товаров
                if (
                    ! isset($deal['PRODUCTS'])
                    || ! is_array($deal['PRODUCTS'])
                    || count($deal['PRODUCTS']) == 0
                ) {
                    continue;
                }

                $commandParams[ $index ] = [
                    'id' => $dealResult[ $index ],
                    'rows' => $deal['PRODUCTS']
                ];
            }
            $commands = $this->buildCommands('crm.deal.productrows.set', $commandParams);
            $productResult = $this->batchRequest($commands);

            // Сравниваем число команд и число статусов в ответе
            $sent = count($commandParams);
            $received = count($productResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить товары к сделкам ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            // Сохраняем Id созданных сделок
            $dealResults = array_merge($dealResults, $dealResult);
        }

        return $dealResults;
    }

    /**
     * Пакетно обновляет сделки c товарными позициями
     *
     * @param array $deals  Массив сделок (поля связанных сущностей [
     *                      'COMPANY_ID', 'CONTACT_ID', 'PRODUCTS' ])
     *                      Если в сделке много
     *                      контактов, а в запросе на обновление сделки
     *                      передано поле CONTACT_ID, то после обновления в
     *                      сделке остается только 1 контакт или 0
     *                      контактов, если CONTACT_ID пуст.
     *
     * @param  array $params Параметры сделок
     * @return array Массив id сделок
     */
    public function updateDeals(array $deals = [], array $params = []): array
    {
        // Id обновленных сделок
        $dealResults = [];

        while ($dealsChunk = array_splice($deals, 0, $this->batchSize)) {
            // Формируем массив команд на обновление сделок
            $commandParams = [];
            foreach ($dealsChunk as $index => $deal) {
                // Проверка наличия поля ID в сделке на обновление
                $dealId = $deal['ID'] ?? null;
                if (empty($dealId)) {
                    $jsonDeal = $this->toJSON($deal);
                    throw new Bitrix24APIException(
                        "Поле 'ID' в сделке (index {$index}) на обновление отсутствует или пустое: '{$jsonDeal}'"
                    );
                }
                $dealResults[] = $dealId;

                $commandParams[ $index ] = [
                    'id'     => $dealId,
                    'fields' => $deal,
                    'params' => $params
                ];
            }
            $commands = $this->buildCommands('crm.deal.update', $commandParams);
            $dealResult = $this->batchRequest($commands);

            // Сравниваем число команд и число успешных статусов в ответе
            $sent = count($commandParams);
            $received = count($dealResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно обновить сделки ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            // Формируем массив команд на обновление товаров в сделках
            $commandParams = [];
            foreach ($dealsChunk as $index => $deal) {
                // Пропускаем сделки без поля PRODUCTS
                if (
                    ! isset($deal['PRODUCTS'])
                    || ! is_array($deal['PRODUCTS'])
                ) {
                    continue;
                }

                // Если сделка без товаров, удаляем товары в существующей сделке
                $products = count($deal['PRODUCTS']) ? $deal['PRODUCTS'] : [];

                $commandParams[ $index ] = [
                    'id'   => $deal['ID'],
                    'rows' => $products
                ];
            }
            $commands = $this->buildCommands('crm.deal.productrows.set', $commandParams);
            $productResult = $this->batchRequest($commands);

            // Сравниваем число команд и число статусов в ответе
            $sent = count($commandParams);
            $received = count($productResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно обновить товары в сделках ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $dealResults;
    }

    /**
     * Пакетно удаляет сделки
     *
     * @param  array $dealIds Массив id сделок
     * @return array Массив id сделок
     */
    public function deleteDeals(array $dealIds = []): array
    {
        // Id удаленных сделок
        $dealResults = [];

        while ($dealsChunk = array_splice($dealIds, 0, $this->batchSize)) {
            // Формируем массив команд на удаление сделок
            $commandParams = [];
            foreach ($dealsChunk as $index => $dealId) {
                $commandParams[ $index ] = [ 'id' => $dealId ];
                $dealResults[] = $dealId;
            }

            $commands = $this->buildCommands('crm.deal.delete', $commandParams);
            $dealResult = $this->batchRequest($commands);

            // Сравниваем число команд и число успешных статусов в ответе
            $sent = count($commandParams);
            $received = count($dealResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно удалить сделки ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $dealResults;
    }

    // ------------------------------------------------------------------------

    /**
     * Устанавливает файл в НЕ множественное пользовательское поле типа файл (файл нельзя удалить)
     *
     * @param  int|string $dealId           Id cделки
     * @param  int|string $userFieldId      Id НЕ множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX')
     * @param  string     $fileName         Имя файла
     * @param  string     $fileContent      Raw данные файла
     * @param  bool       $isBase64FileData Raw данные файла закодированы base64?
     * @return int
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/cases/edit/form_lead_with_files.php
     */
    public function setDealFile(
        $dealId,
        $userFieldId,
        string $fileName,
        string $fileContent,
        bool $isBase64FileData = true
    ) {

        if (! $isBase64FileData) {
            $fileContent = base64_encode($fileContent);
        }

        $fields = [
            $userFieldId => [ 'fileData' => [ $fileName, $fileContent ] ]
        ];

        $result = $this->updateDeal($dealId, $fields);

        return $result;
    }

    /**
     * Устанавливает файлы во множественное пользовательское поле типа файл (файлы можно удалить)
     *
     * @param  int|string $dealId           Id cделки
     * @param  int|string $userFieldId      Id множественного пользовательского поля в сделке ('UF_CRM_XXXXXXXXXX')
     * @param  array      $files            Массив параметров файлов ([ [ <Имя файла>, <Raw данные файла> ], ... ])
     *                                      (пустой массив для удаления всех файлов).
     * @param  bool       $isBase64FileData Raw данные файла закодированы base64?
     * @return int
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/cases/edit/form_lead_with_files.php
     */
    public function setDealFiles(
        $dealId,
        $userFieldId,
        array $files = [],
        bool $isBase64FileData = true
    ) {

        $userFieldValue = [];
        foreach ($files as $file) {
            if (! $isBase64FileData) {
                $file[1] = base64_encode($file[1]);
            }
            $userFieldValue[] = [
                'fileData' => $file
            ];
        }

        // Если удаление всех файлов
        if (! count($userFieldValue)) {
            $userFieldValue = '';
        }

        $fields = [
            $userFieldId => $userFieldValue
        ];

        $result = $this->updateDeal($dealId, $fields);

        return $result;
    }
}
