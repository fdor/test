<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

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
            [['title', 'description', 'year', 'isbn', 'authorsFromForm'], 'required'],
            [['title', 'description'], 'string', 'max' => 255],
            [['year'], 'string', 'max' => 4],
            [['isbn'], 'string', 'max' => 13],
            [['title', 'isbn'], 'unique'],
            [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
        BookAuthor::deleteAll(['book_id' => $this->id]);

        foreach ($this->authorsFromForm as $authorId) {
            $bookAuthor = new BookAuthor();
            $bookAuthor->book_id = $this->id;
            $bookAuthor->author_id = $authorId;
            $bookAuthor->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function upload()
    {
        $this->photo = UploadedFile::getInstance($this, 'photo');
        if ($this->photo) {
            $fileName = $this->id . '.' . $this->photo->extension;
            $this->photo->saveAs('uploads/' . $fileName);
            $this->photo = $fileName;
            $this->save();
        }
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
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
