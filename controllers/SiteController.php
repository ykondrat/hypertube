<?php

namespace app\controllers;

use app\models\Forgot;
use app\models\Genre;
use app\models\Imdb;
use app\models\Login;
use app\models\Signup;
use app\models\Torrent;
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
use yii\helpers\FileHelper;
use Composer\Autoload;
use DOMXPath;

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

/**     CALLBACK FROM GOOGLE AND FACEBOOK    */

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
                $user1->user_avatar2 = str_replace('sz=50', 'sz=750', $attributes['image']['url']);
            } else {
                $full_name = explode(" ", $attributes['name']);
                $user1->user_name = $full_name[0];
                $user1->user_secondname = $full_name[1];
                $user1->user_email = $attributes['email'];
                $user1->user_facebook_id = $attributes['id'];
                $user1->user_avatar = "//graph.facebook.com/" . $attributes['id'] . "/picture?type=large";
                $user1->user_avatar2 = "//graph.facebook.com/" . $attributes['id'] . "/picture?type=large";
            }
            $user1->save(false);

            $session['loged_email'] = $user1->user_email;
            $this->redirect('http://localhost:8080/hypertube/web/main');
        } else {
            $session['loged_email'] = $user->user_email;

            $this->redirect('http://localhost:8080/hypertube/web/main');
        }
    }

