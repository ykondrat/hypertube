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

class FilmController extends Controller
{


    public function actionTest(){
        $title_array = array();
        $titles = Imdb::find()->select('Title')->asArray()->all();
        foreach ($titles as $title){

            $title_array[] = str_replace(' ', '-', mb_strtolower( $title['Title']));

        }
        var_dump($title_array);
        die();
    }

    public $layout = 'film';

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