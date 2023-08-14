<?php

namespace app\controllers\actions;

use yii\base\Action;

class ActivityUpdateAction extends Action
{
    public function run()
    {
        return $this->controller->render('update', ['message' => 'Обновление активности']);
    }

}