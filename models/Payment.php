<?php

namespace app\models;

use Yii;
use app\models\Apartment;

/**
 * This is the model class for table "payment".
 *
 * @property integer $client_id
 * @property integer $apartment_id
 * @property double $sum
 * @property string $pay_date
 */
class Payment extends \yii\db\ActiveRecord
{
    const STATUS_PAID = 1;
    const STATUS_NOT_PAID = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'apartment_id', 'sum', 'pay_date', 'status'], 'safe'],
            [['client_id', 'apartment_id', 'status'], 'integer'],
            [['sum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_id' => Yii::t('app', 'Client ID'),
            'apartment_id' => Yii::t('app', 'Сделка'),
            'sum' => Yii::t('app', 'Сумма'),
            'pay_date' => Yii::t('app', 'Дата платежа'),
        ];
    }

    public function getApartment()
    {
        return $this->hasOne(Apartment::className(), ['id' => 'apartment_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getDeal()
    {
        return $this->hasOne(Deal::className(), ['apartment_id' => 'apartment_id']);
    }
}
