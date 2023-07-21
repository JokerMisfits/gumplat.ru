<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id ID
 * @property string $name Название категории
 */
class Categories extends yii\db\ActiveRecord{

    /**
     * {@inheritdoc}
     */
    public static function tableName(){
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Название категории'
        ];
    }

    /**
     * {@inheritdoc}
     * @return CategoriesQuery the active query used by this AR class.
     */
    
    public static function find(){
        return new CategoriesQuery(get_called_class());
    }
}