<?php

namespace app\modules\api\models;

use app\models\Imdb;
use Yii;


class Comment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'comment';
    }

    public function rules()
    {
        return [
            [['user_name', 'user_secondname', 'imdbID', 'text'], 'required'],
            [['time'], 'safe'],
            [['user_name', 'user_secondname', 'imdbID'], 'string', 'max' => 100],
            [['user_avatar'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'user_secondname' => 'User Secondname',
            'user_avatar' => 'User Avatar',
            'imdbID' => 'Imdb ID',
            'time' => 'Time',
            'text' => 'Text',
        ];
    }

    /** Return list of all comments (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`). Parameter $args = [NULL] */

    function GetCommentList(array $args, $check){
        if ($check == true) {
            return (count($args) == 0 ) ? true : false;
        }
        else {
            return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->all();
        }
    }

    /** Return comments information (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`) by `id`. Parameter $args = [<ids of comments>] */

    function GetCommentById(array $args, $check){
        if ($check == true) {
            $int = 0;
            foreach ($args as $id){
                $int = (is_int($id)) ? $int + 1 : $int;
            }
            return (count($args) > 0 && $int == count($args)) ? true : false;
        }
        else {
            return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->where(['id' => $args])->all();
        }
    }

    /** Search comments by user name and return information about them (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`) . Parameter $args = [<name of user or part of it>] */

    function SearchCommentByUserName(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->where(['like', 'user_name', $args[0]])->all();
        }
    }

    /** Search comments by user surname and return information about them (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`). Parameter $args = [<surname of user or part of it>] */

    function SerchCommentByUserSecondname(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->where(['like', 'user_secondname', $args[0]])->all();
        }
    }

    /** Search comments by imdbID and return information about them (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`). Parameter $args = [<imdbID of comment or part of it>] */

    function SerchCommentByImdbId(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->where(['like', 'imdbID', $args[0]])->all();
        }
    }

    /** Update comment text by `id` . Parameter $args = [ <id of comment>, <new text> ] */

    function UpdateCommentData(array $args, $check){

        if ($check == true) {
            return (count($args) == 2 && is_int($args[0]) && is_string($args[1]) && strlen($args[2]) >= 1 && strlen($args[2]) <= 1000) ? true : false;
        }
        else {

            $comment = self::find()->where(['id' => $args[0]])->one();
            if ($comment != NULL) {
                $comment->text = $args[2];
                $comment->save(false);
                return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->where(['id' => $args[0]])->one();
            }
            else{
                return array('status' => false, 'data' => 'A comment with `id` = '.$args[0].' does not exist');
            }
        }
    }

    /** Delete comment by `id` . Parameter $args = [ <id of comment> ] */

    function DeleteComment(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_int($args[0]) ) ? true : false;
        }
        else {
            Yii::$app->db->createCommand()->delete('comment', ['id' => $args[0]])->execute();
            return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->all();
        }
    }

    /** Create new comment . Parameter $args = [ `user_name`, `user_secondname`, `imdbID`, `text` ] */

    function CreateComment(array $args, $check){
        if ($check == true) {
            $flag = true;
            $flag = (count($args) == 4) ? $flag : false;
            $flag = (is_string($args[0]) && strlen($args[0]) >= 2 && strlen($args[0]) <= 20 ) ? $flag : false;
            $flag = (is_string($args[1]) && strlen($args[1]) >= 2 && strlen($args[1]) <= 20 ) ? $flag : false;
            $flag = (is_string($args[2]) && strlen($args[2]) == 9 ) ? $flag : false;
            $flag = (is_string($args[3]) && strlen($args[3]) >= 1 && strlen($args[3]) <= 1000) ? $flag : false;
            return $flag;
        }
        else{
            $imdb = Imdb::find()->where(['imdbID' => $args[2]])->one();
            if ($imdb) {
                $user = \app\models\User::find()->where(['user_name' => $args[0], 'user_secondname' => $args[1]])->one();
                if ($user) {
                    Yii::$app->db->createCommand()->insert('comment', [
                        'user_name' => $args[0],
                        'user_secondname' => $args[1],
                        'user_avatar' => $user->user_avatar,
                        'text' => $args[3],
                        'imdbID' => $args[2],
                    ])->execute();
                    return self::find()->select('id, user_name, user_secondname , imdbID, time, text')->where(['imdbID' => $args[2]])->all();
                }
                else{
                    return array('status' => false, 'data' => 'A user with `user_name` = '.$args[0].' `user_secondname` = '.$args[1].' does not exist in database');
                }
            }
            else {
                return array('status' => false, 'data' => 'A film with `imdbId` = '.$args[2].' does not exist in database');
            }
        }
    }

}
