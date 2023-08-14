<?php

namespace app\controllers;

use app\base\BaseController;
use app\controllers\actions\ActivityAction;
use app\controllers\actions\ActivityCreateAction;
use app\controllers\actions\ActivityUpdateAction;
use app\controllers\actions\ActivityViewAction;

class ActivityController extends BaseController
{
    public function actions(): array
    {
        return [
            'index' => ActivityAction::class,
            'create' => ActivityCreateAction::class,
            'view' => ActivityViewAction::class,
            'update' => ActivityUpdateAction::class,
        ];
    }
}