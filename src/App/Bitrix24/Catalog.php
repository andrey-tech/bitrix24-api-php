<?php

/**
 * Трейт Catalog. Методы для работы с товарным каталогом в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.2.1
 *
 * v1.0.0 (15.10.2019) Начальная версия
 * v1.1.0 (11.06.2020) Добавлен метод fetchCatalogList()
 * v1.2.0 (14.06.2020) Добавлен метод getCatalogFields()
 * v1.2.1 (03.02.2021) Рефакторинг
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Catalog
{
    /**
     * Возвращает описание полей каталога товаров
     *
     * @return array
     */
    public function getCatalogFields()
    {
        return $this->request('crm.catalog.fields');
    }

    /**
     * Возвращает все товарные каталоги
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/catalog/crm_catalog_list.php
     */
    public function getCatalogList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.catalog.list', $params);
    }

    /**
     * Возвращает все товарные каталоги используя быстрый метод
     *
     * @see    https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/crm/catalog/crm_catalog_list.php
     */
    public function fetchCatalogList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.catalog.list', $params);
    }
}
