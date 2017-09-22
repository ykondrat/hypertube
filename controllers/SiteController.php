<?php

namespace app\controllers;


use app\models\Forgot;
use app\models\Genre;
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
use yii\helpers\FileHelper;
use Composer\Autoload;
use DOMXPath;



class SiteController extends Controller
{

    public function actionTwo()
    {
        $ids = unserialize(file_get_contents('id(top 1000).php'));
        foreach ($ids as $id) {
            $ch = curl_init('https://thepiratebay.org/search/tt' . $id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $htmlString = curl_exec($ch);
            curl_close($ch);
            $magnet = '';
            $seeds = '';
            $peers = '';
            $size = '';
            $quality = '';
            $uploaded = '';
            if (isset($htmlString)) {
                $html = new DOMDocument();
                @ $html->loadHTML($htmlString);
                $k = 0;
                $array = array();
                foreach ($html->getElementsByTagName('tr') as $link) {

                    $i = 0;
                    foreach ($link->getElementsByTagName('td') as $td) {

                        if ($i == 0) {
                            if (!strstr($td->getElementsByTagName('a')[1]->nodeValue, "HD")) {
                                break;
                            }
                        }
                        if ($i == 1) {
                            foreach ($td->getElementsByTagName('div') as $div) {
                                $p = (strstr($div->getElementsByTagName('a')[0]->nodeValue, '720p')) ? 4 : 5;
                                $quality = substr($div->getElementsByTagName('a')[0]->nodeValue, strripos($div->getElementsByTagName('a')[0]->nodeValue, "0p") - $p + 2, $p);
                            }

                            foreach ($td->getElementsByTagName('font') as $font) {
                                $size_string = $font->nodeValue;
                                $size_array = explode(',', $size_string);
                                $size = substr($size_array[1], 5);
                                $uploaded = substr($size_array[0], 9);
                            }
                            foreach ($td->getElementsByTagName('a') as $a) {
                                if (strstr($a->getAttribute('href'), 'magnet')) {

                                }
                                if (strstr($a->getAttribute('href'), 'magnet')) {
                                    $magnet = $a->getAttribute('href');
                                }
                            }
                            if ($magnet == NULL) {
                                break;
                            }
                        }
                        if ($i == 2) {
                            $seeds = $td->nodeValue;
                        }
                        if ($i == 3) {
                            $peers = $td->nodeValue;
                        }
                        $i++;
                        if ($magnet && $quality && $seeds && $peers && $size && $uploaded) {
                            $array[] = array("imdbID" => 'tt' . $id, "url" => $magnet, "quality" => $quality, "seeds" => $seeds, "peers" => $peers,
                                "size" => $size, "date_uploaded" => $uploaded);
                            $k++;
                            $magnet = '';$seeds = '';$peers = '';$size = '';$quality = '';$uploaded = '';
                        }
                    }
                    if ($k > 3) {
                        break;
                    }
                }
                unset($array[0]);
                $posts = Yii::$app->db->createCommand()->batchInsert('torrent_link', ['imdbID' ,'url','quality','seeds','peers','size','date_uploaded'], $array)->execute();

            }
        }
    }


    public function actionTest(){
        $imdb_ids = Imdb::find()->select('imdbID')->asArray()->all();
        $array = array();
        foreach ($imdb_ids as $id){
            $data = file_get_contents('https://yts.ag/api/v2/list_movies.json?query_term='.$id['imdbID']);
            $data = json_decode($data);

            $test = $data->data;

        if (property_exists($test, 'movies')){

            $torrent_arr = $data->data->movies[0]->torrents;

            foreach ($torrent_arr as $torrent) {

                $array[] = array("imdbID" => $id['imdbID'], "url" => $torrent->url,"hash" => $torrent->hash,"quality" => $torrent->quality,"seeds" => $torrent->seeds,"peers" => $torrent->peers,
                    "size" => $torrent->size,"size_bytes" => $torrent->size_bytes,"date_uploaded" => $torrent->date_uploaded,"date_uploaded_unix" => $torrent->date_uploaded_unix);
            }
        }
        }
        $posts = Yii::$app->db->createCommand()->batchInsert('torrent_link', ['imdbID' ,'url','hash','quality','seeds','peers','size','size_bytes','date_uploaded','date_uploaded_unix'], $array)->execute();
    }

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

    public function Dbcreat(){
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

        $genre_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `genre` (
          `genre` VARCHAR (100) NOT NULL ,
          PRIMARY KEY (`genre`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $genre_table->query();

        $torrent_link_table = Yii::$app->db->createCommand('
          CREATE TABLE IF NOT EXISTS `torrent_link` (
          `number` INT (10) UNSIGNED NOT NULL AUTO_INCREMENT,
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
          PRIMARY KEY (`number`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        $torrent_link_table->query();

        if (Imdb::find()->asArray()->all() == NULL){
            require_once Yii::$app->basePath . '/web/imdb_id(1000).php';

            $imdb_add = Yii::$app->db->createCommand()->batchInsert('imdb_id', ['number' ,'imdbID','Title','Year','Runtime','Released','Genre','Director','Writer','Actors'
                ,'Plot','Language','Country','Awards','Poster','Metascore','imdbRating','Production',], $imdb_id_array)->execute();

        }
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
    }

    public function actionIndex()
    {
        $session = Yii::$app->session;

        $this->Dbcreat();

        $login = new Login();
        $signup = new Signup();
        $forgot = new Forgot();

        FileHelper::createDirectory("./photo");
//        ----------------LOGIN-------------------------

        if ($login->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('Login');
            $my_request = Login::find()->asArray()->where(['user_email' => $post['user_email']])->all();
            if ($my_request) {
                if (Yii::$app->getSecurity()->validatePassword($post['user_password'], $my_request[0]['user_password'])) {
                    $session['user_email'] = $post['user_email'];
                    $this->redirect('http://localhost:8080/hypertube/web/main');
                } else {
                    Yii::$app->session->setFlash('error', 'The password you entered is invalid. Please try again');
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
                $user->user_avatar2 = $data->image_url;

                $user->save(false);
            }
            $session['loged_email'] = $data->email;
            $this->redirect('http://localhost:8080/hypertube/web/main');
        }
    }

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

//        $ch = curl_init('http://www.imdb.com/search/title?count=1000&groups=top_1000&sort=user_rating');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $htmlString = curl_exec($ch);
//        curl_close($ch);
//        if (isset($htmlString)) {
//            $html = new DOMDocument();
//            @ $html->loadHTML($htmlString);
//            $ids = array();
//            foreach ($html->getElementsByTagName('a') as $link) {
//                $url = $link->getAttribute('href');
//                if (strstr($url, '/title/tt') && !(in_array(substr($url, 9, 7), $ids))) {
//                    $ids[] = substr($url, 9, 7);
//                }
//            }
////                      $ids = array_slice($ids, 24);
//            foreach ($ids as $film) {
//                $i = 0;
//                while ($i < 10000000) {
//                    $i++;
//                }
//                $film = $this->getFullInformation($film);
//                Yii::$app->db->createCommand()->insert('imdb_id', $film)->execute();
//                $i = 0;
//                while ($i < 10000000) {
//                    $i++;
//                }
//            }
//        }



    }
}
