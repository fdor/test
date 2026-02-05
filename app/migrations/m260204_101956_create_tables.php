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

        $this->addForeignKey('fk_book_author_book', 'book_author', 'book_id', 'book', 'id');
        $this->addForeignKey('fk_book_author_author', 'book_author', 'author_id', 'author', 'id');

        $this->createTable('subscription', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(11)->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_subscription_author', 'subscription', 'author_id', 'author', 'id');

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(),
            'access_token' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscription');
        $this->dropTable('book_author');
        $this->dropTable('author');
        $this->dropTable('book');
        $this->dropTable('user');
    }
}
