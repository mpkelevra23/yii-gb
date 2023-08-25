<?php

namespace app\models;

use app\models\rules\EndDayAfterStartDayRule;
use DateTime;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Модель для работы с активностями.
 *
 * @property string $title Название события
 * @property DateTime $start_day День начала события
 * @property DateTime $end_day День завершения события
 * @property int $user_id ID пользователя, создавшего событие
 * @property string $description Описание события
 * @property bool $is_blocked Блокирующее событие
 * @property bool $is_repeat Повторяющееся событие
 * @property string $email Email
 * @property string $email_confirm Подтверждение Email
 * @property bool $use_notification Поле уведомлений
 * @property UploadedFile[] $images Изображения
 */
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
     * ID пользователя, создавшего события
     *
     * @var int
     */
    public $user_id;
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
     * Изображения
     *
     * @var UploadedFile[]
     */
    public $images;
    /**
     * Сценарий при создании активности
     */
    const SCENARIO_CREATE = 'create';

    /**
     * Сценарий при обновлении активности
     */
    const SCENARIO_UPDATE = 'update';

    /**
     * Разрешенные расширения для изображений
     */
    const IMAGE_EXTENSIONS = ['png', 'jpg'];

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        // Добавление сценариев
        $scenarios[self::SCENARIO_CREATE] = [
            'title',
            'start_day',
            'end_day',
            'user_id',
            'description',
            'is_blocked',
            'is_repeat',
            'email',
            'email_confirm',
            'use_notification',
            'images',
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'title',
            'start_day',
            'end_day',
            'user_id',
            'description',
            'is_blocked',
            'is_repeat',
        ];

        return $scenarios;
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        $this->uploadImages();
        $this->transformStartDate();
        return parent::beforeValidate();
    }

    /**
     * Загрузка изображения перед валидацией.
     *
     * @return void
     */
    private function uploadImages(): void
    {
        $this->images = UploadedFile::getInstances($this, 'images');
    }

    /**
     * Преобразование даты начала события.
     *
     * @return void
     */
    private function transformStartDate(): void
    {
        if (!empty($this->start_day)) {
            $this->start_day = DateTime::createFromFormat('Y-m-d', $this->start_day)->format('Y-m-d');
        }
    }

    /**
     * Правила валидации для модели Activity.
     *
     * @return array
     */
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
            [['images'], 'file', 'skipOnEmpty' => true, 'extensions' => implode(', ', self::IMAGE_EXTENSIONS), 'maxFiles' => 3],
            ['email_confirm', 'compare', 'compareAttribute' => 'email', 'message' => 'Почтовый адрес не совпадает'],
            [['start_day', 'end_day'], 'datetime', 'format' => 'yyyy-MM-dd', 'message' => 'Формат даты должен быть yyyy-MM-dd'],
            ['end_day', 'compare', 'compareAttribute' => 'start_day', 'operator' => '>='],
//            ['start_day', 'compare', 'compareAttribute' => 'end_day', 'operator' => '<='],
//            ['end_day', 'validateEndDayAfterStartDay'],
//            ['end_day', EndDayAfterStartDayRule::class],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'title' => 'Название события',
            'start_day' => 'Дата начала',
            'end_day' => 'Дата завершения',
            'user_id' => 'ID автора',
            'description' => 'Описание события',
            'is_blocked' => 'Блокирующее событие',
            'is_repeat' => 'Повторяющееся событие',
            'email' => 'Адрес электронной почты',
            'email_confirm' => 'Повторите адрес электронной почты',
            'use_notification' => 'Получать уведомления на почтовый адрес?',
            'images' => 'Изображение',
        ];
    }

    /**
     * Валидация даты завершения после даты начала.
     *
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateEndDayAfterStartDay($attribute, $params): void
    {
        if ($this->end_day <= $this->start_day) {
            $this->addError($attribute, 'Дата завершения не может быть раньше даты начала.');
        }
    }
}
