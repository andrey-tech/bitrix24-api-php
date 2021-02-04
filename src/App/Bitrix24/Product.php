<?php

/**
 * Трейт Product. Методы для работы с товаром в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.2.1
 *
 * v1.0.0 (14.10.2019) Начальная версия
 * v1.0.1 (30.10.2019) Добавлено значение по умолчанию в getProductList() для $select = [ '*', 'PROPERTY_*' ]
 * v1.1.0 (15.11.2019) Добавлен метод getProductFields()
 * v1.2.0 (09.06.2020) Добавлен метод fetchProductList()
 * v1.2.1 (03.02.2021) Исправлено имя класса исключения в методах
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Product
{
    /**
     * Возвращает описание полей товара, в том числе пользовательских
     *
     * @return array
     */
    public function getProductFields()
    {
        return $this->request('crm.product.fields');
    }

    /**
     * Возвращает товар по ID
     *
     * @param  int|string $productId ID товара
     * @return array
     */
    public function getProduct($productId)
    {
        $product = $this->request(
            'crm.product.get',
            [ 'id' => $productId ]
        );

        return $product;
    }

    /**
     * Добавляет товар
     *
     * @param  array $fields Список полей товара
     * @return int
     */
    public function addProduct(array $fields = [])
    {
        $result = $this->request(
            'crm.product.add',
            [
                'fields' => $fields
            ]
        );

        return $result;
    }

    /**
     * Обновляет товар
     *
     * @param  int|string $productId ID товара
     * @param  array      $fields    Список
     *                               полей
     *                               товара
     * @return int
     */
    public function updateProduct($productId, array $fields = [])
    {
        $result = $this->request(
            'crm.product.update',
            [
                'id'     => $productId,
                'fields' => $fields
            ]
        );

        return $result;
    }

    /**
     * Удаляет товар по ID
     *
     * @param  string|int $productId ID товара
     * @return array
     */
    public function deleteProduct($productId)
    {
        $result = $this->request(
            'crm.product.delete',
            [ 'id' => $productId ]
        );

        return $result;
    }

    /**
     * Возвращает все товары
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     */
    public function getProductList(
        array $filter = [],
        array $select = [ '*', 'PROPERTY_*' ],
        array $order = []
    ): Generator {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.product.list', $params);
    }

    /**
     * Возвращает все товары используя быстрый метод
     *
     * @see    https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     */
    public function fetchProductList(
        array $filter = [],
        array $select = [ '*', 'PROPERTY_*' ],
        array $order = []
    ): Generator {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.product.list', $params);
    }


    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет товары
     *
     * @param  array $products Массив товаров
     *                         Секция товаров 'SECTION_ID'
     * @return array Массив Id товаров
     */
    public function addProducts(array $products = []): array
    {
        // Id добавленных товаров
        $productResults = [];

        while ($productsChunk = array_splice($products, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($productsChunk as $index => $product) {
                $commandParams[] = [ 'fields' => $product ];
            }
            $commands = $this->buildCommands('crm.product.add', $commandParams);
            $result = $this->batchRequest($commands);

            $sent = count($commandParams);
            $received = count($result);

            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить товары ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            $productResults = array_merge($productResults, $result);
        }

        return $productResults;
    }

    /**
     * Пакетно обновляет товары
     *
     * @param  array $products Массив товаров
     * @return array Массив Id товаров
     */
    public function updateProducts(array $products = []): array
    {
        // Id обновленных товаров
        $productResults = [];

        while ($productsChunk = array_splice($products, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($productsChunk as $index => $product) {
                // Проверка наличия поля ID в товаре на добавление
                $productId = $product['ID'] ?? null;
                if (empty($productId)) {
                    $jsonProduct = $this->toJSON($product);
                    throw new Bitrix24APIException(
                        "Поле 'ID' в товаре (index {$index}) на обновление отсутствует или пустое: '{$jsonProduct}'"
                    );
                }
                $productResults[] = $productId;

                $commandParams[] = [
                    'id'     => $productId,
                    'fields' => $product
                ];
            }
            $commands = $this->buildCommands('crm.product.update', $commandParams);
            $result = $this->batchRequest($commands);

            $sent = count($commandParams);
            $received = count($result);

            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно обновить товары ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $productResults;
    }

    /**
     * Пакетно удаляет товары
     *
     * @param  array $productIds Массив Id товаров
     * @return array Массив Id товаров
     */
    public function deleteProducts(array $productIds = []): array
    {
        // Id удаленных товаров
        $productResults = [];

        while ($productsChunk = array_splice($productIds, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($productsChunk as $index => $productId) {
                $commandParams[] = [ 'id' => $productId ];
                $productResults[] = $productId;
            }
            $commands = $this->buildCommands('crm.product.delete', $commandParams);
            $result = $this->batchRequest($commands);

            $sent = count($commandParams);
            $received = count($result);

            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно удалить товары ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $productResults;
    }
}
