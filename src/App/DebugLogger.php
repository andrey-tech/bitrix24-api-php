<?php

/**
 * Класс DebugLogger. Сохраняет отладочную информацию в лог файл.
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/
 * @license   MIT
 *
 * @version 1.8.0
 *
 * v1.0.0 (23.08.2019) Начальный релиз
 * v1.1.0 (30.08.2019) Добавлен флаг isActive
 * v1.2.0 (30.08.2019) Добавлен параметр $object
 * v1.3.0 (11.09.2019) Добавлен параметр $fileName
 * v1.4.0 (04.10.2019) Добавлен параметр $header
 * v1.4.1 (14.10.2019) Замена метода: getAbsoluteFilePath() на getAbsoluteFileName()
 * v1.4.2 (18.10.2019) В лог добавлены микросекунды и временная зона
 * v1.5.0 (22.10.2019) Добавлен метод getMemoryPeakUsage()
 * v1.5.1 (30.10.2019) Исправлен баг с микросекундами
 * v1.5.2 (16.11.2019) В лог добавлена величина разницы во времени
 * v1.5.3 (12.12.2019) Временная зона отделена от времени
 * v1.5.4 (15.03.2020) Замена '0' на '-' в deltaTime
 * v1.6.0 (13.05.2020) Изменен момент создания каталога для лог файлов
 * v1.7.0 (14.05.2020) Добавлен уникальный ID
 * v1.8.0 (10.06.2020) Удален параметр $header из метода save(). Рефракторинг
 *
 */

declare(strict_types = 1);

namespace App;

class DebugLogger
{
    /**
     * Флаг активности логгера
     * @var bool
     */
    public $isActive = false;

    /**
     * Каталог для хранения лог файлов
     * @var string
     */
    public $logFileDir = 'protected/temp/';

    /**
     * Уникальный ID для лог файла
     * @var string
     */
    protected $uniqId;

    /**
     * Время последнего сохранения в микросекундах
     * @var float
     */
    protected $microtime;

    /**
     * Имя лог файла
     * @var string
     */
    protected $logFileName;

    /**
     * Полный путь к лог файлу
     * @var string
     */
    protected $logFilePath;

    /**
     * Массив единственных объектов класса для каждого имени лог файла
     * @var array
     */
    private static $instances = [];

    /**
     * Конструктор
     * @param string $logFileName Имя лог файла
     */
    private function __construct(string $logFileName)
    {
        $this->logFileName = $logFileName;
        $this->uniqId = $this->getUniqId();
    }

    /**
     * Возвращает единственный объект класса \App\DebugLogger
     * @param string $logFileName Имя лог файла
     * @return \App\RequestLogger
     */
    public static function instance(string $logFileName = 'debug.log') :\App\DebugLogger
    {
        if (! isset(self::$instances[ $logFileName ])) {
            self::$instances[ $logFileName ] = new self($logFileName);
        }
        return self::$instances[ $logFileName ];
    }

    /**
     * Сохраняет отладочную информацию в файл
     * @param mixed $info Отладочная информация (строка, массив, объект)
     * @param object $object Объект класса в котором вызывается метод
     * @return void
     */
    public function save($info, $object = null)
    {
        // Если не активен (выключен)
        if (! $this->isActive) {
            return;
        }

        // Устанавливаем полный путь к лог файлу
        if (! isset($this->logFilePath)) {
            $this->logFilePath = $this->logFileDir . $this->logFileName;
            $this->logFilePath = $this->getAbsoluteFileName($this->logFilePath);
            if (empty($this->logFilePath)) {
                throw new AppException("Не удалость определить путь к лог файлу {$this->logFileName}");
            }
        }

        // Вычисляем время, прошедшее с последнего сохранения
        $microtime = microtime(true);
        $deltaMicrotime = isset($this->microtime) ? sprintf('%.6f', $microtime - $this->microtime) : '-';
        $this->microtime = $microtime;

        // Форматирует время запроса
        $dateTime = \DateTime::createFromFormat('U.u', sprintf('%.f', $microtime));
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $dateTime->setTimeZone($timeZone);
        $requestTime = $dateTime->format('Y-m-d H:i:s,u P') . " Δ{$deltaMicrotime} s";

        $memoryUsage = $this->getMemoryPeakUsage();

        // Заголовок сообщения для лог файла
        $message = "*** {$this->uniqId} [{$requestTime}, {$memoryUsage}] " . str_repeat('*', 20) . PHP_EOL;

        // Формируем заголовок
        if (isset($object) && is_object($object)) {
            $className = get_class($object);
            $message .= "* Class: {$className}\n";
        }

        if (! is_string($info)) {
            $jsonInfo = json_encode($info, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_PARTIAL_OUTPUT_ON_ERROR);
            if ($jsonInfo === false) {
                $errorMessage = json_last_error_msg();
                throw new AppException("Ошибка кодирования JSON ({$errorMessage}): " . print_r($info, true));
            }
            $info = $jsonInfo;
        }

        $message .= $info . PHP_EOL . PHP_EOL;

        // Записывет сообщение в лог файл
        if (! @file_put_contents($this->logFilePath, $message, FILE_APPEND|LOCK_EX)) {
            throw new AppException("Не удалость записать в лог файл {$this->logFilePath}");
        }
    }

    /**
     * Возвращает абсолютное имя файла и создает каталоги при необходимости
     * @param string $relativeFileName Относительное имя файла
     * @param bool $createDir Создавать каталоги при необходимости?
     * @return string|null Абсолютное имя файла
     * @see http://php.net/manual/ru/function.stream-resolve-include-path.php#115229
     */
    private function getAbsoluteFileName(string $relativeFileName, bool $createDir = true)
    {
        $includePath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePath as $path) {
            $absoluteFileName = $path . DIRECTORY_SEPARATOR . $relativeFileName;
            $checkDir = dirname($absoluteFileName);
            if (is_dir($checkDir)) {
                return $absoluteFileName;
            }
            if ($createDir) {
                if (! mkdir($checkDir, $mode = 0755, $recursive = true)) {
                    throw new AppException("Не удалость создать каталог {$checkDir}");
                }
                return $absoluteFileName;
            }
        }
        return null;
    }

    /**
     * Возвращает строку с информацией о пиковом использовании памяти
     * @return string
     */
    protected function getMemoryPeakUsage() :string
    {
        return sprintf('%0.2f', memory_get_peak_usage(false)/1024/1024) . '/' .
            sprintf('%0.2f', memory_get_peak_usage(true)/1024/1024) . ' MiB';
    }

    /**
     * Возвращает уникальный ID для лог файла
     * @param  int $length Длина ID, символов
     * @return string
     */
    protected function getUniqId(int $length = 7) :string
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
    }
}
