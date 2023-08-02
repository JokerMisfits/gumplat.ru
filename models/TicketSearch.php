<?php

namespace app\models;

use app\models\Tickets;

/**
 * TicketSearch represents the model behind the search form of `app\models\Tickets`.
 */
class TicketSearch extends Tickets{
    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['id', 'tg_user_id', 'status', 'category_id', 'city_id', 'user_id'], 'integer'],
            [['name', 'surname', 'phone', 'email', 'title', 'text', 'comment', 'last_change'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() : array{
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function search(array $params) : \yii\data\ActiveDataProvider{
        $query = Tickets::find();

        // add conditions that should always apply here

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 13
            ]
        ]);

        $this->load($params);

        if(!$this->validate()){
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tg_user_id' => $this->tg_user_id,
            'status' => $this->status,
            'last_change' => $this->last_change,
            'category_id' => $this->category_id,
            'city_id' => $this->city_id, 
            'user_id' => $this->user_id
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}