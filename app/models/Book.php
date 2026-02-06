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

    public function beforeDelete()
    {
        BookAuthor::deleteAll(['book_id' => $this->id]);

        if ($this->photo) {
            unlink('uploads/' . $this->photo);
        }

        return parent::beforeDelete();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        BookAuthor::deleteAll(['book_id' => $this->id]);

        foreach ($this->authorsFromForm as $authorId) {
            $bookAuthor = new BookAuthor();
            $bookAuthor->book_id = $this->id;
            $bookAuthor->author_id = $authorId;
            $bookAuthor->save();
        }

        $this->uploadPhoto();

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $this->authorsFromForm = $this->authors;
        parent::afterFind();
    }

    /**
     * Загрузка фото
     *
     * @throws \yii\db\Exception
     */
    public function uploadPhoto()
    {
        $photo = UploadedFile::getInstance($this, 'photo');
        if ($photo) {
            $fileName = $this->id . '.' . $photo->extension;
            $photo->saveAs('uploads/' . $fileName);
            Book::updateAll(['photo' => $fileName], ['id' => $this->id]);
        }
    }

    /**
     * Авторы
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    /**
     * Кнопки
     *
     * @return string
     */
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
