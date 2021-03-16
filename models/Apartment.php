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
            [['dollar_price', 'som_price'], 'number'],
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

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['client'], $fields['manager'], $fields['entry_id'], $fields['status'], $fields['number'], $fields['entry_num'], $fields['building_id'], $fields['plan_id'], $fields['object_id']);
        $fields['room_count'] = function ($model) {
            return $model->plan->room_count;
        };
        $fields['area'] = function ($model) {
            return $model->plan->area;
        };
        $fields['floors'] = function ($model) {
            return $model->building->stores_amount;
        };
        $fields['dollar_price'] = function ($model) {
            if ($model->dollar_price) {
                return $model->dollar_price;
            }
            return $model->object->base_dollar_price * $model->plan->area;
        };
        $fields['som_price'] = function ($model) {
            if ($model->som_price) {
                return $model->som_price;
            }
            return $model->object->base_som_price * $model->plan->area;
        };
        $fields['object_title'] = function ($model) {
            return $model->object->title;
        };
        $fields['base_dollar_price'] = function ($model) {
            return $model->object->base_dollar_price;
        };
        $fields['due'] = function ($model) {
            return $model->object->due_quarter . ' кв. ' . $model->object->due_year;
        };
        $fields['address'] = function ($model) {
            return $model->building->address;
        };
        $fields['company'] = function ($model) {
            return $model->object->company->name;
        };
        $fields['pay_months'] = function ($model) {
            $month2 = $model->object->due_quarter * 3;
            $diff = (($model->object->due_year - date('Y')) * 12) + ($month2 - date('m'));
            if ($diff < 0) {
                $diff = 0;
            }
            return $diff;
        };
        $fields['images'] = function ($model) {
            return [
                'images/object/' . $model->object->logo,
                'images/plan/' . $model->plan_id . '/' . $model->plan->img,
            ];
        };
        return $fields;
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
        if (!(float)$this->{$field}) {
            $field = "base_{$currency}_price";
            return $this->building->object->{$field} * $this->plan->area;
        }
        return $this->{$field};
    }
    #endregion
}
