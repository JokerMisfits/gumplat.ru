<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id ID
 * @property int|null $tg_user_id ID пользователя в telegram
 * @property int $status Статус обращения
 * @property string|null $name Имя
 * @property string|null $surname Фамилия
 * @property string|null $phone Номер телефона
 * @property string|null $answers Ответы на вопросы
 * @property string $title Заголовок обращения
 * @property string $text Текст обращения
 * @property string|null $comment Комментарий
 * @property string $last_change Дата последнего изменения
 * @property int|null $category_id ID категории
 */
class Tickets extends yii\db\ActiveRecord{

    /**
     * {@inheritdoc}
     */
    public static function tableName(){
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['tg_user_id', 'status', 'category_id'], 'integer'],
            [['answers', 'text', 'comment'], 'string'],
            [['title', 'text'], 'required'], 
            [['last_change'], 'safe'],
            [['name', 'surname', 'phone', 'title'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'tg_user_id' => 'ID пользователя в telegram',
            'status' => 'Статус обращения',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'phone' => 'Номер телефона',
            'answers' => 'Ответы на вопросы',
            'title' => 'Заголовок обращения',
            'text' => 'Текст обращения',
            'comment' => 'Комментарий',
            'last_change' => 'Дата последнего изменения',
            'category_id' => 'ID категории'
        ];
    }

    /**
     * {@inheritdoc}
     * @return TicketsQuery the active query used by this AR class.
     */
    public static function find(){
        return new TicketsQuery(get_called_class());
    }
}