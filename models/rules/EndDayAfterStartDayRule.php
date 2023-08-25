<?php

namespace app\models\rules;

use yii\validators\Validator;

class EndDayAfterStartDayRule extends Validator
{
    /**
     * @param $model
     * @param $attribute
     * @return void
     */
    public function validateAttribute($model, $attribute)
    {
        if ($model->end <= $model->start_day) {
            $this->addError($model, $attribute, 'Дата завершения не может быть раньше даты начала.');
        }
    }

}