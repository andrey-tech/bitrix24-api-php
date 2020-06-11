<?php

/**
 * Трейт Catalog. Методы для работы с товарным каталогом в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.1.0
 *
 * v1.0.0 (15.10.2019) Начальная версия
 * v1.1.0 (11.06.2020) Добавлен метод fetchCatalogList()
 *
 */
declare(strict_types = 1);

namespace App\Bitrix24;

trait Catalog
{
    /**
     * Возвращает все товарные каталоги
     * @param array $filter Параметры фильтрации
     * @param array $order Параметры сортировки
     * @param array $select Параметры выборки
     * @return object \Generator
     * @see https://dev.1c-bitrix.ru/rest_help/crm/catalog/crm_catalog_list.php
     */
    public function getCatalogList(array $filter = [], array $select = [], array $order = []) :\Generator
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
     * @see https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param array $filter Параметры фильтрации
     * @param array $order Параметры сортировки
     * @param array $select Параметры выборки
     * @return object \Generator
     * @see https://dev.1c-bitrix.ru/rest_help/crm/catalog/crm_catalog_list.php
     */
    public function fetchCatalogList(array $filter = [], array $select = [], array $order = []) :\Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.catalog.list', $params);
    }
}
