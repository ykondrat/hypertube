<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 13.09.17
 * Time: 17:18
 */

namespace app\models;

use yii\db\ActiveRecord;
use yii\validators\EmailValidator;

class Forgot extends ActiveRecord
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
            [ ['user_login', 'user_password'], 'required'],
            [ ['user_login', 'user_password'], 'trim'],
            [ 'user_password', 'string', 'min' => 8],
            ['user_login', 'string' , 'length' => [6 , 16] ],
        ];
    }

}