<?php

/**
 * Трейт User. Методы для работы с пользователем в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.2.1
 *
 * v1.0.0 (25.10.2019) Начальная версия
 * v1.0.1 (11.11.2019) Добавлены параметры к методам getUsers()
 * v1.1.0 (15.11.2019) Добавлен метод getUserFields()
 * v1.2.0 (11.06.2020) Исправлен метод getUsers()
 * v1.2.1 (03.02.2021) Рефакторинг
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait User
{
    /**
     * Возвращает список названий полей пользователя
     *
     * @return array
     */
    public function getUserFields()
    {
        return $this->request('user.fields');
    }

    /**
     * Возвращает пользователя по ID
     *
     * @param  int|string $userId ID пользователя
     * @return array|null
     */
    public function getUser($userId)
    {
        $result = $this->request('user.get', [ 'ID' => $userId ]);
        $user = array_shift($result);

        return $user;
    }

    /**
     * Возвращает всех пользователей
     *
     * @param  array  $filter    Параметры
     *                           фильтрации
     * @param  string $order     Направление
     *                           сортировки
     * @param  string $sort      Поле, по которому
     *                           сортируются
     *                           результаты
     * @param  bool   $adminMode Ключ работы в
     *                           режиме
     *                           администратора
     * @return Generator
     */
    public function getUsers(
        array $filter = [],
        string $order = 'ASC',
        string $sort = '',
        bool $adminMode = false
    ): Generator {

        $params = [
            'FILTER'     => $filter,
            'order'      => $order,
            'sort'       => $sort,
            'ADMIN_MODE' => $adminMode
        ];

        return $this->getList('user.get', $params);
    }
}
