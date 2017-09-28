<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 28.09.17
 * Time: 15:44
 */

namespace app\controllers;

use yii\web\Controller;
class ErrorController  extends Controller
{
    public $layout = 'error';

    public function Error(){
        return $this->render('404');
    }
}