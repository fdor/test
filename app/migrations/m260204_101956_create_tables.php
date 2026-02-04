<?php

use yii\db\Migration;

class m260204_101956_create_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique(),
            'description' => $this->string()->notNull(),
            'year' => $this->string(4)->notNull(),
            'isbn' => $this->string(13)->notNull(),
            'photo' => $this->string()->notNull(),
        ]);

        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'second_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
        ]);

        $this->createTable('book_author', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_book', 'book_author', 'book_id', 'book', 'id');
        $this->addForeignKey('fk_author', 'book_author', 'author_id', 'author', 'id');

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_author', 'book_author');
        $this->dropForeignKey('fk_book', 'book_author');
        $this->dropTable('book_author');
        $this->dropTable('author');
        $this->dropTable('book');
    }
}
