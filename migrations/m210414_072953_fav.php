<?php

use yii\db\Migration;

/**
 * Class m210414_072953_fav
 */
class m210414_072953_fav extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('fav', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer('11'),
            'apartment_id' => $this->integer('11'),
        ], $tableOptions);

        $this->createIndex('idx_fav_usr_id', 'fav', 'user_id');
        $this->createIndex('idx_fav_apt_id', 'fav', 'apartment_id');
        $this->addForeignKey(
            'fk-fav-user',
            'fav',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-fav-apt',
            'fav',
            'apartment_id',
            'apartment',
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
        echo "m210414_072953_fav cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210414_072953_fav cannot be reverted.\n";

        return false;
    }
    */
}
