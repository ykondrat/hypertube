<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 21.09.17
 * Time: 10:35
 */




namespace app\models;

use yii\db\ActiveRecord;

class Genre extends ActiveRecord
{

    public static function tableName(){
        return 'genre';
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