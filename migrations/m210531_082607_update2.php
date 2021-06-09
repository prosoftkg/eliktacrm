<?php

use yii\db\Migration;

/**
 * Class m210531_082607_update2
 */
class m210531_082607_update2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('object', 'img', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210531_082607_update2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210531_082607_update2 cannot be reverted.\n";

        return false;
    }
    */
}
