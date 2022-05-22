<?php

/**
 * Трейт Contact. Методы для работы с контактом в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2022 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.3.0
 *
 * v1.0.0 (14.10.2019) Начальная версия
 * v1.1.0 (15.11.2019) Добавлен метод getContactFields()
 * v1.2.0 (09.06.2020) Изменен метод getContact(), добавлен метод fetchContactList()
 * v1.2.1 (11.06.2020) Исправлен метод deleteContacts()
 * v1.2.2 (03.02.2021) Исправлено имя класса исключения в методах
 * v1.3.0 (22.05.2022) Добавлен метод getContactsByPhone()
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Contact
{
    /**
     * Возвращает описание полей контакта, в том числе пользовательских
     * @return array
     */
    public function getContactFields()
    {
        return $this->request('crm.contact.fields');
    }

    /**
     * Возвращает контакт по ID
     *
     * @param  int|string $contactId ID контакта
     * @param  array      $with      Список связанных сущностей,
     *                               возвращаемых вместе с контактом [ self::$WITH_COMPANIES ]
     * @return array
     */
    public function getContact($contactId, array $with = [])
    {
        $with = array_map('strtoupper', $with);

        if (empty($with)) {
            return $this->request(
                'crm.contact.get',
                [ 'id' => $contactId ]
            );
        }

        $commands = [
            'CONTACT' => $this->buildCommand('crm.contact.get', [ 'id' => $contactId ])
        ];

        // Связанные компании
        if (in_array(self::$WITH_COMPANIES, $with)) {
            $commands[self::$WITH_COMPANIES] = $this->buildCommand(
                'crm.contact.company.items.get',
                [ 'id' => $contactId ]
            );
        }

        $result = $this->batchRequest($commands, true);

        return $this->createResultWith($result, 'CONTACT', $with);
    }

    /**
     * Возвращает контакты по номеру телефона
     *
     * @param  int|string $phone Номер телефона
     * @param  array $select Параметры выборки
     * @return array
     */
    public function getContactsByPhone($phone, $select = [])
    {
        return $this->request(
            'crm.contact.list',
            [
                'filter' => [ 'PHONE' =>  $phone ],
                'select' => $select
            ]
        );
    }

    /**
     * Добавляет контакт
     *
     * @param  array $fields Список полей контакта
     * @param  array $params Параметры для контакта
     * @return int
     */
    public function addContact(array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.contact.add',
            [
                'fields' => $fields,
                'params' => $params
            ]
        );

        return $result;
    }

    /**
     * Обновляет контакт
     *
     * @param  string|int $contactId ID контакта
     * @param  array      $fields    Список
     *                               полей
     *                               контакта
     * @param  array      $params    Список
     *                               параметров
     *                               контакта
     * @return int
     */
    public function updateContact($contactId, array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.contact.update',
            [
                'id'     => $contactId,
                'fields' => $fields,
                'params' => $params
            ]
        );

        return $result;
    }

    /**
     * Удаляет контакт по ID
     *
     * @param  int|string $contactId ID контакта
     * @return int
     */
    public function deleteContact($contactId)
    {
        $result = $this->request(
            'crm.contact.delete',
            [ 'id' => $contactId ]
        );

        return $result;
    }

    /**
     * Возвращает все контакты
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     */
    public function getContactList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.contact.list', $params);
    }

    /**
     * Возвращает все контакты используя быстрый метод
     *
     * @see    https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     */
    public function fetchContactList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.contact.list', $params);
    }

    // ------------------------------------------------------------------------

    /**
     * Возвращает компании, связанные с контактом по ID контакта
     *
     * @param  int|string $contactId ID контакта
     * @return array
     */
    public function getContactCompanyItems($contactId)
    {
        $result = $this->request(
            'crm.contact.company.items.get',
            [ 'id' => $contactId ]
        );

        return $result;
    }

    /**
     * Устанавливает компании, связанные с контактом по ID контакта
     *
     * @param  int|string $contactId ID контакта
     * @param  array      $companies Массив
     *                               компаний
     * @return array
     */
    public function setContactCompanyItems($contactId, array $companies)
    {
        $result = $this->request(
            'crm.contact.company.items.set',
            [
                'id' => $contactId,
                'items' => $companies
            ]
        );

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет контакты
     *
     * @param  array $contacts Массив контактов (поля связанных сущностей [ 'COMPANY_ID' ])
     * @param  array $params   Параметры
     *                         для
     *                         контактов
     * @return array Массив Id контактов
     */
    public function addContacts(array $contacts = [], array $params = []): array
    {
        // Id добавленных контактов
        $contactResults = [];

        while ($contactsChunk = array_splice($contacts, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($contactsChunk as $index => $contact) {
                $commandParams[ $index ] = [
                    'fields' => $contact,
                    'params' => $params
                ];
            }
            $commands = $this->buildCommands('crm.contact.add', $commandParams);
            $result = $this->batchRequest($commands);

            // Сравниваем число команд и число id в ответе
            $sent = count($commandParams);
            $received = count($result);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить контакты ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            $contactResults = array_merge($contactResults, $result);
        }

        return $contactResults;
    }

    /**
     * Пакетно обновляет контакты
     *
     * @param  array $contacts Массив контактов (поля связанных сущностей [ 'COMPANY_ID' ])
     * @param  array $params   Параметры
     *                         для
     *                         контактов
     * @return array Массив Id контактов
     */
    public function updateContacts(array $contacts = [], array $params = []): array
    {
        // Id обновленных контактов
        $contactResults = [];

        while ($contactsChunk = array_splice($contacts, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($contactsChunk as $index => $contact) {
                // Проверка наличия поля ID в контакте на обновление
                $contactId = $contact['ID'] ?? null;
                if (empty($contactId)) {
                    $jsonContact = $this->toJSON($contact);
                    throw new Bitrix24APIException(
                        "Поле 'ID' в контакте (index {$index}) на обновление отсутствует или пустое: '{$jsonContact}'"
                    );
                }
                $contactResults[] = $contactId;

                $commandParams[ $index ] = [
                    'id'     => $contactId,
                    'fields' => $contact,
                    'params' => $params
                ];
            }
            $commands = $this->buildCommands('crm.contact.update', $commandParams);
            $result = $this->batchRequest($commands);

            // Сравниваем число команд и число статусов в ответе
            $sent = count($commandParams);
            $received = count($result);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно обновить контакты ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $contactResults;
    }

    /**
     * Пакетно удаляет контакты
     *
     * @param  array $contactIds Массив Id контактов
     * @return array Массив Id контактов
     */
    public function deleteContacts(array $contactIds = []): array
    {
        // Id удаленных контактов
        $contactResults = [];

        while ($contactsChunk = array_splice($contactIds, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($contactsChunk as $index => $contactId) {
                $commandParams[ $index ] = [ 'id' => $contactId ];
                $contactResults[] = $contactId;
            }
            $commands = $this->buildCommands('crm.contact.delete', $commandParams);
            $result = $this->batchRequest($commands);

            // Сравниваем число команд и число статусов в ответе
            $sent = count($commandParams);
            $received = count($result);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно удалить контакты ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $contactResults;
    }
}
