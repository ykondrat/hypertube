<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Sign up';

?>
<div class="site-login">
    <?php $form = ActiveForm::begin() ?>
    <div class="container">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-body">
                        <div class="omb_login">
                        <h1 class="omb_authTitle">Sign up</h1>
                        <div class="row omb_row-sm-offset-3 omb_socialButtons mycenter">
                            <div class="col-xs-4 col-sm-2">
                                <a href="/matcha/web/site/auth?authclient=facebook" class="btn btn-lg btn-block omb_btn-facebook">
                                    <i class="fa fa-facebook visible-xs"></i>
                                    <span class="hidden-xs">Facebook</span>
                                </a>
                            </div>
                            <div class="col-xs-4 col-sm-2">
                                <a href="/matcha/web/site/auth?authclient=google" class="btn btn-lg btn-block omb_btn-google">
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
                        </div>
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo Yii::$app->session->getFlash('success'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo Yii::$app->session->getFlash('error'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <?= $form->field($user, 'user_name') ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($user, 'user_secondname') ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($user, 'user_email') ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($user, 'user_login') ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($user, 'user_password')->passwordInput() ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($user, 'user_rep_password')->passwordInput() ?>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Create your account', ['class' => 'btn btn-info btn-block', 'id' => 'signupSubmit']) ?>
                        </div>
                        <hr>
                        <p></p>Already have an account? <a href="login">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
    <?php $form = ActiveForm::end() ?>
</div>
