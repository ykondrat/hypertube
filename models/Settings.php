<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 16.09.17
 * Time: 14:50
 */

namespace app\models;



use yii\db\ActiveRecord;
use yii\validators\EmailValidator;

class Settings extends ActiveRecord
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

            'user_password' => 'Password',
            'user_rep_password' => 'Repeat password',
        ];
    }

    public function rules()
    {
        return [
            [['user_avatar'], 'file', 'extensions'=>'jpg, gif, png','skipOnEmpty' => true ],
            [ ['user_name', 'user_secondname', 'user_email'], 'required'],
            [ ['user_name', 'user_secondname', 'user_email',  'user_password'], 'trim'],
            [ 'user_email' , 'email'],
            [ ['user_name', 'user_secondname'] , 'string', 'length' => [2, 20]],
            [ 'user_password' , 'string', 'min' => 8],

        ];
    }
}