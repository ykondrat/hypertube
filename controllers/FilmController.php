<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 17.09.17
 * Time: 15:24
 */

namespace app\controllers;
use \Done\Subtitles\Subtitles;
use Captioning\Format\SBVFile;
use app\models\Comment;
use app\models\Imdb;
use app\models\Imdb_ua;
use app\models\Torrent;
use app\models\User;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use linslin\yii2\curl;
use Captioning\Format\SubripFile;
use yii\helpers\FileHelper;

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
            ->post('http://localhost:8000/get_info');
        if ($response == 'OK'){
            $this->Down_sub($post[0].$post[1], $post[0]);
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


            if ($session['language'] == 'ua'){
                $film = Imdb_ua::find()->where(['imdbID' => $id])->one();
                return $this->render('film_page_ua', compact('film', 'user', 'comments', 'torrents'));
            }else {
                $film = Imdb::find()->where(['imdbID' => $id])->one();
                return $this->render('film_page', compact('film', 'user', 'comments', 'torrents'));
            }
        }
        else {
            return $this->render('//error/404');
        }

    }

    public function Down_sub($tt, $imdb){

        sleep(3);
        $path = '../node_server/public/films/'.$tt;
        $results = scandir($path);
        $folder = '';
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            if (is_dir($path . '/' . $result)) {
                $folder = $tt.'/'.$result;
            }
        }

        $sub_path = (new \yii\db\Query())->select(['path_url'])->from('subtitle')->where(['imdb_id' => $imdb])->all();
        $zip_pas = '../node_server/public/films/' . $folder . '/test.zip';

        foreach ($sub_path as $path) {

            $src = fopen($path['path_url'], 'r');
            $dest1 = fopen('../node_server/public/films/' . $folder . '/test.zip', 'w');

            stream_copy_to_stream($src, $dest1);

            $zip = new \ZipArchive();
            $res = $zip->open('../node_server/public/films/' . $folder . '/test.zip');
            if ($res === TRUE) {
                $zip->extractTo('../node_server/public/films/' . $folder . '/');
                $zip->close();
            }
            unlink($zip_pas);

        }
        $srt=FileHelper::findFiles('../node_server/public/films/'.$folder.'/', ['only'=>['*.srt']]);
        $sub_s = FileHelper::findFiles('../node_server/public/films/'.$folder.'/', ['only'=>['*.sub']]);
        foreach ($srt as $sub) {
            try{

                Subtitles::convert($sub, substr($sub, 0, -4) . '.vtt');

            } catch(\Exception $e) {

            }
            unlink($sub);

        }
        foreach ($sub_s as $sub) {
            unlink($sub);
        }


    }

    public function actionSet_done(){
        $post = Yii::$app->request->post('done');
        $data = explode(',', $post);
        $torrent = Torrent::find()->where(['imdbID' => $data[0]])->andWhere(['number' => $data[1]])->one();
        $torrent->torent_done = 'done';
        $torrent->time_upload = time();
        $torrent->torrent_path = $data[2].$data[0].$data[1];
        $torrent->save();

    }



}