<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Apartment;

/**
 * ApartmentSearch represents the model behind the search form about `app\models\Apartment`.
 */
class ApartmentSearch extends Apartment
{
    public $object_id;
    public $plan_id;
    public $area;
    public $dollar_price;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plan_id', 'dollar_price', 'area'], 'safe'],
            [['dollar_price', 'area'], 'number'],
            [['id', 'entry_id', 'entry_num', 'status', 'floor', 'number', 'object_id', 'plan_id', 'building_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Apartment::find();
        $query->joinWith('object');
        $query->joinWith(['plan']);

        if (!empty($param['price_min'])) {
            $price_min = (int)$param['price_min'];
            $query->joinWith(['object o'], true, 'INNER JOIN')
                ->andFilterWhere(['o.company_id' => Yii::$app->user->identity->company_id])
                ->andFilterWhere(['>=', '(object.base_dollar_price * plan.area)', $price_min]);
        }
        if (!empty($param['price_max'])) {
            $price_max = (int)$param['price_max'];
            $query->joinWith(['object o'], true, 'INNER JOIN')
                ->andFilterWhere(['<=', '(object.base_dollar_price * plan.area)', $price_max]);
            //$rowData = $price_min . ',';// . $price_max;
        }

        if (!empty($param['area_min'])) {
            $area_min = (int)$param['area_min'];
            $query->andFilterWhere(['>=', 'plan.area', $area_min]);
        }

        if (!empty($param['area_max'])) {
            $area_max = (int)$param['area_max'];
            $query->andFilterWhere(['<=', 'plan.area', $area_max]);
        }

        if (!empty($param['floor'])) {
            $floor = explode(",", $param['floor']);
            $floor_min = (int)$floor[0];
            $floor_max = (int)$floor[1];
            $query->andFilterWhere(['<=', 'floor', $floor_max])
                ->andFilterWhere(['>=', 'floor', $floor_min]);
        }

        if (!empty($param['rooms'])) {
            $room = explode(",", $param['rooms']);
            $room_min = (int)$room[0];
            $room_max = (int)$room[1];
            $query->andFilterWhere(['<=', 'plan.room_count', $room_max])
                ->andFilterWhere(['>=', 'plan.room_count', $room_min]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes = [
            'title' => [
                'asc' => ['object.title' => SORT_ASC],
                'desc' => ['object.title' => SORT_DESC],
            ],
            'floor' => [
                'asc' => ['floor' => SORT_ASC],
                'desc' => ['floor' => SORT_DESC],
            ],
            'plan_id' => [
                'asc' => ['plan.room_count' => SORT_ASC],
                'desc' => ['plan.room_count' => SORT_DESC],
            ],
            'area' => [
                'asc' => ['plan.area' => SORT_ASC],
                'desc' => ['plan.area' => SORT_DESC],
            ],
            'dollar_price' => [
                'asc' => ['object.base_dollar_price' => SORT_ASC],
                'desc' => ['object.base_dollar_price' => SORT_DESC],
            ]
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //$query->andFilterWhere(['like', 'apartment.room_count', $this->room_count]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'entry_id' => $this->entry_id,
            'entry_num' => $this->entry_num,
            'status' => $this->status,
            'number' => $this->number,
            'building_id' => $this->building_id,
            'object.id' => $this->object_id,
            'plan.room_count' => $this->plan_id,
            'floor' => $this->floor,
            'plan.area' => $this->area,
            //'object.base_dollar_price' => $this->getPrice('dollar'),
        ]);
        $query->andFilterWhere(['=', '(object.base_dollar_price * plan.area)', $this->dollar_price]);
        $query->andFilterWhere(['!=', 'plan_id', 0]);


        return $dataProvider;
    }
}
