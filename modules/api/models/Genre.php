<?php

namespace app\modules\api\models;

use Yii;


class Genre extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'genre';
    }

    public function rules()
    {
        return [
            [['genre'], 'required'],
            [['genre'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'genre' => 'Genre',
        ];
    }

    /** Return list of all genres. Parameter $args = [NULL] */

    function GetGenreList(array $args, $check){
        if ($check == true) {
            return (count($args) == 0 ) ? true : false;
        }
        else {
            return self::find()->all();
        }
    }
}
