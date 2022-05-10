<?php

use yii\db\Migration;

/**
 * Class m220507_091814_add_supplier_seeds
 */
class m220507_091814_add_supplier_seeds extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $seeds      = [];
        $codePrefix = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codePrefix = str_split($codePrefix);
        $numbers    = range(1, 99);
        foreach ($codePrefix as $prefix) {
            foreach ($numbers as $number) {
                $seeds[] = ['T_DATA' . uniqid(), $prefix . str_pad($number, 2, 0, STR_PAD_LEFT)];
            }
        }

        $this->batchInsert('supplier', ['name', 'code'], $seeds);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('supplier', 'name LIKE "T_DATA%"');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220507_091814_add_supplier_seeds cannot be reverted.\n";

        return false;
    }
    */
}
