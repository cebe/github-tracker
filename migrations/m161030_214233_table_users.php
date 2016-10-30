<?php

use yii\db\Migration;

class m161030_214233_table_users extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(320)->notNull(),
            'github_id' => $this->bigInteger()->notNull(),
            'auth_token' => $this->string(255),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
