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

class Login extends ActiveRecord
{

    public static function tableName(){
        return 'user';
    }

    public function attributeLabels()
    {
        return [
            'user_login' => 'Login',
            'user_password' => 'Password',
        ];
    }

    public function rules()
    {
        return [
            [ ['user_email', 'user_login', 'user_password'], 'required'],
            [ ['user_login', 'user_password'], 'trim'],
            [ 'user_password', 'string', 'min' => 8],
            ['user_login', 'string' , 'length' => [6 , 16] ],
            ['user_email', 'email'],
        ];
    }
     public static function passwordGenerate(){
         $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
         $pass = array(); //remember to declare $pass as an array
         $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
         for ($i = 0; $i < 8; $i++) {
             $n = rand(0, $alphaLength);
             $pass[] = $alphabet[$n];
         }
         return implode($pass); //turn the array into a string
     }
}