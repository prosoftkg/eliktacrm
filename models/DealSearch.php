<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Deal;

/**
 * DealSearch represents the model behind the search form about `app\models\Deal`.
 */
class DealSearch extends Deal
{
    public $object_id;
    public $profile_id;
    public $channel;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'apartment_id', 'object_id', 'profile_id'], 'integer'],
            [['date_from', 'date_to', 'reference', 'text'], 'safe'],
            [['left_sum', 'discount','reference'], 'number'],
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
        $query = Deal::find();
        $query->joinWith(['object']);
        $query->joinWith(['profile']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['object'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['object.title' => SORT_ASC],
            'desc' => ['object.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['saleReference'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['saleReference.title' => SORT_ASC],
            'desc' => ['saleReference.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['profile'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['profile.name' => SORT_ASC],
            'desc' => ['profile.name' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'apartment_id' => $this->apartment_id,
            'left_sum' => $this->left_sum,
            'discount' => $this->discount,
            'object.id' => $this->object_id,
            'profile.user_id' => $this->profile_id,
            //'channel' => $this->saleReference->title,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);
//        $query
//            ->andFilterWhere(['like', 'apartment.building.object.title', $this->apartment]);

        return $dataProvider;
    }
}
