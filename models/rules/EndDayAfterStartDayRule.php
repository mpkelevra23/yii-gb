<?php

namespace app\models\rules;

use yii\validators\Validator;

class EndDayAfterStartDayRule extends Validator
{

    public function validateAttribute($model, $attribute)
    {
        if ($model->end_day <= $model->start_day) {
            $this->addError($model, $attribute, 'Дата завершения не может быть раньше даты начала.');
        }
    }

}