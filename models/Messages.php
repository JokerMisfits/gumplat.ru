<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id ID
 * @property string $message Сообщение
 * @property int $tg_member_id ID tg_member
 * @property int $user_id ID пользователя
 */
class Messages extends yii\db\ActiveRecord{
    /**
     * {@inheritdoc}
     */
    public static function tableName(){
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['message', 'tg_member_id', 'user_id'], 'required'],
            [['message'], 'string'],
            [['tg_member_id', 'user_id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'message' => 'Сообщение',
            'tg_member_id' => 'ID tg_member',
            'user_id' => 'ID пользователя'
        ];
    }

    /**
     * {@inheritdoc}
     * @return MessagesQuery the active query used by this AR class.
     */
    public static function find(){
        return new MessagesQuery(get_called_class());
    }
}