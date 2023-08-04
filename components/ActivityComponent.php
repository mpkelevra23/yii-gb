<?php

namespace app\components;

use app\models\Activity;
use yii\base\Component;
use yii\base\Exception;

class ActivityComponent extends Component
{
    public string $activity_class;

    /**
     * Выполняется перед созданием объекта
     * @return void
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->activity_class)) {
            throw new Exception('Exception, need Activity class');
        }
    }


    /**
     * @param $params
     * @return Activity
     */
    public function getModel($params = null): Activity
    {
        /**
         * @var Activity $model
         */
        $model = new $this->activity_class;

        if ($params && is_array($params)) {
            $model->load($params);
        }
        return $model;
    }

    /**
     * @param Activity $model
     * @return bool
     */
    public function createActivity(Activity $model): bool
    {
        return $model->validate();
    }


}