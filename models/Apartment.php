<?php

namespace app\models;

use Yii;
use app\models\Plan;
use app\models\Objects;
use app\models\Building;
use app\models\Client;

/**
 * This is the model class for table "apartment".
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $entry_num
 * @property integer $status
 * @property integer $number
 * @property integer $building_id
 */
class Apartment extends \yii\db\ActiveRecord
{
    public $room_amount;

    const STATUS_BOOKED = 1;
    const STATUS_RESERVED = 2;
    const STATUS_SOLD = 3;
    const STATUS_RETURN = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apartment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry_id', 'entry_num', 'status', 'number', 'building_id', 'manager', 'client', 'object_id', 'floor'], 'safe'],
            [['entry_id', 'entry_num', 'status', 'number', 'building_id', 'floor'], 'integer'],
            [['dollar_price','som_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entry_id' => Yii::t('app', 'Entry ID'),
            'entry_num' => Yii::t('app', 'Номер подъезда'),
            'status' => Yii::t('app', 'Статус'),
            'number' => Yii::t('app', 'Номер'),
            'building_id' => Yii::t('app', 'Здание'),
            'floor' => 'Этаж'
        ];
    }

    function getBuilding()
    {
        return $this->hasOne(Building::className(), ['id' => 'building_id']);
    }

    function getObject()
    {
        return $this->hasOne(Objects::className(), ['id' => 'object_id']);
    }

    function getPlan()
    {
        return $this->hasOne(Plan::className(), ['id' => 'plan_id']);
    }

    public function getBrand()
    {
        return $this->building->object->title;
    }

    public function getClientData()
    {
        return $this->hasOne(Client::className(), ['id' => 'client']);
    }

    public function getManagerData()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'manager']);
    }

    #region Methods
    /**
     * Get price
     * If apartment has no price return price of the plan
     * @param string $currency
     * @return mixed
     * @throws \InvalidArgumentException
     */
    function getPrice($currency = 'som')
    {
        if (!in_array($currency, ['dollar', 'som'])) {
            throw new \InvalidArgumentException('Only dollar or som currencies available!');
        }
        $field = "{$currency}_price";
        if (!(double)$this->{$field}) {
            $field = "base_{$currency}_price";
            return $this->building->object->{$field} * $this->plan->area;
        }
        return $this->{$field};
    }
    #endregion
}
