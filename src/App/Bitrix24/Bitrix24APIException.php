<?php

/**
 * Обработчик исключений в классе Bitrix24API
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (13.10.2019) Начальный релиз.
 *
 */

declare(strict_types = 1);

namespace App\Bitrix24;

class Bitrix24APIException extends \Exception
{
    /**
     * Добавляет идентификационную строку в сообщение об исключении
     * @param string $message Сообщение об исключении
     * @param int $code Код исключения
     * @param \Exception|null $previous Предыдущее исключение
     */
    public function __construct(string $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct("Bitrix24API: " . $message, $code, $previous);
    }
}
