<?php

namespace app\models;

/**
 * DocumentSearch represents the model behind the search form of `app\models\Documents`.
 */
class DocumentSearch extends Documents{
    
    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['id', 'category_id'], 'integer'],
            [['base_name', 'name', 'path', 'extension', 'creation_date'], 'safe'],
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
        $query = Documents::find();
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
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'creation_date' => $this->creation_date,
            'category_id' => $this->category_id
        ]);

        $query->andFilterWhere(['like', 'base_name', $this->base_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'extension', $this->extension]);

        return $dataProvider;
    }
}