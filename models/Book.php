<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $text
 * @property string $date_from
 * @property string $date_to
 * @property string $prepayment
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text','prepayment','apartment_id'], 'safe'],
            [['text'], 'string'],
            [['date_from', 'date_to'], 'safe'],
            [['prepayment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Текст'),
            'date_from' => Yii::t('app', 'Дата начало брони'),
            'date_to' => Yii::t('app', 'Дата конца брони'),
            'prepayment' => Yii::t('app', 'Предоплата'),
        ];
    }

    public function getClient()
    {
        return $this->hasOne(Plan::className(), ['id' => 'book_id']);
    }
}
