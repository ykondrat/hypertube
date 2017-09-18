<?php
use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<nav class="navbar navbar-inverse bg-inverse">
		<div class="nav-container">
			<div class="right-nav">
				<a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php
                    if ($user->user_login != NULL):
                        echo $user->user_login;
                    else :
                        echo $user->user_name;
                    endif;
                    ?></a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<img src="<?= $user->user_avatar?>" alt="user_avatar" class="avatar-drop img-fluid" />
					<p class="dropdown-item"><?= $user->user_name." ".$user->user_secondname?></p>
					<a class="dropdown-item" href="main">Main page <i class="fa fa-film" aria-hidden="true"></i></a>
					<a class="dropdown-item" href="logout">Logout <i class="fa fa-power-off" aria-hidden="true"></i></a>
				</div>
				<span class="language-span" onclick="changeLanguage(this)">UA</span><span class="language-span checked" onclick="changeLanguage(this)">EN</span>
			</div>
		</div>
	</nav>
	<div class="login-wrap">
		<div class="login-html">
		    <div class="login-form">
		        <div class="sign-up-htm" style="transform: rotateY(0);">
                    <?php $settingsform = ActiveForm::begin() ?>
					<div class="group user-avatar-block" >
						<label for="user-file" class="label">Set avatar</label>
                        <?= $settingsform->field($user, 'user_avatar')->fileInput(['class' => 'input', 'id' => 'user-file'])->label(false); ?>

						<img src="<?= $user->user_avatar?>" alt="" class="user-img-avatar img-fluid">
		            </div>
		            <div class="group">
		                <label for="user-signup" class="label" >User login</label>
                        <?= $settingsform->field($user, 'user_login')->textInput(['class' => 'input', 'id' => 'user-signup', 'readonly' => true])->label(false); ?>
		            </div>
					<div class="group">
		                <label for="user-first-signup" class="label">First name</label>
                        <?= $settingsform->field($user, 'user_name')->textInput(['class' => 'input', 'id' => 'user-first-signup', 'readonly' => true])->label(false); ?>
		            </div>
					<div class="group">
		                <label for="user-last-signup" class="label">Last name</label>
                        <?= $settingsform->field($user, 'user_secondname')->textInput(['class' => 'input', 'id' => 'user-last-signup', 'readonly' => true])->label(false); ?>
		            </div>
					<div class="group">
		                <label for="email-signup" class="label">Email Address</label>
                        <?= $settingsform->field($user, 'user_email')->textInput(['class' => 'input', 'id' => 'email-signup', 'readonly' => true])->label(false); ?>
		            </div>
		            <div class="group">
		                <label for="pass-signup" class="label">Password</label>
                        <?= $settingsform->field($user, 'user_password')->passwordInput(['class' => 'input', 'id' => 'pass-signup', 'readonly' => true])->label(false); ?>
		            </div>
		            <div class="group">
                        <span class="button btn-edit">Edit</span>
		            </div>
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('error'); ?>
                        </div>
                    <?php endif; ?>
					<div class="hr"></div>
					<div class="group">
                        <?= Html::submitButton('Save Changes', ['class' => 'button btn-save','id' => 'settingsSubmit']) ?>
		            </div>
                    <?php $settingsform = ActiveForm::end() ?>
		        </div>
		    </div>
		</div>
	</div>
