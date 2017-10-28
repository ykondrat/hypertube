<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Hypertube';
?>

<div class="right-nav">
    <span class="language-span" id='ua' onclick="changeLanguage(this)">UA</span><span id='en' class="language-span" onclick="changeLanguage(this)">EN</span>
</div>
<div class="login-wrap">

    <div class="login-html">

        <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Вломитись</label>
        <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Прописатись</label>
        <div class="login-form">

            <div class="sign-in-htm">
                <?php $loginform = ActiveForm::begin() ?>
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
                <div class="group">
                    <label for="user" class="label">Адреса голубиної пошти</label>
                    <?= $loginform->field($login, 'user_email')->textInput(['class' => 'input'])->label(false); ?>
                </div>
                <div class="group">
                    <label for="pass" class="label">Ключ</label>
                    <?= $loginform->field($login, 'user_password')->passwordInput(['class' => 'input'])->label(false); ?>

                </div>
                <div class="group">
                    <?= Html::submitButton('Вломитись', ['class' => 'button', 'id' => 'loginSubmit']) ?>
                </div>
                <?php $loginform = ActiveForm::end() ?>
                <div class="group">
                    <h5>Корішнутись через:</h5>
                </div>
                <div class="group">
                    <a href="/hypertube/web/site/auth?authclient=facebook" class="btn btn-primary">Facebook <i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                    <a href="/hypertube/web/site/auth?authclient=google" class="btn btn-danger">Google+ <i class="fa fa-google-plus-square" aria-hidden="true"></i></a>
                    <a href="https://api.intra.42.fr/oauth/authorize?client_id=ab8c761b24b12bf91cee7442ff17068180783358189e8239f102a5b149ae812c&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2Fhypertube%2Fweb%2Fintra&response_type=code
" class="btn btn-info">Intra 42 <img src="https://signin.intra.42.fr/assets/42_logo_black-684989d43d629b3c0ff6fd7e1157ee04db9bb7a73fba8ec4e01543d650a1c607.png" alt="Intra 42 logo"></a>
                </div>
                <div class="hr"></div>
                <div class="foot-lnk">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#forgot-password">
                        Завтикав ключ? <i class="fa fa-key" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="sign-up-htm">
                <?php $signupform = ActiveForm::begin() ?>
                <div class="group">
                    <label for="username-signup" class="label">Як ти сі називаєш?</label>
                    <?= $signupform->field($signup, 'user_name')->textInput(['class' => 'input'])->label(false); ?>
                </div>
                <div class="group">
                    <label for="usersecondname-signup" class="label">Ім'я роду твого</label>
                    <?= $signupform->field($signup, 'user_secondname')->textInput(['class' => 'input'])->label(false); ?>
                </div>
                <div class="group">
                    <label for="email-signup" class="label">Адреса голубиної пошти</label>
                    <?= $signupform->field($signup, 'user_email')->textInput(['class' => 'input'])->label(false); ?>
                </div>
                <div class="group">
                    <label for="pass-signup" class="label">Ключ</label>
                    <?= $signupform->field($signup, 'user_password')->passwordInput(['class' => 'input'])->label(false); ?>
                </div>
                <div class="group">
                    <label for="pass-rep" class="label">Дублікат ключа</label>
                    <?= $signupform->field($signup, 'user_rep_password')->passwordInput(['class' => 'input'])->label(false); ?>
                </div>
                <div class="group">
                    <?= Html::submitButton('Прописатись', ['class' => 'button', 'id' => 'signupSubmit']) ?>
                </div>
                <?php $signupform = ActiveForm::end() ?>

                <div class="hr"></div>
                <div class="foot-lnk">
                    <label for="tab-1">Вже прописався?</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="forgot-password" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Завтикав ключ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php $forgotform = ActiveForm::begin() ?>
            <div class="modal-body">

                <div class="group">
                    <label for="email-forgot" class="label">Адреса голубиної пошти</label>
                    <?= $forgotform->field($forgot, 'user_email')->textInput(['class' => 'input'])->label(false) ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Зробити новий ключ<i class="fa fa-paper-plane" aria-hidden="true"></i>', ['class' => 'btn btn-success', 'id' => 'forgotSubmit']) ?>

            </div>
            <?php $forgotform = ActiveForm::end() ?>
        </div>
    </div>
</div>
