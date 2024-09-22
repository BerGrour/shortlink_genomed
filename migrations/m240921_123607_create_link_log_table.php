<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link_log}}`.
 */
class m240921_123607_create_link_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%link_log}}', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx-link_log-link_id', 'link_log', 'link_id');
        $this->addForeignKey('fk-link_log-link_id', 'link_log', 'link_id', 'links', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-link_log-link_id','link_log');
        $this->dropIndex('idx-link_log-link_id','link_log');

        $this->dropTable('{{%link_log}}');  
    }
}
