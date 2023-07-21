<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Questions]].
 *
 * @see Questions
 */
class QuestionsQuery extends ActiveQuery{

    /**
     * {@inheritdoc}
     * @return Questions[]|array
     */
    public function all($db = null){
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Questions|array|null
     */
    public function one($db = null){
        return parent::one($db);
    }
}