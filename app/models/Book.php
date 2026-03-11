<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use app\services\PhotoUploadService;

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
        $allowedExtensions = implode(',', Yii::$app->params['allowedPhotoExtensions'] ?? ['jpg', 'jpeg', 'png']);
        
        return [
            [['title', 'description', 'year', 'isbn', 'authorsFromForm'], 'required'],
            [['title', 'description'], 'string', 'max' => 255],
            [['year'], 'string', 'max' => 4],
            [['isbn'], 'string', 'max' => 13],
            [['title', 'isbn'], 'unique'],
            [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => $allowedExtensions],
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

        $photoService = Yii::createObject(PhotoUploadService::class);
        $photoService->deleteBookPhoto($this->photo);

        return parent::beforeDelete();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            BookAuthor::deleteAll(['book_id' => $this->id]);

            foreach ($this->authorsFromForm as $authorId) {
                $bookAuthor = new BookAuthor();
                $bookAuthor->book_id = $this->id;
                $bookAuthor->author_id = $authorId;
                $bookAuthor->save();
            }

            $photoService = Yii::createObject(PhotoUploadService::class);
            $photoFileName = $photoService->uploadBookPhoto($this->id, UploadedFile::getInstance($this, 'photo'));
            
            if ($photoFileName) {
                Book::updateAll(['photo' => $photoFileName], ['id' => $this->id]);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('Error saving book: ' . $e->getMessage());
            throw $e;
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $this->authorsFromForm = $this->authors;
        parent::afterFind();
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
