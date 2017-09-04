<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 03.08.17
 * Time: 10:55
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\validators\EmailValidator;

class User extends ActiveRecord
{

    public static function tableName(){
        return 'user';
    }

    public function attributeLabels()
    {
        return [
            'user_name' => 'Your name',
            'user_secondname' => 'Your surname',
            'user_email' => 'Your email',
            'user_login' => 'Login',
            'user_password' => 'Password',
            'user_rep_password' => 'Repeat password',
        ];
    }

    public function rules()
    {
        return [

          [ ['user_name', 'user_secondname', 'user_email', 'user_login', 'user_password', 'user_rep_password'], 'required'],
            [ ['user_name', 'user_secondname', 'user_email', 'user_login', 'user_password', 'user_rep_password'], 'trim'],
            [ 'user_email' , 'email'],
          [ ['user_name', 'user_secondname'] , 'string', 'length' => [2, 20]],
            [ ['user_city', 'user_country', 'user_latitude', 'user_longitude'] , 'string', 'length' => [0, 200]],
            [ ['user_password', 'user_rep_password'] , 'string', 'min' => 8],
            ['user_rep_password', 'compare', 'compareAttribute' => 'user_password'],
            ['user_login', 'string' , 'length' => [6 , 12] ],
        ];
    }
}