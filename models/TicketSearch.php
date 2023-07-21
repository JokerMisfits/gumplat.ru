<?php

namespace app\models;

use app\models\Tickets;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form of `app\models\Tickets`.
 */
class TicketSearch extends Tickets{
    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['id', 'tg_user_id', 'status', 'category_id'], 'integer'],
            [['name', 'surname', 'phone', 'answers', 'title', 'text', 'comment', 'last_change'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params){
        $query = Tickets::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'answers', $this->answers])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}