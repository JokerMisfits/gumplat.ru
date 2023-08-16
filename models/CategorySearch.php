<?php

namespace app\models;

/**
 * CategorySearch represents the model behind the search form of `app\models\Categories`.
 */
class CategorySearch extends Categories{

    public $ticketsCount = 0;

    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
            [['ticketsCount'], 'integer']
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
        $query = Categories::find()
        ->select(['categories.*', 'COUNT(tickets.id) AS ticketsCount'])
        ->leftJoin('tickets', 'categories.id = tickets.category_id')
        ->groupBy('categories.id');

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
                    'name' => SORT_ASC
                ]
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
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}