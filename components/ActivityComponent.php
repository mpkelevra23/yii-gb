<?php

namespace app\components;

use app\models\Activity;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

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
     * @param $params
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
     * @param Activity $activity
     * @return bool
     * @throws \Exception
     */
    public function createActivity(Activity $activity): bool
    {
        $activity->setScenario($activity::SCENARIO_CREATE);
        if ($activity->validate()) {
            $path = $this->getSaveFilePath($activity);
            $name = random_int(0, 9999) . time() . '.' . $activity->image->getExtension();
            if (!$activity->image->saveAs($path . $name)) {
                $activity->addError('image', 'Файл не удалось переместить');
                return false;
            }
        }
        return true;
    }

    //TODO Убрать это?
//    private function uploadFile(Activity $activity): void
//    {
////        $activity->image = UploadedFile::getInstance($activity, 'image');
//        $activity->image->saveAs('uploads/' . $activity->image->baseName . '.' . $activity->image->extension);
//    }

    private function getSaveFilePath(&$activity)
    {
        return Yii::getAlias('@app/web/files/');
    }

    /**
     * @param Activity $activity
     * @return array
     */
    public function ajaxValidation(Activity $activity): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $activity->setScenario($activity::SCENARIO_CREATE);
        return ActiveForm::validate($activity, ['email', 'email_confirm']);
    }
}