/**     CREATE TABLE AND INSERT DATA   */

    public function Dbcreat(){

/**        CREATE TABLE "USER"         */

        $user_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `user` (
          `user_id` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_name` VARCHAR (100) NOT NULL ,
          `user_secondname` VARCHAR (100) NOT NULL,
          `user_email` VARCHAR (100) NOT NULL,
          `user_avatar` VARCHAR (255),
          `user_avatar2` VARCHAR (255),
          `user_facebook_id` BIGINT (30) UNSIGNED,
          `user_google_id` VARCHAR (30) ,
          `user_intra_id` VARCHAR (30) ,
          `user_password` VARCHAR (1000) ,
          `user_rep_password` VARCHAR (1000),
          PRIMARY KEY (`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $user_table->query();

/**        CREATE TABLE "IMDB_ID"      */

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

        /**        CREATE TABLE "IMDB_ID_ua"      */

        $imdb_id_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `imdb_id_ua` (
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

/**         CREATE TABLE "GENRE"       */

        $genre_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `genre` (
          `genre` VARCHAR (100) NOT NULL ,
          PRIMARY KEY (`genre`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $genre_table->query();


/**         CREATE TABLE "TORRENT_LINK" */

        $torrent_link_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `torrent_link` (
          `number` INT (10) UNSIGNED NOT NULL ,
          `imdbID` VARCHAR (15) NOT NULL ,
          `url` VARCHAR (600) NOT NULL ,
          `hash` VARCHAR (100)  ,
          `quality` VARCHAR (20) NOT NULL ,
          `seeds` VARCHAR (6) NOT NULL ,
          `peers` VARCHAR (6) NOT NULL ,
          `size` VARCHAR (20) NOT NULL ,
          `size_bytes` VARCHAR (20),
          `date_uploaded` VARCHAR (100) NOT NULL ,
          `date_uploaded_unix` VARCHAR (100)  ,
          `torent_done` VARCHAR (20) DEFAULT NULL,
          PRIMARY KEY (`number`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $torrent_link_table->query();

        /**        CREATE TABLE "SUBTITLE"         */

        $subtitle_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `subtitle` (
          `number` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `imdb_id` VARCHAR (100) ,
          `language` VARCHAR (100) ,
          `path_url` VARCHAR (100) ,
          `path_folder` VARCHAR (255),
          PRIMARY KEY (`number`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $subtitle_table->query();

        /**        CREATE TABLE "Comment"         */

        $comment_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `comment` (
          `id` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_name` VARCHAR (100) NOT NULL ,
          `user_secondname` VARCHAR (100) NOT NULL,
          `user_avatar` VARCHAR (255),
          `imdbID` VARCHAR (100) NOT NULL,
          `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
          `text` VARCHAR (1000) NOT NULL,
          PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $comment_table->query();

/**         INSERT DATA TO "IMDB_ID"    */

        if (Imdb::find()->asArray()->all() == NULL){
            require_once Yii::$app->basePath . '/web/db_data/imdb_id(1000).php';
            require_once Yii::$app->basePath . '/web/db_data/imdb_id_ua(1000).php';

            $imdb_add = Yii::$app->db->createCommand()->batchInsert('imdb_id', ['number' ,'imdbID','Title','Year','Runtime','Released','Genre','Director','Writer','Actors'
                ,'Plot','Language','Country','Awards','Poster','Metascore','imdbRating','Production',], $imdb_id_array)->execute();
            $imdb_add2 = Yii::$app->db->createCommand()->batchInsert('imdb_id_ua', ['number' ,'imdbID','Title','Year','Runtime','Released','Genre','Director','Writer','Actors'
            ,'Plot','Language','Country','Awards','Poster','Metascore','imdbRating','Production',], $imdb_id_ua)->execute();
        }

/**         INSERT DATA TO "GENRE"      */

        if (Genre::find()->asArray()->all() == NULL){

            $genre_string = '';
            $genres = Imdb::find()->select('Genre')->all();
            foreach ($genres as $genre){
                $genre_string = $genre_string.', '.$genre->Genre;
            }
            $genre_array = explode(', ', $genre_string);
            $genre_array = array_unique($genre_array);
            sort($genre_array, SORT_STRING);
            unset($genre_array[0]);
            $i = 1;
            while (array_key_exists($i,$genre_array) ){
                $genre_add = Yii::$app->db->createCommand()->insert('genre', ['genre' => $genre_array[$i]])->execute();
                $i++;
            }
        }

/**         INSERT DATA TO "TORRENT_ID"   */

        if (Torrent::find()->asArray()->all() == NULL){
            $torrents1 = unserialize(file_get_contents(Yii::$app->basePath . '/web/db_data/torrent_array1.txt'));
            $torrents2 = unserialize(file_get_contents(Yii::$app->basePath . '/web/db_data/torrent_array2.txt'));

            $posts = Yii::$app->db->createCommand()->batchInsert('torrent_link', ['number', 'imdbID' ,'url','hash','quality','seeds','peers','size','size_bytes','date_uploaded','date_uploaded_unix'], $torrents1)->execute();
            $posts = Yii::$app->db->createCommand()->batchInsert('torrent_link', ['number', 'imdbID' ,'url','hash','quality','seeds','peers','size','size_bytes','date_uploaded','date_uploaded_unix'], $torrents2)->execute();

        }

    }

/**     MAIN PAGE , LOGIN , SIGNUP , FORGOT PASSWORD   */

    public function actionIndex()
    {
        $session = Yii::$app->session;

        $this->Dbcreat();

        $login = new Login();
        $signup = new Signup();
        $forgot = new Forgot();

        FileHelper::createDirectory("./photo");
        FileHelper::createDirectory("./films");

//        ----------------LOGIN-------------------------

        if ($login->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('Login');
            $my_request = Login::find()->asArray()->where(['user_email' => $post['user_email']])->all();

            if ($my_request) {
                if ($my_request[0]['user_password'] != NULL) {
                    if (Yii::$app->getSecurity()->validatePassword($post['user_password'], $my_request[0]['user_password'])) {

                        $session['loged_email'] = $post['user_email'];
                        $this->redirect('http://localhost:8080/hypertube/web/main');

                    } else {
                        Yii::$app->session->setFlash('error', 'The password you entered is invalid. Please try again');
                    }
                } elseif ($my_request[0]['user_facebook_id'] != NULL){
                    Yii::$app->session->setFlash('error', 'Please sign in with Facebook');
                }elseif ($my_request[0]['user_google_id'] != NULL){
                    Yii::$app->session->setFlash('error', 'Please sign in with Google');
                }elseif ($my_request[0]['user_intra_id'] != NULL){
                    Yii::$app->session->setFlash('error', 'Please sign in with Intra');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No such registered email address');
            }
        }
        //        ------------------SIGNUP-------------------------

        elseif ($signup->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('Signup');

            $my_request1 = Signup::find()->asArray()->where(['user_email' => $post['user_email']])->all();

            if ($my_request1 == NULL) {

                if ($signup->validate()) {

                    $signup->user_password = Yii::$app->getSecurity()->generatePasswordHash($signup->user_password);
                    $signup->user_rep_password = $signup->user_password;
                    $signup->user_avatar = "ninja.png";
                    $signup->user_avatar2 = "ninja.png";
                    $session['user_email'] = $post['user_email'];
                    $signup->user_name = str_replace('<', '&lt;',$signup->user_name );
                    $signup->user_name = str_replace('>', '&gt;',$signup->user_name );
                    $signup->user_secondname = str_replace('<', '&lt;',$signup->user_secondname );
                    $signup->user_secondname = str_replace('>', '&gt;',$signup->user_secondname );
                    $signup->save(false);

                    $this->redirect('http://localhost:8080/hypertube/web/main');

                } else {
                    Yii::$app->session->setFlash('error', 'Please fill in all the fields correctly');
                }
            } else  {
                    Yii::$app->session->setFlash('error', 'Such email already registered');
            }
        }
        //      --------------------------FORGOT_PASSWORD--------------

        elseif ($forgot->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('Forgot');
            $post = $post['user_email'];
            $my_request = User::find()->asArray()->where(['user_email' => $post])->one();

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
                    ->setTextBody("Your new password for Matcha is - " . $new_pass)
                    ->send();

                Yii::$app->session->setFlash('success', 'We send you an e-mail message. Please check your email for further instructions');
                return $this->refresh();

            } else {
                Yii::$app->session->setFlash('error', 'No such registered E-mail address');
            }
        }

        if ($session['language'] == 'ua'){
            return $this->render('index_ua', compact('login', 'signup', 'forgot'));
        }
        return $this->render('index', compact('login', 'signup', 'forgot'));
    }

/**     LOGIN WITH INTRA 42 API    */

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
                $user->user_avatar2 = $data->image_url;
                $user->user_intra_login = 1;
                $user->save(false);
            }
            $session['loged_email'] = $data->email;
            $this->redirect('http://localhost:8080/hypertube/web/main');
        }
    }

}
