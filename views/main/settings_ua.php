<?php
use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$session = Yii::$app->session;

if ($session['language'] == 'ua')
    $lan = '';
else{
    $lan = 'Search..';
}
?>
<nav class="navbar navbar-inverse bg-inverse">
		<div class="nav-container">
			<div class="right-nav">
				<a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php

                        echo $user->user_name;

                    ?></a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<img src="<?= $user->user_avatar?>" alt="user_avatar" class="avatar-drop img-fluid" />
					<p class="dropdown-item"><?= $user->user_name." ".$user->user_secondname?></p>
					<a class="dropdown-item" href="main">На головну <i class="fa fa-film" aria-hidden="true"></i></a>
					<a class="dropdown-item" href="logout">Дати драла <i class="fa fa-power-off" aria-hidden="true"></i></a>
				</div>
                <span class="language-span" id='ua' onclick="changeLanguage(this)">UA</span><span id='en' class="language-span" onclick="changeLanguage(this)">EN</span>
			</div>
		</div>
	</nav>
	<div class="login-wrap">

        <input type="text" style="visibility: hidden" name="search" id="search-film" placeholder="<?=$lan?>">
		<div class="login-html">
		    <div class="login-form">
		        <div class="sign-up-htm" style="transform: rotateY(0);">
                    <?php $settingsform = ActiveForm::begin() ?>
					<div class="group user-avatar-block" >
						<label for="user-file" class="label">Поставить нову світлину</label>
                        <?= $settingsform->field($user, 'user_avatar')->fileInput(['class' => 'input', 'id' => 'user-file'])->label(false); ?>

						<img src="<?= $user->user_avatar?>" alt="" class="user-img-avatar img-fluid">
		            </div>
					<div class="group">
		                <label for="user-first-signup" class="label">Твій позивний</label>
                        <?= $settingsform->field($user, 'user_name')->textInput(['class' => 'input', 'id' => 'user-first-signup', 'readonly' => true])->label(false); ?>
		            </div>
					<div class="group">
		                <label for="user-last-signup" class="label">Ім'я роду твого</label>
                        <?= $settingsform->field($user, 'user_secondname')->textInput(['class' => 'input', 'id' => 'user-last-signup', 'readonly' => true])->label(false); ?>
		            </div>
					<div class="group">
		                <label for="email-signup" class="label">Адреса голубиної пошти</label>
                        <?= $settingsform->field($user, 'user_email')->textInput(['class' => 'input', 'id' => 'email-signup', 'readonly' => true])->label(false); ?>
		            </div>
		            <div class="group">
		                <label for="pass-signup" class="label">Ключ</label>
                        <?= $settingsform->field($user, 'user_password')->passwordInput(['class' => 'input', 'id' => 'pass-signup', 'readonly' => true])->label(false); ?>
		            </div>
		            <div class="group">
                        <span class="button btn-edit">Поправить</span>
		            </div>
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('error'); ?>
                        </div>
                    <?php endif; ?>
					<div class="hr"></div>
					<div class="group">
                        <?= Html::submitButton('Сохранитись', ['class' => 'button btn-save','id' => 'settingsSubmit']) ?>
		            </div>
                    <?php $settingsform = ActiveForm::end() ?>
		        </div>
		    </div>
		</div>
	</div>
