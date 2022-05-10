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
        $okSeeds    = [];
        $holdSeeds  = [];
        $codePrefix = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codePrefix = str_split($codePrefix);
        $numbers    = range(1, 99);
        foreach ($codePrefix as $prefix) {
            foreach ($numbers as $number) {
                $okSeeds[] = ['T_DATA' . uniqid(), $prefix . str_pad($number, 2, 0, STR_PAD_LEFT)];
            }
        }

        foreach ($numbers as $number) {
            $holdSeeds[] = ['测试数据' . uniqid(), '#' . str_pad($number, 2, 0, STR_PAD_LEFT), 'hold'];
        }

        $this->batchInsert('supplier', ['name', 'code'], $okSeeds);
        $this->batchInsert('supplier', ['name', 'code', 't_status'], $holdSeeds);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('supplier', 'name LIKE "T_DATA%"');
        $this->delete('supplier', 'name LIKE "测试数据%"');

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
