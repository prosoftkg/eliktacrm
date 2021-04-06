<?php

use yii\db\Migration;

/**
 * Class m210406_095135_chatline
 */
class m210406_095135_chatline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('chatline', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer('11')->notNull()->defaultValue(0),
            'sender_id' => $this->integer('11')->notNull()->defaultValue(0),
            'receiver_id' => $this->integer('11')->notNull()->defaultValue(0),
            'is_read' => $this->boolean(),
            'text' => $this->string('500')->notNull(),
            'sent_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx_chatline_chat_id', 'chatline', 'chat_id');
        $this->addForeignKey(
            'fk-chatline-chat',
            'chatline',
            'chat_id',
            'chat',
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
        echo "m210406_095135_chatline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210406_095135_chatline cannot be reverted.\n";

        return false;
    }
    */
}
