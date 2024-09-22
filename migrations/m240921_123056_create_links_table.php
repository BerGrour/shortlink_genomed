<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%links}}`.
 */
class m240921_123056_create_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull(),
            'short_url' => $this->string()->notNull()->unique(),
            'clicks' => $this->integer()->defaultValue(0)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%links}}');
    }
}
