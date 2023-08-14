<?php

namespace app\controllers\actions;

use app\components\ActivityComponent;
use app\models\Activity;
use Exception;
use Yii;
use yii\base\Action;

/**
 * Class ActivityCreateAction
 * @package app\controllers\actions
 */
class ActivityCreateAction extends Action
{

    /**
     * @return array|string
     */
    public function run()
    {
        try {
            /**
             * Можно вызвать компонент через сервис локатор так как он зарегистрирован в components в файле config/web.php
             * или создать компонент через Yii::createObject
             * @var ActivityComponent $component
             */
//            $component = Yii::$app->activity;
            $component = Yii::createObject([
                'class' => ActivityComponent::class,
                'activity_class' => Activity::class,
            ]);

            if (Yii::$app->request->isPost) {
                $activity = $component->getModel(Yii::$app->request->post());

                if (Yii::$app->request->isAjax) {
                    return $component->ajaxValidation($activity);
                }

                if ($this->createActivity($component, $activity)) {
                    return $this->controller->render('view', ['activity' => $activity]);
                }

            } else {
                $activity = $component->getModel();
            }
            return $this->controller->render('create', ['activity' => $activity]);

        } catch (Exception $e) {
            return $this->controller->render('site/error',
                [
                    'name' => 'An error occurred',
                    'message' => $e->getMessage(),
                ]);
        }
    }

    /**
     * @param ActivityComponent $component
     * @param Activity $activity
     * @return bool
     * @throws Exception
     */
    private function createActivity(ActivityComponent $component, Activity $activity): bool
    {
        if ($component->createActivity($activity)) {
            Yii::$app->session->setFlash('success', 'Активность успешно создана.');
            return true;
        }

        Yii::$app->session->setFlash('error', 'Не удалось создать активность.');
        return false;
    }
}
