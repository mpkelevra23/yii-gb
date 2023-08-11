<?php

namespace app\controllers\actions;

use app\components\ActivityComponent;
use app\models\Activity;
use Exception;
use yii\base\Action;
use Yii;
use yii\base\InvalidConfigException;

class ActivityCreateAction extends Action
{
    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function run()
    {
        /**
         * Можно вызвать компонент через сервис локатор так как он зарегистрирован в components в файле config/web.php
         * или создать компонент через Yii::createObject
         * @var ActivityComponent $component
         */
//        $component = Yii::$app->activity;
        $component = Yii::createObject([
            'class' => ActivityComponent::class,
            'activity_class' => Activity::class,
        ]);

        if (Yii::$app->request->isPost) {

            $activity = $component->getModel(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                return $component->ajaxValidation($activity);
            }
            if ($component->createActivity($activity)) {
                Yii::$app->session->setFlash('success', 'Активность успешно создана.');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось создать активность.');
            }
        } else {
            $activity = $component->getModel();
        }

        return $this->controller->render('create', ['activity' => $activity]);
    }
}