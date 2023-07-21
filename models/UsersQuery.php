<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Users]].
 *
 * @see Users
 */
class UsersQuery extends ActiveQuery{

    /**
     * {@inheritdoc}
     * @return Users[]|array
     */
    public function all($db = null){
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Users|array|null
     */
    public function one($db = null){
        return parent::one($db);
    }
}