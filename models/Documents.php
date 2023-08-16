<?php

namespace app\models;

/**
 * This is the model class for table "documents".
 *
 * @property int $id ID
 * @property string $base_name Имя файла в системе 
 * @property string $name Название файла
 * @property string $path Путь до файла
 * @property string $extension Расширение файла
 * @property string $creation_date Дата добавления
 * @property int $category_id ID категории
 *
 * @property Categories $category
 */

class Documents extends \yii\db\ActiveRecord{

    public $file;

    public static string $extensions = 'jpg, jpeg, png, heic, pdf, doc, docx, txt, csv, xlsx, ppt';
    public static string $extensionsTg = 'jpg, jpeg, png, pdf';

    private static int $maxSize = 30;

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string{
        return 'documents';
    }

    public function scenarios() : array{
        $scenarios = parent::scenarios();
        $scenarios['upload'] = ['file', 'category_id'];
        $scenarios['update'] = ['category_id'];
        $scenarios['uploadTg'] = ['file'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array{
        return [
            [['base_name', 'name', 'path', 'extension', 'category_id'], 'required'],
            [['file'], 'required', 'on' => 'upload'],
            [['creation_date'], 'safe'],
            [['category_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['base_name', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['name'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['file'], 'file','skipOnEmpty' => false, 'extensions' => self::$extensions, 'maxSize' => self::$maxSize * 1024 * 1024, 'except' => 'uploadTg'],
            [['file'], 'file','skipOnEmpty' => false, 'extensions' => self::$extensionsTg, 'maxSize' => self::$maxSize * 1024 * 1024, 'on' => 'uploadTg']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array{
        return [
            'id' => 'ID',
            'base_name' => 'Название файла',
            'name' => 'Имя файла в системе',
            'path' => 'Путь до файла',
            'extension' => 'Расширение файла',
            'creation_date' => 'Дата добавления',
            'category_id' => 'Категория документа',
            'file' => 'Документ'
        ];
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
     * {@inheritdoc}
     * @return DocumentsQuery the active query used by this AR class.
     */
    public static function find() : DocumentsQuery{
        return new DocumentsQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     * @return string available extensions.
     */
    public function getExtensions() : string{
        return self::$extensions;
    }

    /**
     * {@inheritdoc}
     * @return bool uploaded?.
     */
    public function upload() : bool{
        if ($this->validate()) {
            $path = realpath(\Yii::getAlias('@web'));
            if($path !== false){
                $this->name = \Yii::$app->security->generateRandomString(64);
                $this->file->saveAs($path . '/uploads/' . $this->name . '.' . $this->file->extension, true);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
}