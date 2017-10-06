<?php

namespace app\modules\api\models;

use Yii;


class Subtitle extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'subtitle';
    }

    public function rules()
    {
        return [
            [['imdb_id', 'language', 'path_url'], 'string', 'max' => 100],
            [['path_folder'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' => 'Number',
            'imdb_id' => 'Imdb ID',
            'language' => 'Language',
            'path_url' => 'Path Url',
            'path_folder' => 'Path Folder',
        ];
    }

    /** Return list of all subtitles ('imdbID', 'Tlanguage' ,'url path' ).
     * Parameter $args = [NULL] */

    function GetSubtitleList(array $args, $check){
        if ($check == true) {
            return (count($args) == 0 ) ? true : false;
        }
        else {
            return self::find()->select('imdb_id, language, path_url')->all();
        }
    }

    /** Return subtitles  by `ImdbID`. Parameter $args = [<ImdbID's>] */

    function GetSubtitleByImdbId(array $args, $check){
        if ($check == true) {
            $str = 0;
            foreach ($args as $id){
                $int = (is_string($id) && strlen($id) == 9 && preg_match('/^tt[0-9]{7}/', $id)) ? $str + 1 : $str;
            }
            return (count($args) > 0 && $str == count($args)) ? true : false;
        }
        else {
            return self::find()->where(['imdb_id' => $args])->all();
        }
    }
}
