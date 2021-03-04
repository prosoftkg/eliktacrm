<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property integer $plan_id
 * @property string $title
 * @property double $area
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plan_id', 'title', 'area'], 'required'],
            [['plan_id'], 'integer'],
            [['area'], 'number'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'plan_id' => Yii::t('app', 'Plan ID'),
            'title' => Yii::t('app', 'Title'),
            'area' => Yii::t('app', 'Area'),
        ];
    }
}
