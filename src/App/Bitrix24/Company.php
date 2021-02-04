<?php

/**
 * Трейт Company. Методы для работы с компанией в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.2.1
 *
 * v1.0.0 (13.10.2019) Начальная версия
 * v1.1.0 (15.11.2019) Добавлен метод getCompanyFields()
 * v1.2.0 (09.06.2020) Изменен метод getCompany(), добавлен метод fetchCompanyList()
 * v1.2.1 (03.02.2021) Исправлено имя класса исключения в методах
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Company
{
    /**
     * Возвращает описание полей компании, в том числе пользовательских
     *
     * @return array
     */
    public function getCompanyFields()
    {
        return $this->request('crm.company.fields');
    }

    /**
     * Возвращает компанию по ID
     * @param  int|string $companyId ID компании
     * @param  array      $with      Список связанных сущностей,
     *                               возвращаемых вместе с компанией [ self::$WITH_CONTACTS ]
     * @return array
     */
    public function getCompany($companyId, array $with = [])
    {
        $with = array_map('strtoupper', $with);

        if (empty($with)) {
            return $this->request(
                'crm.company.get',
                [ 'id' => $companyId ]
            );
        }

        $commands = [
            'COMPANY' => $this->buildCommand('crm.company.get', [ 'id' => $companyId ])
        ];

        // Связанные контакты
        if (in_array(self::$WITH_CONTACTS, $with)) {
            $commands[self::$WITH_CONTACTS] = $this->buildCommand(
                'crm.company.contact.items.get',
                [ 'id' => $companyId ]
            );
        }

        $result = $this->batchRequest($commands, true);

        return $this->createResultWith($result, 'COMPANY', $with);
    }

    /**
     * Добавляет компанию
     *
     * @param  array $fields Список полей компании
     * @param  array $params Параметры для компании
     * @return int
     */
    public function addCompany(array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.company.add',
            [
                'fields' => $fields,
                'params' => $params
            ]
        );

        return $result;
    }

    /**
     * Обновляет компанию
     *
     * @param  int|string $companyId ID компании
     * @param  array      $fields    Список
     *                               полей
     *                               компании
     * @param  array      $params    Параметры
     *                               для
     *                               компании
     * @return int
     */
    public function updateCompany($companyId, array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.company.update',
            [
                'id'     => $companyId,
                'fields' => $fields,
                'params' => $params
            ]
        );

        return $result;
    }

    /**
     * Удаляет компанию по ID
     *
     * @param  int|string $companyId ID компании
     * @return int
     */
    public function deleteCompany($companyId)
    {
        $result = $this->request(
            'crm.company.delete',
            [ 'id' => $companyId ]
        );

        return $result;
    }

    /**
     * Возвращает все компании
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/company/crm_company_list.php
     */
    public function getCompanyList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.company.list', $params);
    }

    /**
     * Возвращает все компании используя быстрый метод
     *
     * @see    https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/company/crm_company_list.php
     */
    public function fetchCompanyList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.company.list', $params);
    }

    // ------------------------------------------------------------------------

    /**
     * Возвращает контакты, связанные с компанией по ID компании
     *
     * @param  int|string $companyId ID компании
     * @return array
     */
    public function getCompanyContactItems($companyId)
    {
        $result = $this->request(
            'crm.company.contact.items.get',
            [ 'id' => $companyId ]
        );

        return $result;
    }

    /**
     * Устанавливает контакты, связанные с компанией по ID компании
     *
     * @param  int|string $companyId ID компании
     * @param  array      $contacts  Массив
     *                               контактов
     * @return array
     */
    public function setCompanyContactItems($companyId, array $contacts)
    {
        $result = $this->request(
            'crm.company.contact.items.set',
            [
                'id' => $companyId,
                'items' => $contacts
            ]
        );

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет компании
     *
     * @param  array $companies Массив компаний
     * @param  array $params    Параметры
     *                          для
     *                          компаний
     * @return array Массив id компаний
     */
    public function addCompanies(array $companies = [], array $params = []): array
    {
        // Id добавленных компаний
        $companyResults = [];

        while ($companiesChunk = array_splice($companies, 0, $this->batchSize)) {
            // Формируем массив команд на добавление компаний
            $commandParams = [];
            foreach ($companiesChunk as $index => $company) {
                $commandParams[ $index ] = [
                    'fields' => $company,
                    'params' => $params
                ];
            }
            $commands = $this->buildCommands('crm.company.add', $commandParams);
            $companyResult = $this->batchRequest($commands);

            // Сравниваем число команд и число id в ответе
            $sent = count($commandParams);
            $received = count($companyResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить компании ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            $companyResults = array_merge($companyResults, $companyResult);
        }

        return $companyResults;
    }

    /**
     * Пакетно обновляет компании
     *
     * @param  array $companies Массив компаний
     * @param  array $params    Параметры
     *                          для
     *                          компаний
     * @return array Массив id компаний
     */
    public function updateCompanies(array $companies = [], array $params = []): array
    {
        // Id обновленных компаний
        $companyResults = [];

        while ($companiesChunk = array_splice($companies, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($companiesChunk as $index => $company) {
                // Проверка наличия поля ID в компании на обновление
                $companyId = $company['ID'] ?? null;
                if (empty($companyId)) {
                    $jsonCompany = $this->toJSON($company);
                    throw new Bitrix24APIException(
                        "Поле 'ID' в компании (index {$index}) на обновление отсутствует или пустое: '{$jsonCompany}'"
                    );
                }
                $companyResults[] = $companyId;

                $commandParams[ $index ] = [
                    'id'     => $companyId,
                    'fields' => $company,
                    'params' => $params
                ];
            }
            $commands = $this->buildCommands('crm.company.update', $commandParams);
            $result = $this->batchRequest($commands);

            // Сравниваем число команд и число статусов в ответе
            $sent = count($commandParams);
            $received = count($result);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно обновить компании ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $companyResults;
    }

    /**
     * Пакетно удаляет компании
     *
     * @param  array $companyIds Массив id компаний
     * @return array Массив id компаний
     */
    public function deleteCompanies(array $companyIds = []): array
    {
        // Id удаленных компаний
        $companyResults = [];

        while ($companiesChunk = array_splice($companyIds, 0, $this->batchSize)) {
            // Формируем массив команд на удаление компаний
            $commandParams = [];
            foreach ($companiesChunk as $index => $companyId) {
                $commandParams[ $index ] = [ 'id' => $companyId ];
                $companyResults[] = $companyId;
            }

            $commands = $this->buildCommands('crm.company.delete', $commandParams);
            $companyResult = $this->batchRequest($commands);

            // Сравниваем число команд и число статусов в ответе
            $sent = count($commandParams);
            $received = count($companyResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно удалить компании ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $companyResults;
    }
}
