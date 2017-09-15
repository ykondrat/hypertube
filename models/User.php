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

        ];
    }

    public function rules()
    {
        return [

        ];
    }
}