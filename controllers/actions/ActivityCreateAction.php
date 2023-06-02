<?php

namespace app\controllers\actions;

use app\components\ActivityComponent;
use app\models\Activity;
use yii\base\Action;
use Yii;

class ActivityCreateAction extends Action
{
    public function run()
    {
        /**
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

//        $activity = new Activity();
//        $activity->is_blocked = true;
//        $activity->title = 'title';

        return $this->controller->render('create', ['activity' => $activity]);


    }

}