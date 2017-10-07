<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 27.09.17
 * Time: 10:01
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
use Buzz\Browser;
use MatthiasNoback\MicrosoftOAuth\AzureTokenProvider;
use MatthiasNoback\MicrosoftTranslator\MicrosoftTranslator;

class FunctionController extends Controller
{


    public function actionTest(){
        $browser = new Browser();

        $azureKey = '797be17c8efd43c4b5bb0277db5b8fc9';

        $accessTokenProvider = new AzureTokenProvider($browser, $azureKey);

        $translator = new MicrosoftTranslator($browser, $accessTokenProvider);

        $imdb_en = Imdb::find()->asArray()->all();

        $i = 0;
        while ($i < 1000){

            $film_array = array("Title" => $translator->translate($imdb_en[$i]['Title'], 'uk', ''), "Year" => $imdb_en[$i]['Year'], "Released" => $imdb_en[$i]['Released'], "Runtime" => $imdb_en[$i]['Runtime'],
                "Genre" => $imdb_en[$i]['Genre'], "Director" => $imdb_en[$i]['Director'], "Writer" => $imdb_en[$i]['Writer'], "Actors" => $imdb_en[$i]['Actors'],
                "Plot" =>  $translator->translate($imdb_en[$i]['Plot'], 'uk', ''), "Language" => $imdb_en[$i]['Language'], "Country" => $imdb_en[$i]['Country'], "Awards" => $imdb_en[$i]['Awards'],
                "Poster" => $imdb_en[$i]['Poster'], "Metascore" => $imdb_en[$i]['Metascore'], "imdbRating" => $imdb_en[$i]['imdbRating'], "imdbID" => $imdb_en[$i]['imdbID'],
                "Production" => $imdb_en[$i]['Production']);

            Yii::$app->db->createCommand()->insert('imdb_id_ua', $film_array)->execute();
            $i++;
        }

    }

    /** GET TBODY FROM DOM_DOCUMENT */

    public function getTbody($path){
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $htmlString = curl_exec($ch);
        curl_close($ch);

        if (isset($htmlString)) {
            $html = new DOMDocument();
            @ $html->loadHTML($htmlString);
            return $html->getElementsByTagName('tbody');
        }
        else{
            return NULL;
        }
    }

    /** GET LOAD_HTML FROM DOM_DOCUMENT */

    public function getHtml($path){
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $htmlString = curl_exec($ch);
        curl_close($ch);

        if (isset($htmlString)) {
            $html = new DOMDocument();
            @ $html->loadHTML($htmlString);
            return $html;
        }
        else{
            return NULL;
        }
    }

    /** PARSE PODNAPISI_NET FOR SUBTITLE */

    public function podnapisi_net($some_sub){

        $language_parse = ($some_sub['language'] == 'en') ? "Английский" : ($some_sub['language'] == 'ru') ? 'Русский' : 'Украинский';

        $film = Imdb::find()->where(['imdbID' => $some_sub['imdb_id']])->one();

        $title = str_replace(' ', '+', $film->Title);
        $year = $film->Year;

        $tbodys = $this->getTbody('https://www.podnapisi.net/subtitles/search/advanced?keywords='.$title.'&year='.$year);

        if ($tbodys != NULL) {

            foreach ($tbodys as $tbody) {

                foreach ($tbody->getElementsByTagName('tr') as $tr) {
                    if ($tr->getElementsByTagName('td')[3]->getElementsByTagName('abbr')[0]->getAttribute('data-title') == $language_parse ) {
                        $some_sub['path_url'] = 'https://www.podnapisi.net'.$tr->getElementsByTagName('td')[0]->getElementsByTagName('a')[0]->getAttribute('href');
                        $some_sub['path_folder'] = 'films/'.substr($some_sub['imdb_id'], 2).'/subtitle/'.$tr->getElementsByTagName('td')[0]->getElementsByTagName('span')[1]->nodeValue.'srt';
                        $some_sub['is_full'] = true;
                        break;
                    }
                }
            }
            return $some_sub;
        }
        else {
            return NULL;
        }
    }

    /** PARSE YIFY FOR SUBTITLE AND ADD TO TABLE IN DATABASE */

