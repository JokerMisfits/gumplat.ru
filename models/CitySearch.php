<?php

namespace app\models;

/**
 * CitySearch represents the model behind the search form of `app\models\Cities`.
 */
class CitySearch extends Cities{
    
    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['id', 'territory'], 'integer'],
            [['name'], 'safe'],
            [['x', 'y'], 'number']
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
        $query = Cities::find();
        // add conditions that should always apply here

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15
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