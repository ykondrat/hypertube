<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 13.09.17
 * Time: 16:14
 */
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>
<?php
$session = Yii::$app->session;

if (isset($session['filter_value']) && $session['filter_value'] != '') {
    $filter_val = explode(',', $session['filter_value']);
    $year_from = $filter_val[1];
    $year_to = $filter_val[2];
    $rat_from = $filter_val[4];
    $rat_to = $filter_val[5];
}else{
    $year_from = '1920';
    $year_to = '2017';
    $rat_from = '1.4';
    $rat_to = '9.3';
}

?>
<div id="search-nav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <h4>Порода фільма</h4>
    <div class="film-genres">
        <ul>
            <?php Pjax::begin();
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'summary'=>'',
                'itemView' => 'genre_list',
                'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
            ]);
            Pjax::end();?>
        </ul>
    </div>
    <h4>Потасувати фільми</h4>
    <div class="film-sort">
        <ul>
            <li class="sort-list">Коли сі був знятий <i class="fa fa-sort-numeric-asc sort-btn" onclick="setSort('Year,asc')" aria-hidden="true"></i> <i onclick="setSort('Year,desc')" class="fa fa-sort-numeric-desc sort-btn" aria-hidden="true"></i></li>
            <hr />
            <li class="sort-list">По уважусі <i class="fa fa-sort-numeric-asc sort-btn" onclick="setSort('imdbRating,desc')" aria-hidden="true"></i>  <i onclick="setSort('imdbRating,asc')" class="fa fa-sort-numeric-desc sort-btn" aria-hidden="true"></i></li>
            <hr />
            <li class="sort-list">По абетці<i class="fa fa-sort-alpha-asc sort-btn" onclick="setSort('Title,asc')" aria-hidden="true"></i> <i onclick="setSort('Title,desc')" class="fa fa-sort-alpha-desc sort-btn" aria-hidden="true"></i></li>
        </ul>
    </div>
    <h4>Просіяти фільми</h4>
    <div class="film-sort">
        <ul>
            <li>Рік</li>

            <?php echo kartik\slider\Slider::widget([
                'name'=>'Year',
                'value'=>"$year_from,$year_to",
                'sliderColor'=>kartik\slider\Slider::TYPE_GREY,
                'pluginOptions'=>[
                    'min'=>1920,
                    'max'=>2017,
                    'step'=>1,
                    'range'=>true,
                    'tooltip'=>'always'
                ]]);?>
            <hr />
            <li>Уважуха</li>
            <?php echo kartik\slider\Slider::widget([
                'name'=>'Rating',
                'value'=>"$rat_from,$rat_to",
                'sliderColor'=>kartik\slider\Slider::TYPE_GREY,
                'pluginOptions'=>[
                    'min'=>1.4,
                    'max'=>9.3,
                    'step'=>0.1,
                    'range'=>true,
                    'tooltip'=>'always'
                ]]);?>
            <li class="sort-list filter-btn" onclick="setFilter()">
                Просіяти
            </li>
        </ul>
    </div>
</div>
<nav class="navbar navbar-inverse bg-inverse">
    <div class="nav-container">
        <span style="font-size:30px;cursor:pointer; margin-top: 10px" onclick="openNav()" class="open-nav">&#9776;</span>
        <input type="text" style="background-image: url('search-icon.png');" name="search" id="search-film" placeholder="ВИ́НИШПОРИТИ..">
        <div class="right-nav">
            <a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $user->user_name; ?></a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <img src="<?= $user->user_avatar?>" alt="user_avatar" class="avatar-drop img-fluid" />
                <p class="dropdown-item"><?= $user->user_name." ".$user->user_secondname?></p>
                <a class="dropdown-item" href="settings">Підправити фейс <i class="fa fa-wrench" aria-hidden="true"></i></a>
                <a class="dropdown-item" href="logout">Дати драла <i class="fa fa-power-off" aria-hidden="true"></i></a>
            </div>
            <span class="language-span" id='ua' onclick="changeLanguage(this)">UA</span><span id='en' class="language-span" onclick="changeLanguage(this)">EN</span>
        </div>
    </div>
</nav>
<div class="container film-list">
    <div class="row" id="movies">


    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 pag-btn">
        <button class="btn prev"><i class="fa fa-chevron-left" aria-hidden="true"></i> Назад</button>
        <button class="btn next">Далі <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
    </div>
</div>
