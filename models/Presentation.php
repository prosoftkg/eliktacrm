<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presention".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $phone
 * @property string $email
 */
class Presentation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'presentation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'phone', 'email'], 'required'],
            [['email'], 'email'],
            [['fullname', 'phone', 'email'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fullname' => Yii::t('app', 'ФИО'),
            'phone' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'E-mail'),
        ];
    }
}
