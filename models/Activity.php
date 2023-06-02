<?php

namespace app\models;

use yii\base\Model;

class Activity extends Model
{
    public $title;
    public $description;
    public $date_start;
    public $is_blocked;


    function rules(): array
    {
        return [
            ['title', 'string', 'max' => 150, 'min' => 3],
            ['is_blocked', 'boolean'],
            ['date_start', 'datetime'],
            [['title', 'date_start'], 'required'],
        ];
    }

    function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'description' => 'Описание',
            'date_start' => 'Дата начала',
            'is_blocked' => 'Блокирующее',
        ];
    }
}