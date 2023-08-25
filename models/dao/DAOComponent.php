<?php

namespace app\models\dao;

use Throwable;
use Yii;
use yii\base\Component;
use yii\db\Connection;
use yii\db\DataReader;
use yii\db\Exception;
use yii\db\Query;
use yii\log\Logger;
use yii\db\Command;
use yii\db\Transaction;

/**
 * Компонент для доступа к данным (Data Access Object) для работы с таблицами activity и user.
 */
class DAOComponent extends Component
{
    /**
     * @return Connection
     */
    public function getDb(): Connection
    {
        return Yii::$app->db;
    }

    /**
     * Получение всех пользователей.
     *
     * @return array|DataReader
     * @throws Exception
     */
    public function getAllUsers()
    {
        $sql = 'SELECT * FROM `users`';

        return $this->getDb()->createCommand($sql)->queryAll();
    }

    /**
     * Получение активностей пользователя по его ID.
     *
     * Этот метод выполняет SQL-запрос к таблице 'activity' для получения всех активностей,
     * которые связаны с определенным пользователем, заданным через его ID.
     *
     * @param int $user_id ID пользователя, для которого необходимо получить активности.
     * @return array|DataReader Возвращает массив активностей пользователя. Каждая активность представляется ассоциативным массивом
     * с полями, соответствующими столбцам таблицы 'activity'.
     * @throws Exception В случае возникновения ошибки при выполнении SQL-запроса.
     * @see Command::queryAll
     */
    public function getUserActivity(int $user_id)
    {
        // Формируем SQL-запрос для выборки активностей пользователя по его ID
        $sql = 'SELECT * FROM `activity` WHERE user_id=:user_id';

        // Выполняем SQL-запрос с использованием параметра:user_id для передачи значения пользователя
        return $this->getDb()->createCommand($sql, [':user_id' => $user_id])->queryAll();
    }

    /**
     * Подсчет количества активностей, использующих уведомления.
     *
     * Этот метод выполняет SQL-запрос к таблице 'activity' для подсчета количества активностей,
     * которые используют уведомления (поле 'use_notification' имеет значение true).
     *
     * @return false|int|string|DataReader|null Возвращает количество активностей, использующих уведомления.
     * @throws Exception В случае возникновения ошибки при выполнении SQL-запроса.
     * @see Command::queryScalar
     */
    public function countNotificationActivity()
    {
        // Формируем SQL-запрос для подсчета количества активностей, использующих уведомления
        $sql = 'SELECT COUNT(use_notification) FROM `activity` WHERE use_notification = true';

        // Выполняем SQL-запрос и получаем результат подсчета, используя метод queryScalar() из объекта yii\db\Command
        return $this->getDb()->createCommand($sql)->queryScalar();
    }

    /**
     * Получение всех активностей пользователя.
     *
     * Этот метод выполняет запрос к базе данных для получения всех активностей, связанных с определенным пользователем.
     * Запрос объединяет таблицы 'activity' и 'users' по полю 'user_id' и фильтрует результаты по переданному ID пользователя.
     * Активности отображаются в порядке убывания ID, и ограничиваются 10 записями.
     *
     * @param int $user_id ID пользователя, для которого необходимо получить активности.
     * @return array Возвращает массив активностей пользователя. Каждая активность представляется ассоциативным массивом
     * с полями 'title', 'email' и 'start_day'.
     * @see Query::select
     * @see Query::from
     * @see Query::innerJoin
     * @see Query::andWhere
     * @see Query::orderBy
     * @see Query::limit
     * @see Query::all
     */
    public function getAllUserActivity(int $user_id): array
    {
        // Создаем объект запроса Query
        $query = new Query();

        // Формируем запрос для выборки активностей пользователя
        $query->select(['title', 'email', 'start_day'])
            ->from('activity a')
            ->innerJoin('users u', 'a.user_id=u.id')
            ->andWhere(['a.user_id' => $user_id])
            ->andWhere('a.date_created<=:date', [':date' => date('Y-m-d H:i:s')])
            ->orderBy(['a.id' => SORT_DESC])
            ->limit(10);

        // Выполняем запрос и получаем массив ассоциативных массивов, представляющих активности пользователя
        return $query->all();
    }


