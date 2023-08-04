<?php

namespace app\controllers\actions;

use yii\base\Action;

class ActivityAction extends Action
{

    public function run(): string
    {
        return $this->controller->render('index');
    }

}