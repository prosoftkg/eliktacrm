<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property string $title
 * @property double $discount
 * @property string $barter
 * @property string $period
 * @property string $other
 */
class Request extends \yii\db\ActiveRecord
{
    const TYPE_BARTER = 1;
    const TYPE_DELAY  = 2;
    const TYPE_OTHER = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'period','type'], 'required'],
            [['description','reference'], 'safe'],
            [['discount'], 'number'],
            [['period'], 'safe'],
            [['title', 'barter', 'other'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Тип запроса'),
            'title'=> Yii::t('app', 'Заголовок'),
            'discount' => Yii::t('app', 'Скидка'),
            'barter' => Yii::t('app', 'Бартер'),
            'period' => Yii::t('app', 'Период'),
            'reference' => Yii::t('app', 'Канал продаж'),
            'description' => Yii::t('app', 'Описание'),
        ];
    }
}
