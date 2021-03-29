<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Apartment;

/**
 * Class PriceCorrector
 */
class PriceCorrector extends Model
{
    public $usd; //per sq. m.
    public $kgs; //per sq. m
    public $apartments = [];

    function rules()
    {
        return [
            ['apartments', 'required'],
            ['apartments', 'each', 'rule' => ['integer']],
            ['apartments', 'each', 'rule' => ['exist', 'targetClass' => Apartment::className(), 'targetAttribute' => 'id']],

            ['usd', 'number'],
            ['usd', 'required', 'when' => function ($model) {
                return !$model->kgs;
            }],
            ['kgs', 'number'],
            ['kgs', 'required', 'when' => function ($model) {
                return !$model->usd;
            }],
        ];
    }

    /**
     * Set new prices to given apartments
     * @return bool
     */
    function setNewPrices()
    {
        if (!$this->validate()) {
            return false;
        }
        $result = true;
        foreach ($this->apartments as $apartment_id) {
            $mdlApartment = Apartment::findOne($apartment_id);
            //$mdlApartment->dollar_price = ((float)$mdlApartment->dollar_price) ? $mdlApartment->dollar_price + ($this->usd) : $mdlApartment->getPrice('dollar') + $this->usd;
            //$mdlApartment->som_price = ((float)$mdlApartment->som_price) ? $mdlApartment->som_price + ($this->kgs) : $mdlApartment->getPrice('som') + $this->kgs;
            if ($this->usd) {
                $mdlApartment->dollar_price = $mdlApartment->plan->area * $this->usd;
                $mdlApartment->base_dollar_price_custom =  $this->usd;
            }
            if ($this->kgs) {
                $mdlApartment->som_price = $mdlApartment->plan->area * $this->kgs;
                $mdlApartment->base_som_price_custom =  $this->kgs;
            }
            $result = $result && $mdlApartment->save();
        }
        return $result;
    }

    public function attributeLabels()
    {
        return [
            'kgs' => 'Цена за м² в сомах',
            'usd' => 'Цена за м² в долларах',
        ];
    }
}
