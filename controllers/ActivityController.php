<?php

namespace app\controllers;

use app\base\BaseController;
use app\controllers\actions\ActivityCreateAction;

class ActivityController extends BaseController
{
    public function actions(): array
    {
        return [
            'create' => ActivityCreateAction::class
        ];
    }
}