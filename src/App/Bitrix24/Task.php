<?php

/**
 * Трейт Task. Методы для работы с задачами в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2022 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.1.0
 *
 * v1.0.0 (02.12.2019) Начальная версия
 * v1.0.1 (03.02.2021) Исправлено имя класса исключения в методах
 * v1.1.0 (22.05.2022) Добавлен метод getTaskList()
 */

declare(strict_types=1);

namespace App\Bitrix24;

use Generator;

trait Task
{
    /**
     * Возвращает список названий полей задачи
     *
     * @return array
     */
    public function getTaskFields()
    {
        return $this->request('tasks.task.getFields');
    }

    /**
     * Возвращает задачу по ID
     *
     * @param  int|string $taskId ID задачи
     * @param  array      $select Параметры
     *                            выборки
     * @return array|null
     */
    public function getTask($taskId, array $select = [])
    {
        $task = $this->request(
            'tasks.task.get',
            [
            'taskId' => $taskId,
            'select' => $select
            ]
        );

        return $task;
    }

    /**
     * Возвращает все задачи
     *
     * @param  array $filter Параметры фильтрации
     * @param  array $order  Параметры
     *                       сортировки
     * @param  array $select Параметры выборки
     * @return Generator
     * @see    https://dev.1c-bitrix.ru/rest_help/tasks/task/tasks/tasks_task_list.php
     */
    public function getTaskList(array $filter = [], array $select = [], array $order = []): Generator
    {
        $params = [
            'order'  => $order,
            'filter' => $filter,
            'select' => $select
        ];

        return $this->getList('tasks.task.list', $params);
    }

    /**
     * Добавляет задачу
     *
     * @param  array $fields Список полей задачи
     * @return int
     */
    public function addTask(array $fields = [])
    {
        $result = $this->request(
            'tasks.task.add',
            [
                'fields' => $fields
            ]
        );

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет задачи
     *
     * @param  array $companies Массив параметров задач
     * @return array Массив id активностей
     */
    public function addTasks(array $tasks = []): array
    {
        // Id добавленных задач
        $taskResults = [];

        while ($tasksChunk = array_splice($tasks, 0, $this->batchSize)) {
            // Формируем массив команд на добавление задач
            $commandParams = [];
            foreach ($tasksChunk as $index => $task) {
                $commandParams[ $index ] = [
                    'fields' => $task
                ];
            }
            $commands = $this->buildCommands('tasks.task.add', $commandParams);
            $taskResult = $this->batchRequest($commands);

            // Сравниваем число команд и число id в ответе
            $sent = count($commandParams);
            $received = count($taskResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить задачи ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            $taskResults = array_merge($taskResults, $taskResult);
        }

        return $taskResults;
    }
}
