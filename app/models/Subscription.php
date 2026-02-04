<?php

namespace app\models;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property string $phone
 * @property int $author_id
 *
 * @property Author $author
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'author_id'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'unique'],
            [['phone'], 'phoneValidator'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон',
            'author_id' => 'Автор',
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function phoneValidator($attribute, $params)
    {
        if (strlen($this->$attribute) !== 11) {
            $this->addError($attribute, 'Не верный формат телефона');
        }
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->phone = preg_replace('/[^0-9]/', '', $this->phone);

        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
