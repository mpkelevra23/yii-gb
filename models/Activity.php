<?php

namespace app\models;

use app\models\rules\EndDayAfterStartDayRule;
use DateTime;
use yii\base\Model;
use yii\web\UploadedFile;

class Activity extends Model
{
    /**
     * Сценарий при создании активности
     */
    const SCENARIO_CREATE = 'create';
    /**
     * Сценарий при обновлении активности
     */
    const SCENARIO_UPDATE = 'update';
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
    /**
     * Email
     *
     * @var string
     */
    public $email;
    /**
     * Проверка Email
     *
     * @var string
     */
    public $email_confirm;
    /**
     * Поле уведомления
     *
     * @var bool
     */
    public $use_notification;
    /**
     * Изображение
     *
     * @var UploadedFile
     */
    public $image;

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        // Добавление сценариев
        $scenarios[self::SCENARIO_CREATE] = [
            'title',
            'start_day',
            'end_day',
            'id_author',
            'description',
            'is_blocked',
            'is_repeat',
            'email',
            'email_confirm',
            'use_notification',
            'image',
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'title',
            'start_day',
            'end_day',
            'id_author',
            'description',
            'is_blocked',
            'is_repeat',
        ];

        return $scenarios;
    }

    /**
     * Позволяет работать с данными до момента валидации.
     * Пример преобразования даты в нужный формат.
     * @return bool
     */
    public function beforeValidate(): bool
    {
        $this->upload();
        if (!empty($this->start_day)) {
            $this->start_day = DateTime::createFromFormat('Y-m-d', $this->start_day)->format('Y-m-d');
        }
        return parent::beforeValidate();
    }

    public function rules(): array
    {
        return [
            ['title', 'string', 'max' => 150, 'min' => 3],
            ['title', 'trim'],
            [['is_blocked', 'is_repeat', 'use_notification'], 'boolean'],
            [['title', 'start_day'], 'required'],
            [['email', 'email_confirm'], 'email'],
            [['email', 'email_confirm'], 'required', 'when' => function ($model) {
                return (bool)$model->use_notification;
            }],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ['email_confirm', 'compare', 'compareAttribute' => 'email', 'message' => 'Почтовый адрес не совпадает'],
            [['start_day', 'end_day'], 'datetime', 'format' => 'yyyy-MM-dd', 'message' => 'Формат даты должен быть yyyy-MM-dd'],
            ['end_day', 'compare', 'compareAttribute' => 'start_day', 'operator' => '>='],
//            ['start_day', 'compare', 'compareAttribute' => 'end_day', 'operator' => '<='],
//            ['end_day', 'validateEndDayAfterStartDay'],
//            ['end_day', EndDayAfterStartDayRule::class],
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
            'is_repeat' => 'Повторяющиеся событие',
            'email' => 'Почтовый адрес',
            'email_confirm' => 'Повторите почтовый адрес',
            'use_notification' => 'Получать уведомления на почтовый адрес?',
            'image' => 'Изображение',
        ];
    }


    public function upload(): void
    {
        $this->image = UploadedFile::getInstance($this, 'image');
    }

    /**
     * Можно добавить кастомный валидатор для сравнения даты начала и даты конца события,
     * можно использовать, но лучше прописать такое с помощью CompareValidator.
     * Или добавить класс в models/rules и наследоваться от yii\validators\Validator
     *
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateEndDayAfterStartDay($attribute, $params)
    {
        if ($this->end_day <= $this->start_day) {
            $this->addError($attribute, 'Дата завершения не может быть раньше даты начала.');
        }
    }
}