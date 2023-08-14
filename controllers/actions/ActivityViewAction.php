<?php

namespace app\controllers\actions;

use yii\base\Action;

class ActivityViewAction extends Action
{
    public function run()
    {
        return $this->controller->render('view', ['message' => 'Просмотр активности']);
    }

}