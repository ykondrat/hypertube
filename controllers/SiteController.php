<?php

namespace app\controllers;

use app\models\Forgot;
use app\models\Imdb;
use app\models\Login;
use app\models\Signup;
use DOMDocument;
use phpDocumentor\Reflection\Location;
use Symfony\Component\BrowserKit\Request;
use Yii;
use yii\authclient\OAuth2;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\User;
use yii\authclient\AuthAction;
use yii\helpers\Url;
use linslin\yii2\curl;
use Unirest;

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

        $session = Yii::$app->session;
        $session->open();
        $user_email = (array_key_exists("kind", $attributes)) ? $attributes['emails'][0]['value'] : $attributes['email'];
        $user = Signup::findOne(['user_email' => $user_email]);
        if ($user == NULL) {
            $user1 = new Signup();
            if (array_key_exists("kind", $attributes)) {
                $user1->user_name = $attributes['name']['givenName'];
                $user1->user_secondname = $attributes['name']['familyName'];
                $user1->user_email = $user_email;
                $user1->user_google_id = $attributes['id'];
                $user1->user_avatar = str_replace('sz=50', 'sz=750', $attributes['image']['url']);
            } else {
                $full_name = explode(" ", $attributes['name']);
                $user1->user_name = $full_name[0];
                $user1->user_secondname = $full_name[1];
                $user1->user_email = $attributes['email'];
                $user1->user_facebook_id = $attributes['id'];
                $user1->user_avatar = "//graph.facebook.com/" . $attributes['id'] . "/picture?type=large";
            }
            $user1->save(false);
            $session['loged_email'] = $user_email;
            $this->redirect('http://localhost:8080/hypertube/web/main');
        } else {
            $session['loged_email'] = $user_email;
            $session['loged_user'] = $user->user_login;
            $session->close();
            $this->redirect('http://localhost:8080/hypertube/web/main');
        }
    }

    public function actionMain()
    {

        return $this->render('main');
    }

    public function actionIndex()
    {
        $session = Yii::$app->session;

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
          `user_password` VARCHAR (1000) ,
          `user_rep_password` VARCHAR (1000),
          PRIMARY KEY (`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $user_table->query();

        $imdb_id_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `imdb_id` (
          `number` INT (4) UNSIGNED NOT NULL AUTO_INCREMENT,
          `imdbID` VARCHAR (15),
          `Title` VARCHAR (255),
          `Year` VARCHAR (5),
          `Runtime` VARCHAR (15),
          `Released` VARCHAR (255),
          `Genre` VARCHAR (50),
          `Director` VARCHAR (500),
          `Writer` VARCHAR (1000),
          `Actors` VARCHAR (255),
          `Plot` VARCHAR (1000),
          `Language` VARCHAR (1000),
          `Country` VARCHAR (500),
          `Awards` VARCHAR (1000),
          `Poster` VARCHAR (500),
          `Metascore` VARCHAR (5),
          `imdbRating` VARCHAR (10),
          `Production` VARCHAR (1000),         
          PRIMARY KEY (`number`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $imdb_id_table->query();

        $login = new Login();
        $signup = new Signup();
        $forgot = new Forgot();

//        ----------------LOGIN-------------------------

        if ($login->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('Login');
            $my_request = Login::find()->asArray()->where(['user_login' => $post['user_login']])->all();
            if ($my_request) {
                if (Yii::$app->getSecurity()->validatePassword($post['user_password'], $my_request[0]['user_password'])) {
                    $session['loged_user'] = $post['user_login'];
                    $session['loged_email'] = $my_request[0]['user_email'];
                    $this->redirect('http://localhost:8080/hypertube/web/main');
                } else {
                    Yii::$app->session->setFlash('error', 'The password you entered is invalid. Please try again');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No such registered login');
            }
        }
        //        ------------------SIGNUP-------------------------

        elseif ($signup->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('Signup');
            $my_request = Signup::find()->asArray()->where(['user_login' => $post['user_login']])->all();
            $my_request1 = Signup::find()->asArray()->where(['user_email' => $post['user_email']])->all();
            if ($my_request == NULL && $my_request1 == NULL) {
                if ($signup->validate()) {
                    $signup->user_password = Yii::$app->getSecurity()->generatePasswordHash($signup->user_password);
                    $signup->user_rep_password = $signup->user_password;
                    $signup->user_avatar = "default_avatar.png";
                    $signup->save(false);
                    $this->redirect('http://localhost:8080/hypertube/web/main');
                } else {
                    Yii::$app->session->setFlash('error', 'Please fill in all the fields correctly');
                }
            } else {
                if ($my_request != NULL) {
                    Yii::$app->session->setFlash('error', 'Such login already registered');
                } else {
                    Yii::$app->session->setFlash('error', 'Such email already registered');
                }
            }
        }
        //      --------------------------FORGOT_PASSWORD--------------

        elseif ($forgot->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('Forgot');
            $post = $post['user_email'];
            $my_request = User::find()->asArray()->where(['user_email' => $post])->all();
            if ($my_request) {
                $new_pass = Login::passwordGenerate();
                $user = User::findOne(['user_email' => $post]);
                $user->user_password = Yii::$app->getSecurity()->generatePasswordHash($new_pass);
                $user->user_rep_password = $user->user_password;
                $user->save();
                Yii::$app->mailer->compose()
                    ->setFrom('andrusechko@gmail.com')
                    ->setTo($post)
                    ->setSubject('Reset Password')
                    ->setTextBody("Your new password for Matchaff is - " . $new_pass)
                    ->send();
                Yii::$app->session->setFlash('success', 'We send you an e-mail message. Please check your email for further instructions');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'No such registered E-mail address');
            }
        }

        return $this->render('index', compact('login', 'signup', 'forgot'));
    }

    public function actionIntra()
    {
        $get = $_GET['code'];

        $curl = new curl\Curl();
        $response = $curl->setGetParams([
            'grant_type' => 'authorization_code',
            'client_id' => 'ab8c761b24b12bf91cee7442ff17068180783358189e8239f102a5b149ae812c',
            'client_secret' => '963d8d1605ad7196bf02514188ce4e31e697504037ab1ceab6e64e86ce991629',
            'code' => $get,
            'redirect_uri' => 'http://localhost:8080/hypertube/web/intra',
        ])->post('https://api.intra.42.fr/oauth/token');
        $key = json_decode($response);
        $accessToken = $key->access_token;

        $apiUrl = 'https://api.intra.42.fr/v2/me';

        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        if ($json) {
            $session = Yii::$app->session;
            $data = json_decode($json);

            if (!(Signup::findOne(['user_email' => $data->email]))) {
                $user = new Signup();
                $user->user_login = $data->login;
                $user->user_name = $data->first_name;
                $user->user_secondname = $data->last_name;
                $user->user_email = $data->email;
                $user->user_avatar = $data->image_url;
                $user->save(false);
            }
            $session['loged_user'] = $data->login;
            $this->redirect('http://localhost:8080/hypertube/web/main');
        }
    }

//8f911d74
    function getFullInformation($Imdb_id){

        $test =  file_get_contents("http://www.omdbapi.com/?apikey=8f911d74&i=tt".$Imdb_id);
        $json_film = json_decode($test);
        $film = array("Title" => $json_film->Title, "Year" => $json_film->Year, "Released" => $json_film->Released, "Runtime" => $json_film->Runtime,
            "Genre" => $json_film->Genre, "Director" => $json_film->Director, "Writer" => $json_film->Writer, "Actors" => $json_film->Actors,
            "Plot" =>  $json_film->Plot, "Language" => $json_film->Language, "Country" => $json_film->Country, "Awards" => $json_film->Awards,
            "Poster" => $json_film->Poster, "Metascore" => $json_film->Metascore, "imdbRating" => $json_film->imdbRating, "imdbID" => $json_film->imdbID,
            "Production" => $json_film->Production);
        return $film;

    }

    public function actionImdb()
    {
        $ch = curl_init('http://www.imdb.com/chart/top');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $htmlString = curl_exec($ch);
        curl_close($ch);
        if (isset($htmlString)) {
            $html = new DOMDocument();
            @ $html->loadHTML($htmlString);
            $ids = array();
            foreach ($html->getElementsByTagName('a') as $link) {
                $url = $link->getAttribute('href');
                if (strstr($url, '/title/tt') && !(in_array(substr($url, 9, 7), $ids))) {
                    $ids[] = substr($url, 9, 7);
                }
            }

            $ids = array_slice($ids, 213);
            foreach ($ids as $film){
                $i=0;
                while ($i < 1000000){
                    $i++;
                }
            $film = $this->getFullInformation($film);
        Yii::$app->db->createCommand()->insert('imdb_id', $film)->execute();
        $i = 0;
        while ($i < 1000000){
$i++;
        }
            }

        }
    }
}
