<?php

namespace app\models;

/**
 * This is the model class for table "categories".
 *
 * @property int $id ID
 * @property string $name Название категории
 *
 * @property Documents[] $documents
 * @property Tickets[] $tickets
 */
class Categories extends \yii\db\ActiveRecord{

    public $ticketsCount = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string{
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45, 'tooLong' => 'Максимальная длина названия категории - 45 символов(Ограничение для отображения кнопок в telegram).'],
            [['ticketsCount'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array{
        return [
            'id' => 'ID',
            'name' => 'Название категории',
            'ticketsCount' => 'Количество обращений'
        ];
    }

   /** 
    * Gets query for [[Documents]]. 
    * 
    * @return \yii\db\ActiveQuery|DocumentsQuery
    */ 
   public function getDocuments() : \yii\db\ActiveQuery|DocumentsQuery{ 
       return $this->hasMany(Documents::class, ['category_id' => 'id']); 
   } 
 
   /** 
    * Gets query for [[Tickets]]. 
    * 
    * @return \yii\db\ActiveQuery|TicketsQuery
    */ 
   public function getTickets() : \yii\db\ActiveQuery|TicketsQuery{ 
       return $this->hasMany(Tickets::class, ['category_id' => 'id']); 
   }

    /**
     * {@inheritdoc}
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find() : CategoriesQuery{
        return new CategoriesQuery(get_called_class());
    }
}