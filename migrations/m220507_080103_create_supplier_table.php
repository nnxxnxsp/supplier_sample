<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier}}`.
 */
class m220507_080103_create_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('supplier', [
            'id'       => $this->primaryKey(),
            'name'     => $this->string(50)->notNull()->defaultValue(''),
            'code'     => $this->char(3)->null()->unique(),
            't_status' => "ENUM('ok', 'hold') NOT NULL DEFAULT 'ok'",
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplier}}');
    }
}
