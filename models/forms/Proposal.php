<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Apartment;

/**
 * Class PriceCorrector
 */
class Proposal extends Model
{
    public $period;
    public $base_price;
    public $prepay;
    public $apartment;
    public $floor;

    function rules()
    {
        return [
            [['apartment','prepay','period','base_price','floor'], 'required'],
            ['apartment', 'each', 'rule' => ['integer']],
            ['apartment', 'each', 'rule' => ['exist', 'targetClass' => Apartment::className(), 'targetAttribute' => 'id']],
            [['prepay','period','base_price'], 'number'],
            [['floor'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'period'=>'Срок',
            'base_price'=>'Цена',
            'prepay'=>'Первоначальный взнос'
        ];
    }

    /**
     * Set new prices to given apartments
     * @return bool
     */

}