    public function actionAdd_subtitle(){

        $ids = unserialize(file_get_contents('db_data/id(top 1000).php'));
        foreach ($ids as $id) {

            $title = '';
            $en_sub = array( 'imdb_id' => 'tt'.$id , 'language' => 'en', 'path_url' => '', 'path_folder' => '',  'is_full' => false );
            $ru_sub = array( 'imdb_id' => 'tt'.$id , 'language' => 'ru', 'path_url' => '', 'path_folder' => '',  'is_full' => false);
            $ua_sub = array( 'imdb_id' => 'tt'.$id , 'language' => 'ua', 'path_url' => '', 'path_folder' => '',  'is_full' => false );

            $tbodys = $this->getTbody('http://www.yifysubtitles.com/movie-imdb/tt' . $id);

            if ($tbodys != NULL) {

                $html = $this->getHtml('http://www.yifysubtitles.com/movie-imdb/tt' . $id);
                foreach ($html->getElementsByTagName('a') as $a) {
                    if (strstr($a->getAttribute('href'), '/movie-imdb/tt' . $id)) {
                        $title = mb_strtolower(str_replace(' ', '-', $a->nodeValue));
                    }
                }

                foreach ($tbodys as $tbody) {

                    foreach ($tbody->getElementsByTagName('tr') as $tr) {
                        if ($tr->getElementsByTagName('td')[1]->getElementsByTagName('span')[1]->nodeValue == 'English' && $tr->getElementsByTagName('td')[0]->getElementsByTagName('span')[0]->nodeValue >= 0 && $en_sub['is_full'] != true) {
                            $en_sub['path_url'] = 'http://www.yifysubtitles.com/subtitle/' . $title . '-english-yify-' . $tr->getAttribute('data-id') . '.zip';
                            $en_sub['path_folder'] = 'films/'.$id.'/subtitle/'.$title.'-yify-english.srt';
                            $en_sub['is_full'] = true;
                        }
                        if ($tr->getElementsByTagName('td')[1]->getElementsByTagName('span')[1]->nodeValue == 'Russian' && $tr->getElementsByTagName('td')[0]->getElementsByTagName('span')[0]->nodeValue >= 0 && $ru_sub['is_full'] != true) {
                            $ru_sub['path_url'] = 'http://www.yifysubtitles.com/subtitle/' . $title . '-russian-yify-' . $tr->getAttribute('data-id') . '.zip';
                            $ru_sub['path_folder'] = 'films/'.$id.'/subtitle/'.$title.'-yify-russian.srt';
                            $ru_sub['is_full'] = true;
                        }
                    }
                }
                if ($en_sub['is_full'] == false){
                    $en_sub = $this->podnapisi_net($en_sub);
                }
                if ($ru_sub['is_full'] == false){
                    $ru_sub = $this->podnapisi_net($ru_sub);
                }
                if ($ua_sub['is_full'] == false){
                    $ua_sub = $this->podnapisi_net($ua_sub);
                }
            }
            else{
                if ($en_sub['is_full'] == false){
                    $en_sub = $this->podnapisi_net($en_sub);
                }
                if ($ru_sub['is_full'] == false){
                    $ru_sub = $this->podnapisi_net($ru_sub);
                }
                if ($ua_sub['is_full'] == false){
                    $ua_sub = $this->podnapisi_net($ua_sub);
                }
            }
            if ($en_sub['is_full'] != false){
                Yii::$app->db->createCommand()->insert('subtitle', [
                    'imdb_id' => $en_sub['imdb_id'],
                    'language' => $en_sub['language'],
                    'path_url' => $en_sub['path_url'],
                    'path_folder' => $en_sub['path_folder'],
                ])->execute();
            }
            if ($ru_sub['is_full'] != false){
                Yii::$app->db->createCommand()->insert('subtitle', [
                    'imdb_id' => $ru_sub['imdb_id'],
                    'language' => $ru_sub['language'],
                    'path_url' => $ru_sub['path_url'],
                    'path_folder' => $ru_sub['path_folder'],
                ])->execute();
            }
            if ($ua_sub['is_full'] != false){
                Yii::$app->db->createCommand()->insert('subtitle', [
                    'imdb_id' => $ua_sub['imdb_id'],
                    'language' => $ua_sub['language'],
                    'path_url' => $ua_sub['path_url'],
                    'path_folder' => $ua_sub['path_folder'],
                ])->execute();
            }
        }
    }

    /**     PARSE "thepiratebay.org"   */

    public function parseThepiratebay()
    {
        $ids = unserialize(file_get_contents('db_data/id(top 1000).php'));
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
                                if (strstr($div->getElementsByTagName('a')[0]->nodeValue, '720p')){
                                    $quality = '720p';
                                }
                                elseif (strstr($div->getElementsByTagName('a')[0]->nodeValue, '1080p')){
                                    $quality = '720p';
                                }
                                else{
                                    $quality = 'HD';
                                }
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

    /**     PARSE "yts.ag"    */

    public function parseYTS(){
        $imdb_ids = Imdb::find()->select('imdbID')->asArray()->all();
        $array = array();
        $i = 900;
        while ($i < 1000){
            $id = $imdb_ids[$i];
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
            var_dump($i);
            $i++;
        }
        $posts = Yii::$app->db->createCommand()->batchInsert('torrent_link', ['imdbID' ,'url','hash','quality','seeds','peers','size','size_bytes','date_uploaded','date_uploaded_unix'], $array)->execute();
    }

    /**     GET FULL INFORMATION FROM OMDB   */

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

    /**     PARSE IMDB TOP 1000   */

    public function getTOP1000IMDB()
    {
        $ch = curl_init('http://www.imdb.com/search/title?count=1000&groups=top_1000&sort=user_rating');
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
            foreach ($ids as $film) {
                $film = $this->getFullInformation($film);
                Yii::$app->db->createCommand()->insert('imdb_id', $film)->execute();
            }
        }
    }

}