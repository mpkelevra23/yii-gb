<?php

namespace app\models;

use DateTime;
use yii\base\Model;

class Activity extends Model
{
    /**
     * Название события
     *
     * @var string
     */
    public $title;
    /**
     * День начала события. Хранится в Unix timestamp
     *
     * @var DateTime
     */
    public $start_day;
    /**
     * День завершения события. Хранится в Unix timestamp
     *
     * @var DateTime
     */
    public $end_day;
    /**
     * ID автора, создавшего события
     *
     * @var int
     */
    public $id_author;
    /**
     * Описание события
     *
     * @var string
     */
    public $description;
    /**
     * Блокирующие событие
     *
     * @var bool
     */
    public $is_blocked;
    /**
     * Повторяющиеся событие
     *
     * @var bool
     */
    public $is_repeat;

    public function rules(): array
    {
        return [
            ['title', 'string', 'max' => 150, 'min' => 3],
            [['is_blocked', 'is_repeat'], 'boolean'],
            [['start_day', 'end_day'], 'datetime', 'format' => 'yyyy-MM-dd'],
            ['end_day', 'compare', 'compareAttribute' => 'start_day', 'operator' => '>='],
            [['title', 'start_day'], 'required'],
//            ['end_day', 'validateEndDayAfterStartDay'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название события',
            'start_day' => 'Дата начала',
            'end_day' => 'Дата завершения',
            'id_author' => 'ID автора',
            'description' => 'Описание события',
            'is_blocked' => 'Блокирующее событие',
            'is_repeat' => 'Повторяющиеся событие'
        ];
    }

    /**
     * Можно добавить кастомный валидатор для сравнения даты начала и даты конца события,
     * можно использовать, но лучше прописать такое с помощью CompareValidator
     *
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateEndDayAfterStartDay($attribute, $params)
    {
        if ($this->end_day < $this->start_day) {
            $this->addError($attribute, 'Дата завершения не может быть раньше даты начала.');
        }
    }
}