<?php

namespace app\models;

use app\models\Cities;
use yii\data\ActiveDataProvider;

/**
 * CitySearch represents the model behind the search form of `app\models\Cities`.
 */
class CitySearch extends Cities{
    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['id', 'territory'], 'integer'],
            [['name'], 'safe'],
            [['x', 'y'], 'number'],
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
        $query = Cities::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
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
            'x' => $this->x,
            'y' => $this->y,
            'territory' => $this->territory
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}