<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sold".
 *
 * @property integer $id
 * @property string $date_to
 * @property string $date_from
 * @property double $discount
 * @property string $reference
 */
class Sold extends \yii\db\ActiveRecord
{
    const REFERENCE_INTERNET = "Интернет";
    const REFERENCE_TV = "Телевидение";
    const REFERENCE_RADIO = "Радио";
    const REFERENCE_RELATIVES = "Знакомые";
    const REFERENCE_OTHER = "Другое";
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['primary_sum', 'left_sum', 'discount', 'reference','apartment_id'], 'safe'],
            [['discount','left_sum','primary_sum'], 'number'],
            [['reference'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'primary_sum' => Yii::t('app', 'Первоначальный взнос'),
            'left_sum' => Yii::t('app', 'Остаток'),
            'discount' => Yii::t('app', 'Скидка'),
            'reference' => Yii::t('app', 'Канал продаж'),
        ];
    }
}
