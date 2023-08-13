<?php

namespace app\models;

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
 * @property string|null $comment Результаты рассмотрения
 * @property string|array $messages Сообщения
 * @property string $creation_date Дата создания обращения
 * @property string $last_change Дата последнего изменения
 * @property int|null $category_id ID категории
 * @property int|null $city_id ID города
 * @property int|null $user_id ID пользователя
 * @property int $limit limit сообщений 
 * @property int $is_new Есть ли новое сообщение? 
 * @property string|null $tg_username Username пользователя в telegram 
 *
 * @property Categories $category
 * @property Cities $city 
 * @property Users $user 
 */
class Tickets extends \yii\db\ActiveRecord{

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string{
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['tg_user_id', 'status', 'category_id', 'city_id', 'user_id', 'limit', 'is_new'], 'integer'],
            [['title', 'text'], 'required'],
            [['text', 'comment'], 'string'],
            [['creation_date', 'last_change', 'messages'], 'safe'],
            [['name', 'surname'], 'string', 'min' => 1],
            [['phone'], 'string', 'min' => 3],
            [['email'], 'string', 'min' => 5],
            [['comment'], 'string', 'min' => 4],
            [['name', 'surname', 'phone', 'email', 'title', 'tg_username'], 'string', 'max' => 255],
            [['name', 'surname', 'phone', 'email', 'title', 'text', 'comment', 'messages'], 'trim'],
            [['name', 'surname', 'phone', 'email', 'comment'], 'default'],
            ['status', 'default', 'value' => 0],
            ['user_id', 'default', 'value' => \Yii::$app->params['systemUserId']],
            ['phone', 'match', 'pattern' => '/^((\+7|7|8)+([0-9]){10})$/', 'message' => 'Недействительный номер'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array{
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
            'comment' => 'Результаты рассмотрения',
            'messages' => 'Сообщения',
            'creation_date' => 'Дата создания обращения',
            'last_change' => 'Дата последнего изменения',
            'category_id' => 'Категория обращения',
            'city_id' => 'Город',
            'user_id' => 'Ответственный',
            'limit' => 'Лимит сообщений от пользователя',
            'is_new' => 'Есть ли новое сообщение?',
            'tg_username' => 'Имя пользователя в telegram'
        ];
    }

    public function beforeSave($insert) : bool{
        if(parent::beforeSave($insert)){
            if($this->messages === '' || $this->messages === null){
                $this->messages = [];
            }
            return true;
        }
        return false;
    }

   /** 
    * Gets query for [[Category]]. 
    * 
    * @return \yii\db\ActiveQuery|CategoriesQuery
    */ 
   public function getCategory() : \yii\db\ActiveQuery|CategoriesQuery{ 
       return $this->hasOne(Categories::class, ['id' => 'category_id']); 
   } 
 
   /** 
    * Gets query for [[City]]. 
    * 
    * @return \yii\db\ActiveQuery|CitiesQuery
    */ 
   public function getCity() : \yii\db\ActiveQuery|CitiesQuery{ 
       return $this->hasOne(Cities::class, ['id' => 'city_id']); 
   } 
 
   /** 
    * Gets query for [[User]]. 
    * 
    * @return \yii\db\ActiveQuery|UsersQuery
    */ 
   public function getUser() : \yii\db\ActiveQuery|UsersQuery{ 
       return $this->hasOne(Users::class, ['id' => 'user_id']); 
   }

    /**
     * {@inheritdoc}
     * @return TicketsQuery the active query used by this AR class.
     */
    public static function find() : TicketsQuery{
        return new TicketsQuery(get_called_class());
    }
}