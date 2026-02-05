<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $year
 * @property string $isbn
 * @property string $photo
 *
 * @property BookAuthor[] $bookAuthors
 */
class Book extends \yii\db\ActiveRecord
{
    public $authorsFromForm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'year', 'isbn', 'photo', 'authorsFromForm'], 'required'],
            [['title', 'description', 'photo'], 'string', 'max' => 255],
            [['year'], 'string', 'max' => 4],
            [['isbn'], 'string', 'max' => 13],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'year' => 'Год',
            'isbn' => 'ISBN',
            'photo' => 'Фото',
            'authorsFromForm' => 'Авторы',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->authorsFromForm as $authorId) {
            $bookAuthor = new BookAuthor();
            $bookAuthor->book_id = $this->id;
            $bookAuthor->author_id = $authorId;
            $bookAuthor->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'book_id'])
            ->viaTable('book_author', ['author_id' => 'id']);
    }

    public static function getButtons()
    {
        $buttons = '{view} ';
        if (Yii::$app->user->can('updateBook')) {
            $buttons .= '{update} ';
        }
        if (Yii::$app->user->can('deleteBook')) {
            $buttons .= '{delete} ';
        }

        return $buttons;
    }
}
