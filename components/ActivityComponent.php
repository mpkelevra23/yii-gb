<?php

namespace app\components;

use app\models\Activity;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

class ActivityComponent extends Component
{
    public string $activity_class;

    /**
     * Выполняется перед созданием объекта
     * @return void
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->activity_class)) {
            throw new Exception('Exception, need Activity class');
        }
    }

    /**
     * Создает новую модель активности с заданными параметрами.
     * @param null $params Параметры модели
     * @return Activity
     */
    public function getModel($params = null): Activity
    {
        /**
         * @var Activity $model
         */
        $model = new $this->activity_class;

        if ($params && is_array($params)) {
            $model->load($params);
        }
        return $model;
    }

    /**
     * Создает новую активность и сохраняет изображение, если оно предоставлено.
     * @param Activity $activity Модель активности
     * @return bool Успешно ли создана активность и сохранено изображение
     * @throws \Exception
     */
    public function createActivity(Activity $activity): bool
    {
        $activity->setScenario($activity::SCENARIO_CREATE);

        if ($activity->validate()) {
            if ($activity->images && !$this->uploadFile($activity)) {
                $activity->addError('images', 'Файл не удалось переместить');
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Загружает файл изображения для активности.
     * @param Activity $activity Модель активности
     * @return bool Успешно ли загружено изображение
     * @throws \Exception
     */
    private function uploadFile(Activity $activity): bool
    {
        try {
            foreach ($activity->images as $image) {
                $image->saveAs($this->getSaveFilePath() . $this->generateFileName($image));
            }
            return true;
        } catch (Exception $exception) {
            echo 'Ошибка при загрузке файла: ' . $exception->getMessage();
            return false;
        }
    }

    /**
     * Генерирует уникальное имя файла для сохранения изображения.
     * @param UploadedFile $image Загруженное изображение
     * @return string Уникальное имя файла
     * @throws \Exception
     */
    private function generateFileName(UploadedFile $image): string
    {
        return random_int(0, 9999) . time() . '.' . $image->getExtension();
    }

    /**
     * Возвращает путь к директории для сохранения файлов.
     * @return string Путь к директории
     */
    private function getSaveFilePath(): string
    {
        return Yii::getAlias('@app/web/files/');
    }

    /**
     * Выполняет AJAX-валидацию модели активности.
     * @param Activity $activity Модель активности для валидации
     * @return array Результат валидации в формате JSON
     */
    public function ajaxValidation(Activity $activity): array
    {
        $activity->setScenario($activity::SCENARIO_CREATE);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($activity, ['email', 'email_confirm']);
    }
}
