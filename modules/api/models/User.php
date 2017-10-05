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



    function Test(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['like', 'user_name', $args[0]])->all();
        }
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

    /** Update user information by `user_id` . Parameter $args = [ <id of user>, [ <parameters that you want to change> ] , [ <values ​​of these parameters> ] ] */
    /** valid name of parameters : user_name , user_secondname  */
    function UpdateUserData(array $args, $check){
        $parameters = $args[1];
        if ($check == true) {
            $flag = 1;
            foreach ($parameters as $parameter){
               $flag =  (strlen( $parameter) >= 2 && strlen( $parameter) <= 20) ? $flag : 0;
            }
            return (count($args) == 3 && is_int($args[0]) && is_array($args[1]) && count($args[1]) > 0 && is_array($args[2]) && count($args[2]) > 0 && count($args[2]) == count($args[1]) && $flag) ? true : false;
        }
        else {
            foreach ($parameters as $key => $parameter){
                $user = self::updateAll([$key => $parameter, ['user_id' => $args[0]]]);
            }
            return self::find()->select('user_id, user_name, user_secondname , user_email')->where(['user_id' => $args[0]])->all();
        }
    }

    /** Delete user by `user_id` . Parameter $args = [ <id of user> ] */
    function DeleteUser(array $args, $check){
        if ($check == true) {
            $int = 0;
            foreach ($args as $id){
                $int = (is_int($id)) ? $int + 1 : $int;
            }
            return (count($args) > 0 && $int == count($args)) ? true : false;
        }
        else {
//            $user = self::
            return self::find()->select('user_id, user_name, user_secondname , user_email')->all();
        }
    }

    /** Create new user . Parameter $args = [ `user_name`, `user_secondname`, `user_email`, `user_password` ] */
    function CreateUser(){

    }

}
