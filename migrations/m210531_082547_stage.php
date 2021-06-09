<?php

use yii\db\Migration;

/**
 * Class m210531_082547_stage
 */
class m210531_082547_stage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('stage', [
            'id' => $this->primaryKey(),
            'date_stage' => $this->integer('11'),
            'building_id' => $this->integer('11'),
            'img' => $this->string(500),
            'description' => $this->string(500),
        ], $tableOptions);
        $this->addForeignKey(
            'fk-stage-b',
            'stage',
            'building_id',
            'building',
            'id',
            'CASCADE',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210531_082547_stage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210531_082547_stage cannot be reverted.\n";

        return false;
    }
    */
}
