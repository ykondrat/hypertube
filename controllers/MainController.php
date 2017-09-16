<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 16.09.17
 * Time: 12:51
 */

namespace app\controllers;

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

use yii\web\UploadedFile;

class MainController extends Controller
{
    public $layout = 'my_main';



    public function actionMain()
    {
        $genre_string = '';
        $genres = Imdb::find()->select('Genre')->all();
        $session = Yii::$app->session;
        $user = User::findOne(['user_email' => $session['loged_email']]);

        foreach ($genres as $genre){
            $genre_string = $genre_string.', '.$genre->Genre;
        }
        $genre_array = explode(', ', $genre_string);
        $genre_array = array_unique($genre_array);
        sort($genre_array, SORT_STRING);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $genre_array,
            'pagination' => false,
        ]);

        return $this->render('main', compact('user', 'dataProvider'));
    }

    public function actionSettings()
    {
        $session = Yii::$app->session;
        $user = Settings::findOne(['user_email' => $session['loged_email']]);
        $flag = ($user->user_rep_password != NULL) ? 1 : 0;

        if ($user->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('Settings');

            $my_request = Settings::find()->asArray()->where(['user_login' => $post['user_login']])->one();
            $my_request1 = Settings::find()->asArray()->where(['user_email' => $post['user_email']])->one();

            if ($my_request != NULL) {
                $user_login = $my_request['user_login'];
            } else {
                $user_login = '';
            }
            if ($my_request1 != NULL) {
                $user_email = $my_request1['user_email'];
            } else {
                $user_email = '';
            }

            if ($user->validate()) {

                if ($my_request == NULL || $user_login == $user->user_login) {

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
                    Yii::$app->session->setFlash('error', 'Such login already registered');
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
        $this->redirect('http://localhost:8080/hypertube/web/index');
    }
}