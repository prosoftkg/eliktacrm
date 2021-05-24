<?php

use yii\db\Migration;

/**
 * Class m210421_121039_update1
 */
class m210421_121039_update1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('fav', 'url', $this->string(200));
        $this->addColumn('fav', 'title', $this->string(255));
        $this->addColumn('fav', 'date_query', $this->integer());
        $this->addColumn('apartment', 'updated_at', $this->integer());
        $this->createIndex(
            'idx-apt-upd',
            'apartment',
            'updated_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210421_121039_update1 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210421_121039_update1 cannot be reverted.\n";

        return false;
    }
    */
}
