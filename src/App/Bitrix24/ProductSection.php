<?php

/**
 * Трейт ProductSection. Методы для работы с разделом товаров в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.2.1
 *
 * v1.0.0 (14.10.2019) Начальная версия
 * v1.1.0 (15.11.2019) Добавлен метод getProductSectionFields()
 * v1.2.0 (09.06.2020) Добавлен метод fetchProductSectionList()
 * v1.2.1 (03.02.2021) Исправлено имя класса исключения в методах
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait ProductSection
{
    /**
     * Возвращает описание полей раздела товаров, в том числе пользовательских
     *
     * @return array
     */
    public function getProductSectionFields()
    {
        return $this->request('crm.productsection.fields');
    }

    /**
     * Возвращает раздел товаров по ID
     *
     * @param  int|string $productSectionId ID раздела товаров
     * @return array
     */
    public function getProductSection($productSectionId)
    {
        $productSection = $this->request(
            'crm.productsection.get',
            [ 'id' => $productSectionId ]
        );

        return $productSection;
    }

    /**
     * Добавляет раздел товаров
     *
     * @param  array $fields Список полей раздела товаров
     * @return int
     */
    public function addProductSection(array $fields = [])
    {
        $result = $this->request(
            'crm.productsection.add',
            [
                'fields' => $fields
            ]
        );

        return $result;
    }

    /**
     * Обновляет раздел товаров
     *
     * @param  int|string $productSectionId ID раздела товаров
     * @param  array      $fields           Список
     *                                      полей
     *                                      раздела
     *                                      товаров
     * @return int
     */
    public function updateProductSection($productSectionId, array $fields = [])
    {
        $result = $this->request(
            'crm.productsection.update',
            [
                'id'     => $productSectionId,
                'fields' => $fields
            ]
        );

        return $result;
    }

    /**
     * Удаляет раздел товаров по ID
     *
     * @param  int|string $productSectionId ID раздела товаров
     * @return int
     */
    public function deleteProductSection($productSectionId)
    {
        $result = $this->request(
            'crm.product.delete',
            [ 'id' => $productSectionId ]
        );

        return $result;
    }

    /**
     * Возвращает все разделы товары
     *
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $filter Параметры фильтрации
     * @param  array $select Параметры выборки
     * @return Generator
     */
    public function getProductSectionList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('crm.productsection.list', $params);
    }

    /**
     * Возвращает все разделы товары используя быстрый метод
     *
     * @see    https://dev.1c-bitrix.ru/rest_help/rest_sum/start.php
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $filter Параметры фильтрации
     * @param  array $select Параметры выборки
     * @return Generator
     */
    public function fetchProductSectionList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->fetchList('crm.productsection.list', $params);
    }

    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет разделы товары
     *
     * @param  array $productSections Массив разделов товаров
     * @return array Массив Id разделов товаров
     */
    public function addProductSections(array $productSections = []): array
    {
        // Id добавленных разделов товаров
        $productSectionResults = [];

        while ($productSectionsChunk = array_splice($productSections, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($productSectionsChunk as $index => $productSection) {
                $commandParams[] = [ 'fields' => $productSection ];
            }
            $commands = $this->buildCommands('crm.productsection.add', $commandParams);
            $result = $this->batchRequest($commands);

            $sent = count($commandParams);
            $received = count($result);

            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить разделы товаров ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            $productSectionResults = array_merge($productSectionResults, $result);
        }

        return $productSectionResults;
    }

    /**
     * Пакетно обновляет разделы товаров
     *
     * @param  array $productSections Массив разделов товаров
     * @return array Массив Id разделов товаров
     */
    public function updateProductSections(array $productSections = []): array
    {
        // Id обновленных разделов товаров
        $productSectionResults = [];

        while ($productSectionsChunk = array_splice($productSections, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($productSectionsChunk as $index => $productSection) {
                // Проверка наличия поля ID в товаре на добавление
                $productSectionId = $productSection['ID'] ?? null;
                if (empty($productSectionId)) {
                    $jsonProductSection = $this->toJSON($productSection);
                    throw new Bitrix24APIException(
                        "Поле 'ID' в разделе товаров (index {$index}) на обновление " .
                        "отсутствует или пустое: '{$jsonProductSection}'"
                    );
                }
                $productSectionResults[] = $productSectionId;

                $commandParams[] = [
                    'id'     => $productSectionId,
                    'fields' => $productSection
                ];
            }
            $commands = $this->buildCommands('crm.productsection.update', $commandParams);
            $result = $this->batchRequest($commands);

            $sent = count($commandParams);
            $received = count($result);

            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно обновить раздел товаров ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $productSectionResults;
    }

    /**
     * Пакетно удаляет разделы товаров
     *
     * @param  array $productSectionIds Массив Id разделов товаров
     * @return array Массив Id разделов товаров
     */
    public function deleteProductSections(array $productSectionIds = []): array
    {
        // Id удаленных разделов товаров
        $productSectionResults = [];

        while ($productSectionsChunk = array_splice($productSectionIds, 0, $this->batchSize)) {
            $commandParams = [];
            foreach ($productSectionsChunk as $index => $productSectionId) {
                $commandParams[] = [ 'id' => $productSectionId ];
                $productSectionResults[] = $productSectionId;
            }
            $commands = $this->buildCommands('crm.productsection.delete', $commandParams);
            $result = $this->batchRequest($commands);

            $sent = count($commandParams);
            $received = count($result);

            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно удалить разделы товаров ({$sent}/{$received}): {$jsonResponse}"
                );
            }
        }

        return $productSectionResults;
    }
}
