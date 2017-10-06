<?php

namespace app\modules\api\models;

use Yii;


class User extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['user_name', 'user_secondname', 'user_email'], 'required'],
            [['user_facebook_id'], 'integer'],
            [['user_name', 'user_secondname', 'user_email'], 'string', 'max' => 100],
            [['user_avatar', 'user_avatar2'], 'string', 'max' => 255],
            [['user_google_id'], 'string', 'max' => 30],
            [['user_password', 'user_rep_password'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_secondname' => 'User Secondname',
            'user_email' => 'User Email',
            'user_avatar' => 'User Avatar',
            'user_avatar2' => 'User Avatar2',
            'user_facebook_id' => 'User Facebook ID',
            'user_google_id' => 'User Google ID',
            'user_password' => 'User Password',
            'user_rep_password' => 'User Rep Password',
        ];
    }

    /** Return list of all users (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`). Parameter $args = [NULL] */

    function GetUserList(array $args, $check){
        if ($check == true) {
            return (count($args) == 0 ) ? true : false;
        }
        else {
            return self::find()->select('user_id, user_name, user_secondname , user_email')->all();
        }
    }

    /** Return users information (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`) by `user_id`. Parameter $args = [<ids of users>] */

    function GetUserById(array $args, $check){
        if ($check == true) {
            $int = 0;
            foreach ($args as $id){
                $int = (is_int($id)) ? $int + 1 : $int;
            }
            return (count($args) > 0 && $int == count($args)) ? true : false;
        }
        else {
            return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['user_id' => $args])->all();
        }
    }

    /** Search users by name and return information about them (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`) . Parameter $args = [<name of user or part of it>] */

    function SearchUserByName(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['like', 'user_name', $args[0]])->all();
        }
    }

    /** Search users by surname and return information about them (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`). Parameter $args = [<surname of user or part of it>] */

    function SerchUserBySecondname(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['like', 'user_secondname', $args[0]])->all();
        }
    }

    /** Search user by email and return information about him (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`). Parameter $args = [<email of user or part of it>] */

    function SerchUserByEmail(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['like', 'user_email', $args[0]])->all();
        }
    }

    /** Update user information by `user_id` . Parameter $args = [ <id of user>, <parameter that you want to change> , <value ​​of this parameter>  ] */
    /** valid name of parameter : user_name , user_secondname  */

    function UpdateUserData(array $args, $check){

        if ($check == true) {

            return (count($args) == 3 && is_int($args[0])
                    && is_string($args[1]) && is_string($args[2])
                    && strstr('user_name user_secondname', $args[1])
                    && strlen($args[2]) >= 2 && strlen($args[2]) <= 20)
                    ? true : false;
        }
        else {

            $user = self::find()->where(['user_id' => $args[0]])->one();
            if ($user != NULL) {
                if ($args[1] == 'user_name') {
                    $user->user_name = $args[2];
                } else {
                    $user->user_secondname = $args[2];
                }
                $user->save(false);
                return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['user_id' => $args[0]])->one();
            }
            else{
                return array('status' => false, 'data' => 'A user with `user_id` = '.$args[0].' does not exist');
            }
        }
    }

    /** Delete user by `user_id` . Parameter $args = [ <id of user> ] */

    function DeleteUser(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_int($args[0]) ) ? true : false;
        }
        else {
            Yii::$app->db->createCommand()->delete('user', ['user_id' => $args[0]])->execute();
            return self::find()->select('user_id, user_name, user_secondname , user_email')->all();
        }
    }

    /** Create new user . Parameter $args = [ `user_name`, `user_secondname`, `user_email`, `user_password` ] */

    function CreateUser(array $args, $check){
        if ($check == true) {
            $flag = true;
            $flag = (count($args) == 4) ? $flag : false;
            $flag = (is_string($args[0]) && strlen($args[0]) >= 2 && strlen($args[0]) <= 20 ) ? $flag : false;
            $flag = (is_string($args[1]) && strlen($args[1]) >= 2 && strlen($args[1]) <= 20 ) ? $flag : false;
            $flag = (is_string($args[2]) && strlen($args[2]) >= 1 &&  filter_var($args[2], FILTER_VALIDATE_EMAIL)) ? $flag : false;
            $flag = (is_string($args[3]) && strlen($args[3]) >= 8 ) ? $flag : false;
            return $flag;
        }
        else{
            $user = new User();
            $user->user_name = $args[0];
            $user->user_secondname = $args[1];
            $user->user_email = $args[2];
            $user->user_password = Yii::$app->getSecurity()->generatePasswordHash($args[3]);
            $user->user_rep_password = $user->user_password;
            $user->user_avatar = "ninja.png";
            $user->save(false);
            return self::find()->select('user_id, user_name, user_secondname , user_email')->all();
        }
    }

}