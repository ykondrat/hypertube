<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Login';

?>
<div class="site-login">

    <?php $form = ActiveForm::begin() ?>
    <div class="container">
        <div class="omb_login ">
            <h3 class="omb_authTitle">Login or <a href="signup">Sign up</a></h3>
            <div class="row omb_row-sm-offset-3 omb_socialButtons mycenter">
                <div class="col-xs-4 col-sm-2">
                    <a href="/matcha/web/site/auth?authclient=facebook" class="btn btn-lg btn-block omb_btn-facebook">
                        <i class="fa fa-facebook visible-xs"></i>
                        <span class="hidden-xs">Facebook</span>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="/matcha/web/site/auth?authclient=facebook" class="btn btn-lg btn-block omb_btn-google">
                        <i class="fa fa-google-plus visible-xs"></i>
                        <span class="hidden-xs">Google+</span>
                    </a>
                </div>
            </div>


            <div class="row omb_row-sm-offset-3 omb_loginOr">
                <div class="col-xs-12 col-sm-6">
                    <hr class="omb_hrOr">
                    <span class="omb_spanOr">or</span>
                </div>
            </div>
            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo Yii::$app->session->getFlash('error'); ?>
                </div>
            <?php endif; ?>
            <div class="row omb_row-sm-offset-3">
                <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <?= $form->field($login, 'user_login')
                                ->textInput([ 'placeholder' => 'Login', 'class' => 'form-control'])
                                ->label(false) ?>
                        </div>
                        <span class="help-block"></span>
                        <div class="form-group">
                            <?= $form->field($login, 'user_password')
                                ->passwordInput([ 'placeholder' => 'Password', 'class' => 'form-control'])
                                ->label(false) ?>
                        </div>
                        <br>
                        <?= Html::submitButton('Login', ['class' => 'btn btn-lg btn-primary btn-block', 'id' => 'loginSubmit']) ?>
                </div>
            </div>
            <div class="row omb_row-sm-offset-3">
                <div class="col-xs-12 col-sm-3"></div>
                <div class="col-xs-12 col-sm-3">
                    <p class="omb_forgotPwd">
                        <a href="forgot">Forgot password?</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php $form = ActiveForm::end() ?>

</div>
