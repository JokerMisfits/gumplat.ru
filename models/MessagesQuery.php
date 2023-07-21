<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Messages]].
 *
 * @see Messages
 */
class MessagesQuery extends ActiveQuery{

    /**
     * {@inheritdoc}
     * @return Messages[]|array
     */
    public function all($db = null){
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Messages|array|null
     */
    public function one($db = null){
        return parent::one($db);
    }
}