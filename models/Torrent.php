<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 26.09.17
 * Time: 11:56
 */

namespace app\models;

use yii\db\ActiveRecord;

class Torrent extends ActiveRecord
{

    public static function tableName(){
        return 'torrent_link';
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