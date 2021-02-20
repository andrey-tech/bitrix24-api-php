<?php

/**
 * Трейт ProductRow. Методы для работы с товарными позициями в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (20.02.2021) Начальная версия
 *
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait ProductRow
{
    /**
     * Возвращает описание полей товарных позиций
     *
     * @return array
     */
    public function getProductRowFields()
    {
        return $this->request('crm.productrow.fields');
    }
}
