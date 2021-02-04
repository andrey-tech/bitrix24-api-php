<?php

/**
 * Трейт Disk. Методы для работы с диском в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (28.10.2019) Начальная версия
 * v1.0.1 (03.02.2021) Рефакторинг
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Disk
{

    /**
     * Возвращает список доступных хранилищ
     *
     * @param  array $filter Параметры
     *                       фильтрации
     * @return Generator
     */
    public function getDiskStorageList(array $filter = []): Generator
    {
        $params = [
            'filter'  => $filter
        ];

        return $this->getList('disk.storage.getlist', $params);
    }

    /**
     * Возвращает список файлов и папок, которые находятся непосредственно в корне хранилища
     *
     * @param  int|string $storageId Id хранилища
     * @param  array      $filter    Параметры
     *                               фильтрации
     * @return array
     */
    public function getDiskStorageChildren($storageId, array $filter = [])
    {
        $result = $this->request(
            'disk.storage.getchildren',
            [
                'id'     => $storageId,
                'filter' => $filter
            ]
        );

        return $result;
    }

    /**
     * Загружает новый файл в указанную папку на Диск
     *
     * @param  int|string $folderId         Id
     *                                      папки
     * @param  string     $fileContent      Raw
     *                                      данные
     *                                      файла
     * @param  array      $data             Массив параметров,
     *                                      описывающих файл
     *                                      (обязательное поле
     *                                      NAME - имя нового
     *                                      файла)
     * @param  bool       $isBase64FileData Raw данные файла
     *                                      закодированы base64?
     * @return array
     * @see    https://dev.1c-bitrix.ru/rest_help/disk/folder/disk_folder_uploadfile.php
     */
    public function uploadfileDiskFolder(
        $folderId,
        string $fileContent,
        array $data,
        bool $isBase64FileData = true
    ) {

        if (! $isBase64FileData) {
            $fileContent = base64_encode($fileContent);
        }

        $result = $this->request(
            'disk.folder.uploadfile',
            [
                'id'          => $folderId,
                'fileContent' => $fileContent,
                'data'        => $data
            ]
        );

        return $result;
    }
}
