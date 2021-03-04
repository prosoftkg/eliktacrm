<?php

namespace app\models;

use app\models\Company;
use app\models\Client;
use app\models\Apartment;
use app\models\Objects;
use app\models\Reference;
use app\models\User;
use app\models\Profile;
use Yii;

/**
 * This is the model class for table "deal".
 *
 * @property integer $id
 * @property string $date_from
 * @property string $date_to
 * @property integer $apartment_id
 * @property double $left_sum
 * @property double $discount
 * @property string $reference
 * @property string $text
 */
class Deal extends \yii\db\ActiveRecord
{
    const STATUS_DEAL_BOOKED = 1;
    const STATUS_DEAL_SOLD = 2;

    public $dataType;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apartment_id'], 'safe'],
            [['date_from', 'client_id', 'deal_date', 'date_to', 'left_sum', 'discount', 'reference', 'text', 'status', 'manager', 'object_id', 'company_id', 'prepay'], 'safe'],
            [['apartment_id', 'client_id'], 'integer'],
            [['left_sum', 'discount', 'prepay','reference'], 'number'],
            [['text'], 'string'],
        ];
    }

    public function statusLabel($status)
    {
        if($status == 1)
        {
            return "Забронирован";
        }
        elseif($status == 2)
        {
            return "Продан";
        }
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getSaleReference()
    {
        return $this->hasOne(Reference::className(), ['id' => 'reference']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getApartment()
    {
        return $this->hasOne(Apartment::className(), ['id' => 'apartment_id']);
    }

    public function getDeal()
    {
        return $this->apartment->building->object->title . ', ' . $this->apartment->building->title . ', кв. №' . $this->apartment->number;
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'manager']);
    }

    public function getObject()
    {
        return $this->hasOne(Objects::className(), ['id' => 'object_id']);
    }

    public function days()
    {
        $now = time(); // or your date as well
        $your_date = strtotime($this->date_to);
        $datediff = $your_date - $now;

        return floor($datediff / (60 * 60 * 24));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date_from' => Yii::t('app', 'Date From'),
            'date_to' => Yii::t('app', 'Date To'),
            'apartment_id' => Yii::t('app', 'Apartment ID'),
            'left_sum' => Yii::t('app', 'Остаток'),
            'discount' => Yii::t('app', 'Скидка'),
            'reference' => Yii::t('app', 'Канал продаж'),
            'text' => Yii::t('app', 'Описание'),
            'status' => Yii::t('app', 'Статус сделки'),
            'deal_date' => Yii::t('app', 'Дата сделки'),
            'prepay' => Yii::t('app', 'Предоплата'),
            'deal_date'=>'Дата сделки',
            'manager'=>'Менеджер',
        ];
    }
}
