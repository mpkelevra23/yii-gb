<?php

namespace app\models;

use yii\base\Model;

class Day extends Model
{
    /**
     * Рабочий день
     * Пока не знаю зачем это нужно, ведь можно только указывать что день выходной или нет
     *
     * @var bool
     */
    public $weekday;
    /**
     * Выходной день
     *
     * @var bool
     */
    public $day_off;
    /**
     * Активность
     *
     * @var Activity
     */
    public $activity;

    public function rules()
    {
        return [
            [['day_off', 'weekday'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'weekday' => 'Рабочий день',
            'day_off' => 'Выходной'
        ];
    }

}