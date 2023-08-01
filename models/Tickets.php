<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id ID
 * @property int|null $tg_user_id ID пользователя в telegram
 * @property int $status Статус обращения
 * @property string|null $name Имя
 * @property string|null $surname Фамилия
 * @property string|null $phone Номер телефона
 * @property string|null $email Почта
 * @property string $title Заголовок обращения
 * @property string $text Текст обращения
 * @property string|null $answers Ответы на вопросы
 * @property string|null $comment Результаты рассмотрения
 * @property string $creation_date Дата создания обращения
 * @property string $last_change Дата последнего изменения
 * @property int|null $category_id ID категории
 * @property int|null $city_id ID города
 * @property int|null $user_id ID пользователя
 *
 * @property Categories $category 
 * @property Cities $city 
 * @property Users $user 
 */
class Tickets extends ActiveRecord{

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
            [['tg_user_id', 'status', 'category_id', 'city_id', 'user_id'], 'integer'],
            [['title', 'text'], 'required'],
            [['text', 'answers', 'comment'], 'string'],
            [['creation_date', 'last_change'], 'safe'],
            [['name', 'surname', 'phone', 'email', 'title'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']]
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
            'email' => 'Почта',
            'title' => 'Заголовок обращения',
            'text' => 'Текст обращения',
            'answers' => 'Ответы на вопросы',
            'comment' => 'Результаты рассмотрения',
            'creation_date' => 'Дата создания обращения', 
            'last_change' => 'Дата последнего изменения',
            'category_id' => 'Категория обращения',
            'city_id' => 'Город',
            'user_id' => 'Ответственный'
        ];
    }

   /** 
    * Gets query for [[Category]]. 
    * 
    * @return ActiveQuery|CategoriesQuery 
    */ 
   public function getCategory(){ 
       return $this->hasOne(Categories::class, ['id' => 'category_id']); 
   } 
 
   /** 
    * Gets query for [[City]]. 
    * 
    * @return ActiveQuery|CitiesQuery 
    */ 
   public function getCity(){ 
       return $this->hasOne(Cities::class, ['id' => 'city_id']); 
   } 
 
   /** 
    * Gets query for [[User]]. 
    * 
    * @return \yii\db\ActiveQuery|UsersQuery 
    */ 
   public function getUser(){ 
       return $this->hasOne(Users::class, ['id' => 'user_id']); 
   }

    /**
     * {@inheritdoc}
     * @return TicketsQuery the active query used by this AR class.
     */
    public static function find(){
        return new TicketsQuery(get_called_class());
    }
}