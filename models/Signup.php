<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 13.09.17
 * Time: 16:32
 */

namespace app\models;



use yii\db\ActiveRecord;
use yii\validators\EmailValidator;

class Signup extends ActiveRecord
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
            [ ['user_password', 'user_rep_password'] , 'string', 'min' => 8],
            ['user_rep_password', 'compare', 'compareAttribute' => 'user_password'],
            ['user_login', 'string' , 'length' => [6 , 12] ],
        ];
    }
}