    /**
     * Возвращает объект DataReader для выполнения запроса на получение всех записей из таблицы activity.
     *
     *  Этот метод выполняет SQL-запрос для выборки всех данных из таблицы activity и возвращает
     *  объект DataReader, который предоставляет поток данных для пошагового чтения результатов запроса.
     *  DataReader может быть полезен при обработке большого объема данных, так как он позволяет читать
     *  результаты запроса по мере необходимости, не загружая их все сразу в память.
     *
     *  Пример использования:
     *  ```php
     *  $dao = new DAOComponent();
     *  $dataReader = $dao->getActivityReader();
     *
     *  while (($row = $dataReader->read()) !== false) {
     *      // Обработка данных строки $row
     *  }
     *
     *  // Не забудьте закрыть DataReader после завершения чтения
     *  $dataReader->close();
     *  ```
     *
     * @return DataReader объект DataReader для выполнения запроса на получение всех записей из таблицы activity.
     * @throws Exception если возникла ошибка при выполнении SQL-запроса.
     * @see DataReader
     */
    public function getActivityReader(): DataReader
    {
        $sql = 'SELECT * FROM `activity`';

        return $this->getDb()->createCommand($sql)->query();
    }

    /**
     * Вставка тестовых данных в таблицу activity с использованием транзакции.
     *
     * Этот метод предназначен для вставки тестовых данных в таблицу 'activity' с использованием механизма транзакций.
     * Он создает транзакцию и выполняет два SQL-запроса на вставку тестовых записей в таблицу 'activity'.
     * Если оба запроса успешно выполнены, транзакция коммитится. В случае возникновения ошибки при выполнении запросов,
     * транзакция откатывается, и ошибка записывается в лог.
     *
     * @return void
     * @see Connection::beginTransaction
     * @see Command::execute
     * @see Transaction::commit
     * @see Transaction::rollBack
     * @see Logger::LEVEL_ERROR
     */
    public function insertTest(): void
    {
        // Начало транзакции
        $transaction = $this->getDb()->beginTransaction();
        try {
            // Выполняем первый SQL-запрос на вставку тестовых данных
            $this->getDb()->createCommand()->insert('activity', [
                'user_id' => 2,
                'title' => 'Заголовок 7',
                'start_day' => date('Y-m-d H:i:s')
            ])->execute();

            // Выполняем второй SQL-запрос на вставку тестовых данных
            $this->getDb()->createCommand()->insert('activity', [
                'user_id' => 1,
                'title' => 'Заголовок 8',
                'start_day' => date('Y-m-d H:i:s')
            ])->execute();

            // Если оба запроса успешно выполнены, коммитим транзакцию
            $transaction->commit();

        } catch (Exception $exception) {
            // В случае ошибки, записываем ее в лог и откатываем транзакцию
            Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
            $transaction->rollBack();
        }
    }

    /**
     * Вставка тестовых данных в таблицу activity с использованием механизма транзакции через callback.
     *
     * Этот метод предназначен для вставки тестовых данных в таблицу 'activity' с использованием механизма транзакций
     * через callback-функцию. Он использует метод `transaction()` из `Yii::$app->db` для создания транзакции,
     * внутри которой выполняются два SQL-запроса на вставку тестовых записей в таблицу 'activity'.
     * Если оба запроса успешно выполнены, транзакция коммитится автоматически. В случае возникновения ошибки при
     * выполнении запросов, транзакция откатывается автоматически, и ошибка записывается в лог.
     *
     * @return void
     * @throws Throwable В случае возникновения ошибки при выполнении транзакции.
     * @see Connection::transaction
     * @see Command::insert
     * @see Transaction::commit
     * @see Transaction::rollBack
     * @see Logger::LEVEL_ERROR
     */
    public function insertTestUseCallback(): void
    {
        // Используем метод transaction() для создания транзакции с использованием callback-функции
        Yii::$app->db->transaction(function ($db) {
            // Внутри callback выполняем первый SQL-запрос на вставку тестовых данных
            $db->createCommand()->insert('activity', [
                'user_id' => 2,
                'title' => 'Заголовок 9',
                'start_day' => date('Y-m-d H:i:s')
            ])->execute();

            // Внутри callback выполняем второй SQL-запрос на вставку тестовых данных
            $db->createCommand()->insert('activity', [
                'user_id' => 1,
                'title' => 'Заголовок 10',
                'start_day' => date('Y-m-d H:i:s')
            ])->execute();
        });
    }

}