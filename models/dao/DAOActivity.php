<?php
namespace app\models\dao;

use app\models\Activity;
use Yii;
use yii\base\Component;
use yii\db\Connection;
use yii\db\Query;

class DAOActivity extends Component
{
    /**
     * @return Connection
     */
    public function getDb()
    {
        return Yii::$app->db;
    }

    /**
     * Получение списка активностей.
     *
     * @return array
     */
    public function getAllActivities(): array
    {
        $query = new Query();
        return $query->select('*')
            ->from('activity')
            ->orderBy(['start_day' => SORT_DESC])
            ->all();
    }

    /**
     * Получение активности по ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getActivityById($id)
    {
        return Activity::findOne($id);
    }

    /**
     * Создание новой активности.
     *
     * @param array $data
     * @return bool
     */
    public function createActivity($data): bool
    {
        $activity = new Activity();
        $activity->setScenario(Activity::SCENARIO_CREATE);
        $activity->load($data, '');

        if ($activity->validate()) {
            return $activity->save();
        }

        return false;
    }

    /**
     * Обновление активности.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateActivity($id, $data): bool
    {
        $activity = Activity::findOne($id);
        $activity->setScenario(Activity::SCENARIO_UPDATE);
        $activity->load($data, '');

        if ($activity->validate()) {
            return $activity->save();
        }

        return false;
    }

    /**
     * Удаление активности по ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteActivity($id): bool
    {
        $activity = Activity::findOne($id);
        if ($activity) {
            return $activity->delete();
        }
        return false;
    }
}
