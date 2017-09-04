<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 05.08.17
 * Time: 11:22
 */
use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forgot Password';
?>

<div class="form-gap"></div>
<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin() ?>
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
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3><i class="fa fa-lock fa-4x"></i></h3>
                        <h2 class="text-center">Forgot Password?</h2>
                        <p>You can reset your password here.</p>
                        <div class="panel-body">
                                <div class="form-group">
                                        <?= $form->field($forgot, 'user_email')->textInput(['class' => 'form-control', 'placeholder' => "email address"])->label(false) ?>
                                </div>
                                <div class="form-group">
                                    <?= Html::submitButton('Reset Password', ['class' => 'btn btn-lg btn-primary btn-block', 'id' => 'loginSubmit']) ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $form = ActiveForm::end() ?>
</div>