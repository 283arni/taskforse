<?php

use yii\db\Migration;
use yii\db\Query;

class m260208_131702_fill_tasks_with_realistic_fake_data extends Migration
{
    private function getCategories()
    {
        return (new Query())
            ->select(['id'])
            ->from('categories')
            ->column();
    }

    private function getUserIds()
    {
        return (new Query())
            ->select(['id'])
            ->from('users') // или другое имя таблицы пользователей (например, `user`)
            ->limit(50)
            ->column();
    }

    private function getStatusIds()
    {
        return (new Query())
            ->select(['id'])
            ->from('statuses')
            ->column();
    }

    public function safeUp()
    {
        $faker = \Faker\Factory::create('ru_RU');

        $categoryIds = $this->getCategories();
        $userIds = $this->getUserIds();
        $statusIds = $this->getStatusIds();

        if (empty($categoryIds)) {
            echo "Ошибка: Нет данных в таблице 'categories'.\n";
            return false;
        }
        if (empty($userIds)) {
            echo "Ошибка: Нет пользователей в таблице 'users'.\n";
            return false;
        }
        if (empty($statusIds)) {
            echo "Ошибка: Нет данных в таблице 'statuses'.\n";
            return false;
        }

        $taskNames = [
            'Ремонт смесителя', 'Дизайн логотипа', 'Уборка квартиры',
            'Выгул собаки', 'Написание текста', 'Ремонт компьютера',
            'Перевод документа', 'Курьерская доставка', 'Фотосессия',
            'Обучение математике', 'Строительство забора', 'Пошив одежды'
        ];

        $descriptions = [
            'Требуется срочно выполнить работу качественно и в срок.',
            'Важно соблюсти все пожелания клиента.',
            'Можно приступать сразу после подтверждения.',
            'Подробности обсудим лично.',
            'Есть похожие проекты — покажу портфолио.'
        ];

        for ($i = 0; $i < 50; $i++) {
            $clientId = $faker->randomElement($userIds);
            $performerId = $faker->boolean(30) ? $faker->randomElement($userIds) : null; // 30% задач имеют исполнителя
            $statusId = $faker->randomElement($statusIds);

            // Если исполнитель назначен, статус не должен быть "новый"
            if ($performerId && in_array($statusId, [1])) { // если статус "новый"
                $statusId = $faker->randomElement(array_diff($statusIds, [1])); // исключим "новый"
            }

            $this->insert('tasks', [
                'name' => $faker->randomElement($taskNames) . ' ' . $faker->numberBetween(1, 100),
                'category_id' => $faker->randomElement($categoryIds),
                'description' => $faker->randomElement($descriptions) . ' ' . $faker->realText(150),
                'location' => $faker->city . ', ' . $faker->streetAddress,
                'budget' => $faker->boolean(70) ? $faker->numberBetween(500, 50000) : null, // 70% задач с бюджетом
                'expire_dt' => $faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d H:i:s'),
                'dt_add' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s'),
                'client_id' => $clientId,
                'performer_id' => $performerId,
                'status_id' => $statusId,
            ]);
        }

        echo "Добавлено 50 фейковых задач.\n";
    }

    public function safeDown()
    {
        $this->delete('tasks', 'dt_add BETWEEN NOW() - INTERVAL 30 DAY AND NOW()');
        echo "Удалены тестовые задачи.\n";
    }
}