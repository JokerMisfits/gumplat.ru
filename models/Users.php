<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id ID
 * @property string $username Имя пользователя
 * @property string $password Пароль
 * @property string $password_repeat Пароль
 * @property string $snm ФИО сотрудника
 * @property string|null $auth_key Кука
 * @property string|null $access_token Код авторизации
 * @property int|null $tg_user_id ID пользователя в telegram
 * @property string $registration_date Дата регистрации
 * @property string $last_activity Дата последней активности
 */
class Users extends yii\db\ActiveRecord implements yii\web\IdentityInterface{
    public string $password_repeat = '';
    public bool $rememberMe = true;
    private static false|object $_user = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName(){
        return 'users';
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios['signup'] = ['username', 'password', 'password_repeat', 'snm'];
        $scenarios['login'] = ['username', 'password', 'rememberMe'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['snm'], 'required'],
            [['username', 'password', 'password_repeat'], 'required', 'on' => 'signup'],
            [['snm'], 'string', 'min' => 4],
            [['snm'], 'string', 'max' => 255],
            [['tg_user_id'], 'integer'],
            [['last_activity', 'registration_date'], 'safe'],
            [['rememberMe'], 'boolean'],
            [['rememberMe'], 'required', 'on' => 'login'],
            [['username'], 'string', 'min' => 4],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            [['username'], 'string', 'max' => 32],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 64],
            [['password_repeat'], 'string', 'max' => 64],
            [['username'], 'unique', 'except' => 'login'],
            [['username', 'password', 'snm'], 'trim'],
            [['password'], 'validateModelPassword', 'on' => 'login'],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i','message' => '{attribute} должно начинаться и содержать символы только латинского алфавита']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'password_repeat' => 'Подтвердить пароль',
            'rememberMe' => 'Запомнить меня',
            'snm' => 'ФИО сотрудника',
            'auth_key' => 'Кука',
            'access_token' => 'Код авторизации', 
            'tg_user_id' => 'ID пользователя в telegram', 
            'registration_date' => 'Дата регистрации', 
            'last_activity' => 'Дата последней активности'
        ];
    }

    /**
     * {@inheritdoc}
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find() : UsersQuery{
        return new UsersQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */

    public static function findIdentity($id) : yii\web\IdentityInterface|null{
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */

    public static function findIdentityByAccessToken($token, $type = null) : false{
        return false;
        //return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */

    private static function findByUsername($username) : static|null{
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId() : int|string{
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() : string|null{
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($auth_key) : bool{
        if($this->auth_key === $auth_key){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateModelPassword(string $attribute){
        if(!$this->hasErrors()){
            self::getUser();
            if(!self::$_user || !self::validatePassword($this->password)){
                $this->addError($attribute, 'Неправильное имя пользователя или пароль.');
            }
            else{
                if($this->rememberMe){
                    self::generateAuthKey();
                }
                else{
                    self::$_user->auth_key = null;
                    self::$_user->save();
                }
            }
        }
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    private function validatePassword($password) : bool{
        return Yii::$app->security->validatePassword($password, self::$_user->password);
    }

    private static function generateAuthKey() : void{
        self::$_user->updateAttributes(['auth_key' => Yii::$app->security->generateRandomString(64)]);
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login(){
        if($this->validate()){
            self::getUser();
            if(self::$_user !== null){
                return Yii::$app->user->login(self::$_user, $this->rememberMe ? 3600*24*30 : 0);
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     */
    private function getUser() : void{
        if(self::$_user === false){
            self::$_user = self::findByUsername($this->username);
            if(self::$_user === null){
                self::$_user = false;
            }
        }
    }
}