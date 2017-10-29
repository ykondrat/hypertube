<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 16.09.17
 * Time: 12:51
 */

namespace app\controllers;

use app\models\Imdb;
use app\models\Settings;
use app\models\User;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;


class MainController extends Controller
{
    public $layout = 'my_main';

    public function actionTest(){
        echo sha1(file_get_contents('https://yts.ag/torrent/download/FFA501CDB8239F6975D8EAAB3D9C07B7A48E6D04'));
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
            if ($session['language'] == 'ua'){
                return $this->render('main_ua', compact('user', 'dataProvider'));
            }
            return $this->render('main', compact('user', 'dataProvider'));
        }
    }

    public function actionSort_filter(){
        $session = Yii::$app->session;
        $like = (isset($session['searchValue']) && $session['searchValue'] != '') ? 'Title' : 'Genre';
        $like_value = (isset($session['searchValue']) && $session['searchValue'] != '') ? $session['searchValue'] : $session['genre'];
        $offset = Yii::$app->request->post('limit') * 10;
        $like_array = ($like == 'Genre' && ($like_value == 'All' || $like_value == '') ) ? "number>0" : array('like',$like, $like_value);
        $sort_set = (Yii::$app->request->post('sort_value') != '') ? Yii::$app->request->post('sort_value') : Yii::$app->request->post('sort');
        $filter_set = (Yii::$app->request->post('filter_value') != '') ? Yii::$app->request->post('filter_value') : Yii::$app->request->post('filter');
        if ($sort_set != ''){
            $sort_by = explode(',', $sort_set)[0];
            $sort_how = explode(',', $sort_set)[1];
            $sort_array = ($sort_how == "desc") ? array($sort_by => SORT_DESC) : array($sort_by => SORT_ASC);
        }
        else{
            $sort_array = array('imdbRating' => SORT_DESC);
        }
        if ( $filter_set != '' ){
            $filter_year = explode(',', $filter_set)[0];
            $filter_year_from = explode(',', $filter_set)[1];
            $filter_year_to = explode(',', $filter_set)[2];
            $year_from_string = "$filter_year>=$filter_year_from";
            $year_to_string = "$filter_year<=$filter_year_to";

            $filter_rating = explode(',', $filter_set)[3];
            $filter_rating_from = explode(',', $filter_set)[4];
            $filter_rating_to = explode(',', $filter_set)[5];
            $rating_from_string = "$filter_rating>=$filter_rating_from";
            $rating_to_string = "$filter_rating<=$filter_rating_to";
        }
        else{
            $rating_from_string = "imdbRating>=0";
            $rating_to_string = "imdbRating<=10";
            $year_from_string = "Year>=1920";
            $year_to_string = "Year<=2017";
        }

        $film = Imdb::find()->orderBy($sort_array)->where($like_array)->andWhere($year_from_string)->andWhere($year_to_string)->andWhere($rating_from_string)->andWhere($rating_to_string)->asArray()->limit(10)->offset($offset)->all();
        $if_end = Imdb::find()->orderBy($sort_array)->where($like_array)->andWhere($year_from_string)->andWhere($year_to_string)->andWhere($rating_from_string)->andWhere($rating_to_string)->asArray()->limit(11)->offset($offset)->all();

        $film[] = (count($if_end) > count($film)) ? "OK" : "END";

        echo json_encode($film);
    }

    /** SEND DATA OF SEARCH PARAM TO JS */

    public function actionGet_look_for()
    {
        $session = Yii::$app->session;
        $look_for = array();
        $look_for[] = (isset($session['limit'])) ? ['limit' => $session['limit']] : ['limit' => '1'];
        $look_for[] = (isset($session['genre'])) ? ['genre' => $session['genre']] : ['genre' => 'All'];
        $look_for[] = (isset($session['searchValue'])) ? ['searchValue' => $session['searchValue']] : ['searchValue' => ''];
        $look_for[] = (isset($session['sort_value'])) ? ['sort_value' => $session['sort_value']] : ['sort_value' => ''];
        $look_for[] = (isset($session['filter_value'])) ? ['filter_value' => $session['filter_value']] : ['filter_value' => ''];
        echo json_encode($look_for);
    }

    /** GET DATA OF SEARCH PARAM FROM JS */

    public function actionSend_look_for()
    {
        $post = Yii::$app->request->post();
        $session = Yii::$app->session;
        $session['genre'] = $post['genre'];
        $session['searchValue'] = $post['searchValue'];
        $session['limit'] = $post['limit'];
        $session['sort_value'] = $post['sort_value'];
        $session['filter_value'] = $post['filter_value'];
    }

    /** RETURN ARRAY OF ALL GENRE */

    public function get_Genre(){
        $genre_array =   [
         ['All', 'Всьо'] ,['Action', 'Бойовик курва'],['Adventure', 'Пригодницький'],['Animation', 'Мультяшки'],['Biography', 'Біографічний' ],['Comedy', 'Ржака'],['Crime', 'Бандюганський'],['Documentary', 'Документальний'],['Drama', 'Драма'],
         ['Family', 'Сімейний'],['Fantasy', 'Шото анріал'],['Film-Noir','Чорний фільм'],['History', 'Історичний'],['Horror', 'Страшний'],['Music', 'Музикальний'],['Musical', 'Мюзикл'],['Mystery', 'Таємничий'],['Romance', 'Соплі'],['Sci-Fi', 'Ботанський'],['Sport', 'На спортіку'],['Thriller','Трилер'],
         ['War', 'Войнушки'],['Western', 'Ковбойські']];

        return $genre_array;
    }

    /** CHANGE USER PROFILE DATA */

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
                    $user->user_name = str_replace('<', '&lt;',$user->user_name );
                    $user->user_name = str_replace('>', '&gt;',$user->user_name );
                    $user->user_secondname = str_replace('<', '&lt;',$user->user_secondname );
                    $user->user_secondname = str_replace('>', '&gt;',$user->user_secondname );
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
        if ($session['language'] == 'ua'){
            return $this->render('settings_ua',compact('user'));
        }
        return $this->render('settings',compact('user'));
    }

    /** MAKE LOGOUT AND DESTROY SESSION */

    public function actionLogout()
    {
        $session = Yii::$app->session;
        foreach ($session as $name => $value ){
           unset($session[$name]);
        }
        $session->close();
        $session->destroy();

        $this->redirect('http://localhost:8080/hypertube/web/index');
    }
}