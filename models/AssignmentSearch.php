<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Assignment;

/**
 * AssignmentSearch represents the model behind the search form about `app\models\Assignment`.
 */
class AssignmentSearch extends Assignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'priority', 'status'], 'integer'],
            [['date_from', 'date_to', 'description'], 'safe'],
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
        $query = Assignment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'priority' => $this->priority,
            'status' => $this->status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'user_id'=>Yii::$app->user->id,
        ]);

        //$query->andFilterWhere(['status'=>1]);
        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
