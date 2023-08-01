<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property int $id ID
 * @property string $name Название города
 * @property float $x Долгота
 * @property float $y Широта
 * @property int $territory Новая территория? 
 */
class Cities extends yii\db\ActiveRecord{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName(){
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['name', 'x', 'y'], 'required'],
            [['x', 'y'], 'number'],
            [['territory'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Название города',
            'x' => 'Долгота',
            'y' => 'Широта',
            'territory' => 'Новая территория?'
        ];
    }

    /**
     * {@inheritdoc}
     * @return CitiesQuery the active query used by this AR class.
     */
    public static function find(){
        return new CitiesQuery(get_called_class());
    }
}