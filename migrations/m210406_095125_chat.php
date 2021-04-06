<?php

use yii\db\Migration;

/**
 * Class m210406_095125_chat
 */
class m210406_095125_chat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('chat', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer('11'),
            'receiver_id' => $this->integer('11'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'subject' => $this->string('255')->notNull(),
            'subject_link' => $this->string('20')->notNull(),
            'archive' => $this->boolean(),
        ], $tableOptions);

        $this->createIndex('idx_sender_id', 'chat', 'sender_id');
        $this->createIndex('idx_receiver_id', 'chat', 'receiver_id');
        $this->addForeignKey(
            'fk-chat-sender',
            'chat',
            'sender_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-chat-receiver',
            'chat',
            'receiver_id',
            'user',
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
        echo "m210406_095125_chat cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210406_095125_chat cannot be reverted.\n";

        return false;
    }
    */
}
