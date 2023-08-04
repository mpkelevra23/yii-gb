<?php

namespace app\controllers\actions;

use app\components\ActivityComponent;
use app\models\Activity;
use yii\base\Action;
use Yii;
use yii\base\InvalidConfigException;

class ActivityCreateAction extends Action
{
    /**
     * @throws InvalidConfigException
     */
    public function run(): string
    {
        /**
         * Можно вызвать компонент через сервис локатор так как он зарегистрирован в components в файле config/web.php
         * или создать компонент через Yii::createObject
         * @var ActivityComponent $component
         */
//        $component = Yii::$app->activity;
        $component = Yii::createObject(['class' => ActivityComponent::class, 'activity_class' => Activity::class]);

        if (Yii::$app->request->isPost) {
            $activity = $component->getModel(Yii::$app->request->post());
            $component->createActivity($activity);
        } else {
            $activity = $component->getModel();
        }

        return $this->controller->render('create', ['activity' => $activity]);
    }

}