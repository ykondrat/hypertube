<?php

namespace app\modules\api\controllers;

use app\modules\api\models\User;
use yii\rest\Controller;
use yii\web\Response;
use Yii;

class CallController extends Controller
{
        public function checkData($post){
        $model = "app\\modules\\api\\models\\".ucfirst($post['Model']);
        $method = ucfirst($post['Method']);
        if (is_array($post['Args'])){
            $args = $post['Args'];
            $status = (class_exists($model)) ? true : false;
            $status = (method_exists($model, $method)) ? $status : false;
            $status = (call_user_func_array(array($model, $method), array($args, true))) ? $status : false;
            return $status;
        }
        else{
            return false;
        }
    }

    public function actionIndex(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if (isset($post['Model']) && isset($post['Method']) && isset($post['Args']) && count($post) == 3 && $this->checkData($post)){
            $model = "app\\modules\\api\\models\\".ucfirst($post['Model']);
            $method = ucfirst($post['Method']);
            $args = $post['Args'];
            return call_user_func_array(array($model, $method), array($args, false));
        }
        else {
            return $this->Error();
        }
    }

    public function Error(){
        return array('status' => false, 'data' => 'You have some problems with your request. For more information please visit http://localhost:8080/hypertube/web/api/info ');
    }

}
