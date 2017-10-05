<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 05.10.17
 * Time: 16:11
 */

namespace app\modules\api\controllers;

use app\modules\api\models\User;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class InfoController extends Controller{

    public function actionIndex(){
         return $this->renderPartial('index');
    }
}