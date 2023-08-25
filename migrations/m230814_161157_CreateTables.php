<?php

use yii\db\Migration;

/**
 * Class m230814_161157_CreateTables
 */
class m230814_161157_CreateTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('activity',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(150)->notNull(),
                'description' => $this->text(),
                'start_day' => $this->dateTime()->notNull(),
                '$end_day' => $this->dateTime(),
                '$email' => $this->string(150),
                'email_confirm' => $this->string(150),
                'is_blocked' => $this->boolean()->notNull()->defaultValue(false),
                'is_repeat' => $this->boolean()->notNull()->defaultValue(false),
                'use_notification' => $this->boolean()->notNull()->defaultValue(false),
                'date_created' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
            ]
        );

        $this->createTable('users',
            [
                'id' => $this->primaryKey(),
                'email' => $this->string(150)->unique()->notNull(),
                'password_hash' => $this->string(300)->notNull(),
                'token' => $this->string(300)->notNull(),
                'date_created' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('activity');
        $this->dropTable('users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230814_161157_CreateTables cannot be reverted.\n";

        return false;
    }
    */
}
