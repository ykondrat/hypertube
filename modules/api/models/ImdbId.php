<?php

namespace app\modules\api\models;

use Yii;

class ImdbId extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'imdb_id';
    }

    public function rules()
    {
        return [
            [['imdbID', 'Runtime'], 'string', 'max' => 15],
            [['Title', 'Released', 'Actors'], 'string', 'max' => 255],
            [['Year', 'Metascore'], 'string', 'max' => 5],
            [['Genre'], 'string', 'max' => 50],
            [['Director', 'Country', 'Poster'], 'string', 'max' => 500],
            [['Writer', 'Plot', 'Language', 'Awards', 'Production'], 'string', 'max' => 1000],
            [['imdbRating'], 'string', 'max' => 10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' => 'Number',
            'imdbID' => 'Imdb ID',
            'Title' => 'Title',
            'Year' => 'Year',
            'Runtime' => 'Runtime',
            'Released' => 'Released',
            'Genre' => 'Genre',
            'Director' => 'Director',
            'Writer' => 'Writer',
            'Actors' => 'Actors',
            'Plot' => 'Plot',
            'Language' => 'Language',
            'Country' => 'Country',
            'Awards' => 'Awards',
            'Poster' => 'Poster',
            'Metascore' => 'Metascore',
            'imdbRating' => 'Imdb Rating',
            'Production' => 'Production',
        ];
    }

    /** Return list of all films ('imdbID', 'Title' ,'Year' ,'Runtime' ,'Released','Genre' ,
     * 'Director' ,'Writer' ,'Actors' ,'Plot' ,'Language' ,'Country' ,'Awards' ,'Poster' ,
     * 'Metascore' ,'imdbRating' ,'Production' .
     * Parameter $args = [NULL] */

    function GetFilmList(array $args, $check){
        if ($check == true) {
            return (count($args) == 0 ) ? true : false;
        }
        else {
            return self::find()->all();
        }
    }

    /** Return film information  by `ImdbID`. Parameter $args = [<ImdbID's>] */

    function GetFilmByImdbId(array $args, $check){
        if ($check == true) {
            $str = 0;
            foreach ($args as $id){
                $int = (is_string($id) && strlen($id) == 9 && preg_match('/^tt[0-9]{7}/', $id)) ? $str + 1 : $str;
            }
            return (count($args) > 0 && $str == count($args)) ? true : false;
        }
        else {
            return self::find()->where(['imdbID' => $args])->all();
        }
    }

    /** Search films by title and return information about them . Parameter $args = [<title of film or part of it>] */

    function SearchFilmByTitle(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->where(['like', 'Title', $args[0]])->all();
        }
    }

    /** Search films by Year and return information about them . Parameter $args = [<Year>] */

    function SearchFilmByYear(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_int($args[0])) ? true : false;
        }
        else {
            return self::find()->where(['like', 'Year', $args[0]])->all();
        }
    }

    /** Search films by genre and return information about them . Parameter $args = [<genre of film>] */

    function SearchFilmByGenre(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->where(['like', 'Genre', $args[0]])->all();
        }
    }

    /** Search films by actor name or surname and return information about them . Parameter $args = [<name or surname of actor>] */

    function SearchFilmByActor(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_string($args[0])) ? true : false;
        }
        else {
            return self::find()->where(['like', 'Actors', $args[0]])->all();
        }
    }

    /** Search films by Imdb rating and return information about them . Parameter $args = [<imdb rating (float)>] */

    function SearchFilmByRating(array $args, $check){
        if ($check == true) {
            return (count($args) == 1 && is_float($args[0])) ? true : false;
        }
        else {
            return self::find()->where([ 'imdbRating' => $args[0]])->all();
        }
    }

}
