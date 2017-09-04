<?php

/* @var $this yii\web\View */

$this->title = 'Matcha';
?>

<div class="login-wrap">
    <div class="login-html">
        <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
        <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
        <div class="login-form">

            <div class="sign-in-htm">
                <div class="group">
                    <label for="user" class="label">Username</label>
                    <input id="user" type="text" class="input">
                </div>
                <div class="group">
                    <label for="pass" class="label">Password</label>
                    <input id="pass" type="password" class="input" data-type="password">
                </div>
                <div class="group">
                    <input type="submit" class="button" value="Sign In">
                </div>
                <div class="group">
                    <h5>Continue with:</h5>
                </div>
                <div class="group">
                    <button href="/hypertube/web/site/auth?authclient=facebook" class="btn btn-primary">Facebook <i class="fa fa-facebook-square" aria-hidden="true"></i></button>
                    <button class="btn btn-danger">Google+ <i class="fa fa-google-plus-square" aria-hidden="true"></i></button>
                    <button class="btn btn-info">Intra 42 <img src="https://signin.intra.42.fr/assets/42_logo_black-684989d43d629b3c0ff6fd7e1157ee04db9bb7a73fba8ec4e01543d650a1c607.png" alt="Intra 42 logo"></button>
                </div>
                <div class="hr"></div>
                <div class="foot-lnk">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#forgot-password">
                        Forgot Password? <i class="fa fa-key" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="sign-up-htm">
                <div class="group">
                    <label for="user-signup" class="label">Username</label>
                    <input id="user-signup" type="text" class="input">
                </div>
                <div class="group">
                    <label for="email-signup" class="label">Email Address</label>
                    <input id="email-signup" type="email" class="input">
                </div>
                <div class="group">
                    <label for="pass-signup" class="label">Password</label>
                    <input id="pass-signup" type="password" class="input" data-type="password">
                </div>
                <div class="group">
                    <label for="pass-rep" class="label">Repeat Password</label>
                    <input id="pass-rep" type="password" class="input" data-type="password">
                </div>
                <div class="group">
                    <input type="submit" class="button" value="Sign Up">
                </div>
                <div class="hr"></div>
                <div class="foot-lnk">
                    <label for="tab-1">Already Member?</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="forgot-password" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Forgot Password?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="group">
                    <label for="email-forgot" class="label">Email Address</label>
                    <input id="email-forgot" type="email" class="input">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success">Send new password <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
</div>
