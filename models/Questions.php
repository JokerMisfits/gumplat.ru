<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "questions".
 *
 * @property int $id ID
 * @property string $question Вопрос
 */
class Questions extends yii\db\ActiveRecord{
    /**
     * {@inheritdoc}
     */
    public static function tableName(){
        return 'questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['question'], 'required'],
            [['question'], 'string', 'max' => 255],
            [['question'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'question' => 'Вопрос'
        ];
    }

    /**
     * {@inheritdoc}
     * @return QuestionsQuery the active query used by this AR class.
     */
    public static function find(){
        return new QuestionsQuery(get_called_class());
    }
}