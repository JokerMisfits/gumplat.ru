<?php

namespace app\models;

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
            [['snm', 'phone', 'email', 'text', 'comment', 'messages', 'creation_date', 'last_change'], 'safe'],
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
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15
            ],
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'creation_date' => SORT_DESC
                ]
            ],
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

        $query->andFilterWhere(['like', 'snm', $this->snm])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'creation_date', $this->creation_date])
            ->andFilterWhere(['like', 'messages', $this->messages]);

        return $dataProvider;
    }
}