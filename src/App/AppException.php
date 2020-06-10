<?php

/**
 * Обработчик исключений к классах пространства имен \App
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (28.05.2019) Начальный релиз
 * v1.0.1 (26.06.2019) Изменения для App
 *
 */

declare(strict_types = 1);

namespace App;

class AppException extends \Exception
{
    /**
     * Добавляет идентификационную строку App: в сообщение об исключении
     * @param string $message Сообщение об исключении
     * @param int $code Код исключения
     * @param \Exception|null $previous Предыдущее исключение
     */
    public function __construct(string $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct("App: " . $message, $code, $previous);
    }
}
