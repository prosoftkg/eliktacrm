<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Objects;

/**
 * ObjectSearch represents the model behind the search form about `app\models\Object`.
 */
class ObjectSearch extends Objects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'due_quarter', 'due_year', 'company_id'], 'integer'],
            [['title', 'logo', 'city', 'description'], 'safe'],
            [['base_dollar_price', 'base_som_price'], 'number'],
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
    public function search($params, $own = false)
    {
        $query = Objects::find();

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
            'base_dollar_price' => $this->base_dollar_price,
            'base_som_price' => $this->base_som_price,
            'due_quarter' => $this->due_quarter,
            'due_year' => $this->due_year,
            'company_id' => $this->company_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'description', $this->description]);

        if ($own) {
            $query->joinWith(['company'])
                ->andFilterWhere([
                    'or',
                    ['=', 'company.owner_id', Yii::$app->user->id],
                    ['=', 'company.id', Yii::$app->user->identity->company_id]
                ]);
        }

        return $dataProvider;
    }
}
