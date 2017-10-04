<?php

namespace app\modules\api\controllers;

use app\modules\api\models\User;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;

class UserController extends ActiveController
{

    public $modelClass = 'app\modules\api\models\User';

    public function actions()
    {
        $actions =  parent::actions();

        unset($actions['delete'], $actions['create']);

        return $actions;
    }
//    public function actionIndex()
//    {
//        return "info";
//    }

//    public function actionTest(){
//        print_r(Yii::$app->request->post());
//        die();
//    }
//
//    public function actionGetListOfUser(){
//        \Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $user = User::find()->select('user_id, user_name, user_secondname ,user_email')->all();
//
//        if (count($user) > 0){
//            return array('status' => true, 'data' => $user);
//        }
//        else{
//            return array('status' => false, 'data' => 'No users found.');
//        }
//    }
//
//
//    public function actionGetFullInformationById(){
//        \Yii::$app->response->format = Response::FORMAT_JSON;
//        $user = User::findOne(['user_id' => $id]);
//    }
}


//http://localhost:8080/hypertube/web/api/user/