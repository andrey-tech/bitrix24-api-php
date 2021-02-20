<?php

/**
 * Трейт Lead. Методы для работы с лидом в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (20.02.2020) Начальная версия
 *
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Lead
{
    /**
     * Возвращает описание полей лида, в том числе пользовательских
     *
     * @return array
     * @see https://dev.1c-bitrix.ru/rest_help/crm/leads/crm_lead_fields.php
     */
    public function getLeadFields()
    {
        return $this->request('crm.lead.fields');
    }

    /**
     * Возвращает лид по ID
     * @param  int|string $leadId ID лида
     * @param  array      $with      Список связанных сущностей,
     *                               возвращаемых вместе с лидом [ self::$WITH_PRODUCTS ]
     * @return array
     */
    public function getLead($leadId, array $with = [])
    {
        $with = array_map('strtoupper', $with);

        if (empty($with)) {
            return $this->request(
                'crm.lead.get',
                [ 'id' => $leadId ]
            );
        }

        $commands = [
            'LEAD' => $this->buildCommand('crm.lead.get', [ 'id' => $leadId ])
        ];

        // Связанные товары
        if (in_array(self::$WITH_PRODUCTS, $with)) {
            $commands[self::$WITH_PRODUCTS] = $this->buildCommand(
                'crm.lead.productrows.get',
                [ 'id' => $leadId ]
            );
        }

        $result = $this->batchRequest($commands, true);

        return $this->createResultWith($result, 'LEAD', $with);
    }

    /**
     * Добавляет лид
     *
     * @param  array $fields Список полей лида
     * @param  array $params Параметры для лида
     * @return int
     */
    public function addLead(array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.lead.add',
            [
                'fields' => $fields,
                'params' => $params
            ]
        );
        return $result;
    }

    /**
     * Обновляет лид
     *
     * @param  int|string $leadId ID лида
     * @param  array      $fields Список
     *                            полей
     *                            лида
     * @param  array      $params Параметры
     *                            для лида
     * @return int
     */
    public function updateLead($leadId, array $fields = [], array $params = [])
    {
        $result = $this->request(
            'crm.lead.update',
            [
                'id'     => $leadId,
                'fields' => $fields,
                'params' => $params
            ]
        );
        return $result;
    }

    /**
     * Удаляет лид по ID
     *
     * @param  int|string $leadId ID лида
     * @return int
     */
    public function deleteLead($leadId)
    {
        $result = $this->request(
            'crm.lead.delete',
            [ 'id' => $leadId ]
        );
        return $result;
    }

    /**
     * Возвращает все лиды
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/leads/crm_lead_list.php
     */
    public function getLeadList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.lead.list', $params);
    }

    /**
     * Возвращает все лиды используя быстрый метод
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/leads/crm_lead_list.php
     */
    public function fetchLeadList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.lead.list', $params);
    }

    // ------------------------------------------------------------------------

    /**
     * Возвращает товарные позиции, связанные с лидом по ID лида
     *
     * @param  int|string $leadId ID лида
     * @return array
     */
    public function getLeadProductRows($leadId)
    {
        $result = $this->request(
            'crm.lead.productrows.get',
            [ 'id' => $leadId ]
        );
        return $result;
    }

    /**
     * Устанавливает товарные позиции, связанные с лидом по ID лида
     *
     * @param  int|string $leadId   ID лида
     * @param  array      $products Массив товаров
     * @return array
     */
    public function setLeadProductRows($leadId, array $products)
    {
        $result = $this->request(
            'crm.lead.productrows.set',
            [
                'id' => $leadId,
                'rows' => $products
            ]
        );
        return $result;
    }
}
