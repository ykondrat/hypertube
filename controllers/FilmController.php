<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 17.09.17
 * Time: 15:24
 */

namespace app\controllers;

use app\controllers\SiteController;
use app\models\Comment;
use app\models\Forgot;
use app\models\Imdb;
use app\models\Login;
use app\models\Settings;
use app\models\Signup;
use app\models\Torrent;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use DOMDocument;
use linslin\yii2\curl;
class FilmController extends Controller
{
    public $layout = 'film';

    /** OPEN PAGE OF FILM */

    public function actionSend_to_node(){
        $session = Yii::$app->session;
        $post = Yii::$app->request->post('film_data');
        $post = explode(',', $post);
        $user = User::find()->where(['user_email' => $session['loged_email']])->asArray()->one();
        $film = Imdb::find()->where(['imdbID' => $post[0]])->asArray()->one();
        $torrent = Torrent::find()->where(['imdbID' => $post[0]])->andWhere(['number' => $post[1]])->asArray()->one();
        $data = json_encode( array($user, $film, $torrent));

        $curl = new curl\Curl();
        $response = $curl->setPostParams([
            'data' => $data,
        ])
            ->post('http://localhost:3000/get_info');
        if ($response == 'OK'){
            echo json_encode('OK');
        } else{
            echo json_encode('GAMNO');
        }
    }

    public function actionAddcomment(){

        $session = Yii::$app->session;
        $user = User::findOne(['user_email' => $session['loged_email']]);

        $text = Yii::$app->request->post('Text');
        $imdbID = Yii::$app->request->post('imdbID');

        Yii::$app->db->createCommand()->insert('comment', [
            'user_name' => $user->user_name,
            'user_secondname' => $user->user_secondname,
            'user_avatar' => $user->user_avatar,
            'text' => $text,
            'imdbID' => $imdbID,
        ])->execute();

    }

    public function get_comments($id){
        return    Comment::find()->orderBy(['id'=>SORT_DESC])->where(['imdbID' => $id])->all();
    }

    public function get_torrents($id){
        return    Torrent::find()->orderBy(['seeds'=>SORT_DESC])->where(['imdbID' => $id])->all();
    }



    public function actionFilm_page($id)
    {
        $session = Yii::$app->session;
        $user = User::findOne(['user_email' => $session['loged_email']]);

        $comments = new ArrayDataProvider([
            'allModels' => $this->get_comments($id),
            'pagination' => false,
        ]);
        $session['film_id'] = $id;
        $torrents = new ArrayDataProvider([
            'allModels' => $this->get_torrents($id),
            'pagination' => false,
        ]);

        if (Imdb::find()->where(['imdbID' => $id])->one()){

            $film = Imdb::find()->where(['imdbID' => $id])->one();
            return $this->render('film_page', compact('film', 'user', 'comments', 'torrents'));
        }
        else {
            return $this->render('//error/404');
        }

    }




}