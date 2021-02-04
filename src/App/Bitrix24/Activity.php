<?php

/**
 * Трейт Activity. Методы для работы с делами (активностями) в системе Bitrix24.
 *
 * @author    andrey-tech
 * @copyright 2019-2021 andrey-tech
 * @see       https://github.com/andrey-tech/bitrix24-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (02.12.2019) Начальная версия
 * v1.0.1 (03.03.2021) Исправлено имя класса исключения в методах
 */

declare(strict_types=1);

namespace App\Bitrix24;

trait Activity
{
    /**
     * Возвращает список названий полей активности
     *
     * @return array
     */
    public function getActivityFields()
    {
        return $this->request('crm.activity.fields');
    }

    /**
     * Возвращает активность по ID
     *
     * @param  int|string $activityId ID активности
     * @return array|null
     */
    public function getActivity($activityId)
    {
        $activity = $this->request(
            'crm.activity.get',
            [
                'id' => $activityId
            ]
        );

        return $activity;
    }

    /**
     * Добавляет активность
     *
     * @param  array $fields Список полей активности
     * @return int
     */
    public function addActivity(array $fields = [])
    {
        $result = $this->request(
            'crm.activity.add',
            [
                'fields' => $fields
            ]
        );

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Пакетно добавляет активности
     *
     * @param  array $activities Массив параметров активностей
     * @return array Массив id активностей
     */
    public function addActivities(array $activities = []): array
    {
        // Id добавленных активностей
        $activityResults = [];

        while ($activitiesChunk = array_splice($activities, 0, $this->batchSize)) {
            // Формируем массив команд на добавление активностей
            $commandParams = [];
            foreach ($activitiesChunk as $index => $activity) {
                $commandParams[ $index ] = [
                    'fields' => $activity
                ];
            }
            $commands = $this->buildCommands('crm.activity.add', $commandParams);
            $activityResult = $this->batchRequest($commands);

            // Сравниваем число команд и число id в ответе
            $sent = count($commandParams);
            $received = count($activityResult);
            if ($received != $sent) {
                $jsonResponse = $this->toJSON($this->lastResponse);
                throw new Bitrix24APIException(
                    "Невозможно пакетно добавить активности ({$sent}/{$received}): {$jsonResponse}"
                );
            }

            $activityResults = array_merge($activityResults, $activityResult);
        }

        return $activityResults;
    }
}
