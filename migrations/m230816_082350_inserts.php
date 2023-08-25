<?php

use yii\db\Migration;

/**
 * Class m230816_082350_inserts
 */
class m230816_082350_inserts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('users',
            [
                'id' => '1',
                'email' => 'mpkelevra23',
                'password_hash' => '12345',
                'token' => '12345'
            ]
        );

        $this->insert('users',
            [
                'id' => '2',
                'email' => 'mpkelevra123',
                'password_hash' => '54321',
                'token' => '54321'
            ]
        );


        $this->batchInsert('activity',
            ['title', 'start_day', 'user_id', 'use_notification'],
            [
                ['Заголовок 1', date('Y-m-d'), 1, true],
                ['Заголовок 2', date('2020-12-12'), 1, false],
                ['Заголовок 3', date('Y-m-d'), 1, false],
                ['Заголовок 4', date('Y-m-d'), 2, false],
                ['Заголовок 5', date('2021-4-23'), 2, true],
                ['Заголовок 6', date('Y-m-d'), 2, false],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230816_082350_inserts cannot be reverted.\n";

        return false;
    }
    */
}
