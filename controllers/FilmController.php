<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 17.09.17
 * Time: 15:24
 */

namespace app\controllers;

use app\controllers\SiteController;
use app\models\Forgot;
use app\models\Imdb;
use app\models\Login;
use app\models\Settings;
use app\models\Signup;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use DOMDocument;

class FilmController extends Controller
{
    public $layout = 'film';
/**
 * CREATE TABLE IF NOT EXISTS `subtitle` (
`number` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT,
`imdb_id` VARCHAR (100) ,
`language` VARCHAR (100) ,
`path_url` VARCHAR (100) ,
`path_folder` VARCHAR (255),*/

    public function podnapisi_net(){

    }

    public function actionTest(){  /**      yify       */

        $ids = unserialize(file_get_contents('db_data/id(top 1000).php'));
        foreach ($ids as $id) {
            $ch = curl_init('http://www.yifysubtitles.com/movie-imdb/tt' . $id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $htmlString = curl_exec($ch);
            curl_close($ch);

            $en_sub = array( 'imdb_id' => 'tt'.$id , 'language' => 'en', 'path_url' => '', 'path_folder' => '', 'title' => '' , 'is_full' => false );
            $ru_sub = array( 'imdb_id' => 'tt'.$id , 'language' => 'ru', 'path_url' => '', 'path_folder' => '', 'title' => '' , 'is_full' => false);
            $ua_sub = array( 'imdb_id' => 'tt'.$id , 'language' => 'ua', 'path_url' => '', 'path_folder' => '', 'title' => '' , 'is_full' => false );

            if (isset($htmlString)) {
                $html = new DOMDocument();
                @ $html->loadHTML($htmlString);
                $tbodys = $html->getElementsByTagName('tbody');

                if ($tbodys != NULL) {

                    foreach ($html->getElementsByTagName('a') as $a) {
                        if (strstr($a->getAttribute('href'), '/movie-imdb/tt' . $id)) {
                            $ru_sub['title'] = $en_sub['title'] = $ua_sub['title'] = mb_strtolower(str_replace(' ', '-', $a->nodeValue));
                        }
                    }

                    foreach ($tbodys as $tbody) {

                        foreach ($tbody->getElementsByTagName('tr') as $tr) {
                            if ($tr->getElementsByTagName('td')[1]->getElementsByTagName('span')[1]->nodeValue == 'English' && $tr->getElementsByTagName('td')[0]->getElementsByTagName('span')[0]->nodeValue >= 0) {
                                $en_sub['path_url'] = 'http://www.yifysubtitles.com/subtitle/' . $en_sub['title'] . '-english-yify-' . $tr->getAttribute('data-id') . '.zip';
                                $en_sub['path_folder'] = 'films/'.$id.'/subtitle/'.$en_sub['title'].'-yify-english.srt';
                                $en_sub['is_full'] = true;
                            }
                            if ($tr->getElementsByTagName('td')[1]->getElementsByTagName('span')[1]->nodeValue == 'Russian' && $tr->getElementsByTagName('td')[0]->getElementsByTagName('span')[0]->nodeValue >= 0) {
                                $ru_sub['path_url'] = 'http://www.yifysubtitles.com/subtitle/' . $ru_sub['title'] . '-russian-yify-' . $tr->getAttribute('data-id') . '.zip';
                                $ru_sub['path_folder'] = 'films/'.$id.'/subtitle/'.$ru_sub['title'].'-yify-russian.srt';
                                $ru_sub['is_full'] = true;
                            }
                        }
                    }
                    if ($en_sub['is_full'] == false){

                    }
                    if ($ru_sub['is_full'] == false){

                    }
                    if ($ua_sub['is_full'] == false){

                    }
                }
                else{
                    //   ПАРСИМ ІНШИЙ САЙТ
                }
            }
        }
    }
//http://www.yifysubtitles.com/subtitle/the-shawshank-redemption-dutch-yify-8979.zip

    /** RETURN JSON OBJECT OF FILM */

    function getFullInformation($Imdb_id){

        $test =  file_get_contents("http://www.omdbapi.com/?apikey=8f911d74&i=".$Imdb_id);
        $json_film = json_decode($test);
        $film = array("Title" => $json_film->Title, "Year" => $json_film->Year, "Released" => $json_film->Released, "Runtime" => $json_film->Runtime,
            "Genre" => $json_film->Genre, "Director" => $json_film->Director, "Writer" => $json_film->Writer, "Actors" => $json_film->Actors,
            "Plot" =>  $json_film->Plot, "Language" => $json_film->Language, "Country" => $json_film->Country, "Awards" => $json_film->Awards,
            "Poster" => $json_film->Poster, "Metascore" => $json_film->Metascore, "imdbRating" => $json_film->imdbRating, "imdbID" => $json_film->imdbID,
            "Production" => $json_film->Production);
        return $film;

    }

    /** OPEN PAGE OF FILM */

    public function actionFilm_page($id)
    {
        $session = Yii::$app->session;
        $user = User::findOne(['user_email' => $session['loged_email']]);

        if (Imdb::find()->where(['imdbID' => $id])->one()){
            $film = Imdb::find()->where(['imdbID' => $id])->one();
        }
        else {
            $new_film = $this->getFullInformation($id);
            Yii::$app->db->createCommand()->insert('imdb_id', $new_film)->execute();
            $film = Imdb::find()->where(['imdbID' => $id])->one();
        }
        return $this->render('film_page', compact('film', 'user'));
    }
}