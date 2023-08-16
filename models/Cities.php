<?php

namespace app\models;

/**
 * This is the model class for table "cities".
 *
 * @property int $id ID
 * @property string $name Название города
 * @property float $x Долгота
 * @property float $y Широта
 * @property int $territory Новая территория? 
 */
class Cities extends \yii\db\ActiveRecord{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string{
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['name', 'x', 'y'], 'required'],
            [['x', 'y'], 'number'],
            [['territory'], 'integer'],
            [['name'], 'string', 'max' => 45, 'tooLong' => 'Максимальная длина названия Н. П. - 45 символов(Ограничение для отображения кнопок в telegram).'],
            [['name'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array{
        return [
            'id' => 'ID',
            'name' => 'Название Н. П.',
            'x' => 'Долгота',
            'y' => 'Широта',
            'territory' => 'Новая территория?'
        ];
    }

    /**
     * {@inheritdoc}
     * @return CitiesQuery the active query used by this AR class.
     */
    public static function find() : CitiesQuery{
        return new CitiesQuery(get_called_class());
    }
}