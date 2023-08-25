<?php

namespace app\controllers;

use app\base\BaseController;
use app\models\dao\DAOComponent;
use Yii;
use yii\db\Exception;

class DaoController extends BaseController
{

    private DAOComponent $dao;

    /**
     * Присвоение DAO компонента через конструктор класса, контроллера
     *
     * @param $id
     * @param $module
     * @param DAOComponent $dao
     * @param array $config
     */
    public function __construct($id, $module, DAOComponent $dao, array $config = [])
    {
        $this->dao = $dao;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionTest(): string
    {
        /*
         * Присвоение DAO компонента через сервис локатор Yii
         */
        $dao = Yii::$app->dao;

//        $dao->insertTest();
//        $dao->insertTestUseCallback();

        $users = $this->dao->getAllUsers();
        $user_activity = $dao->getUserActivity(1);
        $count_notification_activity = $dao->countNotificationActivity();
        $all_user_activity = $dao->getAllUserActivity(2);
        $activity_reader = $dao->getActivityReader();
        /*
         * compact предназначен для создания ассоциативного массива из имеющихся переменных и их значений.
         * Это часто используется для передачи переменных из текущей области видимости в шаблонизаторы или функции,
         * которые ожидают данные в виде массива.
         */
        return $this->render('test', compact('users', 'user_activity', 'count_notification_activity', 'all_user_activity', 'activity_reader'));
    }

}