<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 16.09.17
 * Time: 12:51
 */

namespace app\controllers;

use app\models\Forgot;
use app\models\Genre;
use app\models\Imdb;
use app\models\Login;
use app\models\Settings;
use app\models\Signup;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;

use yii\web\UploadedFile;

class MainController extends Controller
{
    public $layout = 'my_main';

    public function get_Genre(){

        $genre_string = '';
        $genres = Imdb::find()->select('Genre')->all();
        foreach ($genres as $genre){
            $genre_string = $genre_string.', '.$genre->Genre;
        }
        $genre_array = explode(', ', $genre_string);
        $genre_array = array_unique($genre_array);
        sort($genre_array, SORT_STRING);
        unset($genre_array[0]);
        array_unshift($genre_array, "All");

        return $genre_array;
}

    public function actionMain()
    {
        $session = Yii::$app->session;
        if (!(isset($session['loged_email']))){
            $this->actionLogout();
        }else {
            $user = User::findOne(['user_email' => $session['loged_email']]);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $this->get_Genre(),
                'pagination' => false,
            ]);

            return $this->render('main', compact('user', 'dataProvider'));
        }
    }

    public function actionReturngenre(){

        $post = Yii::$app->request->post('Genre');
        $offset = Yii::$app->request->post('limit') * 10;

        if ($post != "All") {
            $films = Imdb::find()->where(['like', 'Genre', $post])->asArray()->limit(10)->offset($offset)->all();
            $if_end = Imdb::find()->where(['like', 'Genre', $post])->asArray()->limit(11)->offset($offset)->all();
        } else {
            $films = Imdb::find()->asArray()->limit(10)->offset($offset)->all();
            $if_end = Imdb::find()->asArray()->limit(11)->offset($offset)->all();
        }

        $films[] = (count($if_end) > count($films)) ? "OK" : "END";

        echo json_encode($films);
    }

    public function actionGet_look_for()
    {
        $session = Yii::$app->session;
        $look_for = array();
        $look_for[] = (isset($session['limit'])) ? ['limit' => $session['limit']] : ['limit' => '1'];
        $look_for[] = (isset($session['genre'])) ? ['genre' => $session['genre']] : ['genre' => 'All'];
        $look_for[] = (isset($session['searchValue'])) ? ['searchValue' => $session['searchValue']] : ['searchValue' => ''];

        echo json_encode($look_for);
    }

    public function actionSend_look_for()
    {
        $post = Yii::$app->request->post();
        $session = Yii::$app->session;
        $session['genre'] = $post['genre'];
        $session['searchValue'] = $post['searchValue'];
        $session['limit'] = $post['limit'];

    }

    public function actionSettings()
    {
        $session = Yii::$app->session;
        $user = Settings::findOne(['user_email' => $session['loged_email']]);
        $flag = ($user->user_rep_password != NULL) ? 1 : 0;

        if ($user->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('Settings');


            $my_request1 = Settings::find()->asArray()->where(['user_email' => $post['user_email']])->one();


            if ($my_request1 != NULL) {
                $user_email = $my_request1['user_email'];
            } else {
                $user_email = '';
            }

            if ($user->validate()) {

                if ($my_request1 == NULL || $user_email == $user->user_email) {

                    if ($post['user_password'] != '' && $flag == 0) {
                        $user->user_password = Yii::$app->getSecurity()->generatePasswordHash($post['user_password']);
                        $user->user_rep_password = $user->user_password;
                    } elseif ($user->user_password == '' && $flag == 1){
                        $user->user_password = $user->user_rep_password;
                    }

                    if (array_key_exists( "Settings" , $_FILES) && $_FILES['Settings']['name']['user_avatar']) {

                        $user->user_avatar = UploadedFile::getInstance($user, 'user_avatar');

                        $user->user_avatar->saveAs('photo/' . $user->user_id . "." . $user->user_avatar->extension);
                        $user->user_avatar = 'photo/' . $user->user_id . "." . $user->user_avatar->extension;
                        $user->user_avatar2 = $user->user_avatar;

                    }
                    else{
                        $user->user_avatar = $user->user_avatar2;
                    }

                    $session['loged_email'] = $user->user_email;
                    $user->save(false);
                    return $this->refresh();

                }
                else {
                    Yii::$app->session->setFlash('error', 'Such email already registered');
                }
            }
            else {
                Yii::$app->session->setFlash('error', 'Please fill in all the fields correctly');
            }
        }
        else {
            $user->user_password = '';
        }

        return $this->render('settings',compact('user'));
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->destroy();
        $session->close();
        $this->redirect('http://localhost:8080/hypertube/web/index');
    }
}