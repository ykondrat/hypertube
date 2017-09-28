<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 28.09.17
 * Time: 16:25
 */

namespace app\models;


use yii\db\ActiveRecord;


class Comment extends ActiveRecord
{

    public static function tableName(){
        return 'comment';
    }

    public function attributeLabels()
    {
        return [

        ];
    }

    public function rules()
    {
        return [
            [ ['text'], 'trim', 'required'],
            [ 'text', 'string', 'max' => 1000],
        ];
    }

}