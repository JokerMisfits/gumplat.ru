<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Categories]].
 *
 * @see Categories
 */
class CategoriesQuery extends ActiveQuery{

    /**
     * {@inheritdoc}
     * @return Categories[]|array
     */
    public function all($db = null){
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Categories|array|null
     */
    public function one($db = null){
        return parent::one($db);
    }
}