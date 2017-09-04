<?php

namespace app\controllers;

use app\models\Login;
use phpDocumentor\Reflection\Location;
use Yii;
use yii\authclient\OAuth2;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\User;
use yii\authclient\AuthAction;
use yii\helpers\Url;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();

        var_dump($attributes);
        die();
//        $session = Yii::$app->session;
//        $session->open();
//        $user_email = (array_key_exists( "kind" , $attributes)) ? $attributes['emails'][0]['value'] : $attributes['email'] ;
//        $user = User::findOne(['user_email' => $user_email]);
//        if ($user == NULL){
//            $user1 = new User();
//            if (array_key_exists( "kind" , $attributes)){
//                $user1->user_name = $attributes['name']['givenName'];
//                $user1->user_secondname = $attributes['name']['familyName'];
//                $user1->user_email = $user_email;
//                $user1->user_google_id = $attributes['id'];
//                $user1->user_avatar = str_replace('sz=50', 'sz=750', $attributes['image']['url']);
//            }
//            else {
//                $full_name = explode(" ", $attributes['name']);
//                $user1->user_name = $full_name[0];
//                $user1->user_secondname = $full_name[1];
//                $user1->user_email = $attributes['email'];
//                $user1->user_facebook_id = $attributes['id'];
//                $user1->user_avatar = "//graph.facebook.com/".$attributes['id']."/picture?type=large";
//            }
//            $user1->save(false);
//            $session['loged_email'] = $user_email;
//            $this->redirect('http://localhost:8080/matcha/web/profiledata');
//        }
//        else{
//            $session['loged_email'] = $user_email;
//            $session['loged_user'] = $user->user_login;
//            $session->close();
//            if ($user->user_profile_complete == 1){
//                $this->redirect('http://localhost:8080/matcha/web/account');
//            }else{
//                $this->redirect('http://localhost:8080/matcha/web/profiledata');
//            }
//        }
    }

    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->destroy();

        $user_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `user` (
          `user_id` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_name` VARCHAR (100) NOT NULL ,
          `user_secondname` VARCHAR (100) NOT NULL,
          `user_email` VARCHAR (100) NOT NULL,
          `user_login` VARCHAR (20) ,
          `user_avatar` VARCHAR (255),
          `user_facebook_id` BIGINT (30) UNSIGNED,
          `user_google_id` VARCHAR (30) ,
          `user_intra_id` VARCHAR (30) ,
          `user_password` VARCHAR (1000) ,
          `user_rep_password` VARCHAR (1000),
          `user_latitude` VARCHAR (100) ,
          PRIMARY KEY (`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $user_table->query();
        return $this->render('index');
    }





    public function actionLogin()
    {
        $login = new Login();
        if ($login->load(Yii::$app->request->post())){
            $post = Yii::$app->request->post('Login');
            $my_request = User::find()->asArray()->where(['user_login' => $post['user_login']])->all();
            if ($my_request) {
                if (Yii::$app->getSecurity()->validatePassword($post['user_password'], $my_request[0]['user_password'])) {
                    $session = Yii::$app->session;
                    $session->open();
                    $session['loged_user'] = $post['user_login'];
                    $session['loged_email'] = $my_request[0]['user_email'];
                    $session->close();
                    $location = substr(file_get_contents('https://geoip-db.com/json/geoip.php?jsonp='), 1 ,strlen(file_get_contents('https://geoip-db.com/json/geoip.php?jsonp=')) - 2 );

                    $this->redirect('http://localhost:8080/matcha/web/profiledata');
                } else {
                    Yii::$app->session->setFlash('error', 'The password you entered is invalid. Please try again');
                }
            }else {
                Yii::$app->session->setFlash('error', 'No such registered login');
            }
        }
        return $this->render('login', compact('login'));
    }

    public function actionSignup()
    {
        $user = new User();
        if ($user->load(Yii::$app->request->post())){
            $post = Yii::$app->request->post('User');
            $my_request = User::find()->asArray()->where(['user_login' => $post['user_login']])->all();
            $my_request1 = User::find()->asArray()->where(['user_email' => $post['user_email']])->all();
            if ($my_request == NULL && $my_request1 == NULL) {
                if ($user->validate()) {
                    $user->user_password = Yii::$app->getSecurity()->generatePasswordHash($user->user_password);
                    $user->user_rep_password = $user->user_password;
                    $user->save(false);
                    Yii::$app->session->setFlash('success', 'You have successfully signed up, now you can login to Matcha');
                    return $this->refresh();
                }else {
                    Yii::$app->session->setFlash('error',  'Please fill in all the fields correctly');
                }
            }else {
                if ($my_request != NULL) {
                    Yii::$app->session->setFlash('error', 'Such login already registered');
                }else{
                    Yii::$app->session->setFlash('error', 'Such email already registered');
                }
            }
        }
        return $this->render('signup', compact('user'));
    }

    public function actionForgot(){
        $forgot = new Login();
        if ($forgot->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('Login');
            $post = $post['user_email'];
            $my_request = User::find()->asArray()->where(['user_email' => $post])->all();
            if ($my_request){
                $new_pass = Login::passwordGenerate();
                $user = User::findOne(['user_email' => $post]);
                $user->user_password = Yii::$app->getSecurity()->generatePasswordHash($new_pass);
                $user->user_rep_password = $user->user_password;
                $user->save();
                Yii::$app->mailer->compose()
                    ->setFrom('andrusechko@gmail.com')
                    ->setTo($post)
                    ->setSubject('Reset Password')
                    ->setTextBody("Your new password for Matchaff is - ".$new_pass)
                    ->send();
                Yii::$app->session->setFlash('success', 'We send you an e-mail message. Please check your email for further instructions');
                return $this->refresh();
            }else {
                Yii::$app->session->setFlash('error',  'No such registered E-mail address');
            }
        }
        return $this->render('forgot', compact('forgot'));
    }
}