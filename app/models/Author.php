<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 *
 * @property BookAuthor[] $bookAuthors
 */
class Author extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'second_name', 'last_name'], 'required'],
            [['first_name', 'second_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'second_name' => 'Second Name',
            'last_name' => 'Last Name',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    /**
     * Топ 10 авторов за год
     *
     * @param int $year
     * @return array|\yii\db\ActiveRecord[]|\yii\db\T[]
     */
    public static function getTop(int $year)
    {
        return Author::find()
            ->select([
                'name' => 'CONCAT(LEFT(author.first_name, 1), ". ", LEFT(author.second_name, 1), ". ", author.last_name)',
                'count_books' => 'count(book.id)',
            ])
            ->leftJoin('book_author', 'author.id = book_author.author_id')
            ->leftJoin('book', 'book_author.book_id = book.id')
            ->where(['book.year' => $year])
            ->groupBy('author.id')
            ->orderBy(['count_books' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all()
        ;
    }

}
