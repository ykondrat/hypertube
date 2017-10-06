<?php

namespace app\modules\api\models;

use Yii;

class Torrentlink extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'torrent_link';
    }

    public function rules()
    {
        return [
            [['number', 'imdbID', 'url', 'quality', 'seeds', 'peers', 'size', 'date_uploaded'], 'required'],
            [['number'], 'integer'],
            [['imdbID'], 'string', 'max' => 15],
            [['url'], 'string', 'max' => 600],
            [['hash', 'date_uploaded', 'date_uploaded_unix'], 'string', 'max' => 100],
            [['quality', 'size', 'size_bytes'], 'string', 'max' => 20],
            [['seeds', 'peers'], 'string', 'max' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' => 'Number',
            'imdbID' => 'Imdb ID',
            'url' => 'Url',
            'hash' => 'Hash',
            'quality' => 'Quality',
            'seeds' => 'Seeds',
            'peers' => 'Peers',
            'size' => 'Size',
            'size_bytes' => 'Size Bytes',
            'date_uploaded' => 'Date Uploaded',
            'date_uploaded_unix' => 'Date Uploaded Unix',
        ];
    }

    /** Return list of all torrent's ('imdbID', 'url' ,'hash', 'qualiti', 'seeds', 'peers', 'size' ).
     * Parameter $args = [NULL] */

    function GetTorrentList(array $args, $check){
        if ($check == true) {
            return (count($args) == 0 ) ? true : false;
        }
        else {
            return self::find()->select('imdbID, url ,hash, qualiti, seeds, peers, size')->all();
        }
    }

    /** Return torrent's  by `ImdbID`. Parameter $args = [<ImdbID's>] */

    function GetTorrentByImdbId(array $args, $check){
        if ($check == true) {
            $str = 0;
            foreach ($args as $id){
                $int = (is_string($id) && strlen($id) == 9 && preg_match('/^tt[0-9]{7}/', $id)) ? $str + 1 : $str;
            }
            return (count($args) > 0 && $str == count($args)) ? true : false;
        }
        else {
            return self::find()->where(['imdbID' => $args])->all();
        }
    }
}
