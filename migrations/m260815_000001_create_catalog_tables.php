<?php

use yii\db\Migration;

class m260815_000001_create_catalog_tables extends Migration
{
    public function safeUp(): void
    {
        $options = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(), 'full_name' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(), 'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(), 'title' => $this->string(255)->notNull(),
            'publication_year' => $this->smallInteger()->notNull(), 'description' => $this->text(),
            'isbn' => $this->string(20)->notNull()->unique(), 'cover' => $this->string(255),
            'created_at' => $this->integer()->notNull(), 'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(), 'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY ([[book_id]], [[author_id]])',
        ], $options);
        if ($this->db->driverName !== 'sqlite') {
            $this->addForeignKey('fk-book-author-book', '{{%book_author}}', 'book_id', '{{%book}}', 'id', 'CASCADE');
            $this->addForeignKey('fk-book-author-author', '{{%book_author}}', 'author_id', '{{%author}}', 'id', 'CASCADE');
        }
        $this->createIndex('idx-book-author-author', '{{%book_author}}', 'author_id');
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(), 'author_id' => $this->integer()->notNull(),
            'email' => $this->string(255)->notNull(), 'created_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex('uq-subscription-author-email', '{{%subscription}}', ['author_id', 'email'], true);
        if ($this->db->driverName !== 'sqlite') {
            $this->addForeignKey('fk-subscription-author', '{{%subscription}}', 'author_id', '{{%author}}', 'id', 'CASCADE');
        }
    }
    public function safeDown(): void
    {
        $this->dropTable('{{%subscription}}'); $this->dropTable('{{%book_author}}');
        $this->dropTable('{{%book}}'); $this->dropTable('{{%author}}');
    }
}
