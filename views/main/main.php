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

<div id="search-nav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <h4>Film Genres</h4>
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
    <h4>Sort Film</h4>
    <div class="film-sort">
        <ul>
            <li>Year <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></li>
            <li>Year <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i></li>
            <hr />
            <li>Rating <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></li>
            <li>Rating <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i></li>
            <hr />
            <li>Alphabetical <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i></li>
            <li>Alphabetical <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></li>
        </ul>
    </div>
    <h4>Filter Film</h4>
    <div class="film-sort">
        <ul>
            <li>Year <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></li>
            <hr />
            <li>Rating <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></li>
        </ul>
    </div>
</div>
<nav class="navbar navbar-inverse bg-inverse">
    <div class="nav-container">
        <span style="font-size:30px;cursor:pointer; margin-top: 10px" onclick="openNav()" class="open-nav">&#9776;</span>
        <input type="text" style="background-image: url('search-icon.png');" name="search" id="search-film" placeholder="Search..">
        <div class="right-nav">
            <a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $user->user_name; ?></a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <img src="<?= $user->user_avatar?>" alt="user_avatar" class="avatar-drop img-fluid" />
                <p class="dropdown-item"><?= $user->user_name." ".$user->user_secondname?></p>
                <a class="dropdown-item" href="settings">Settings <i class="fa fa-wrench" aria-hidden="true"></i></a>
                <a class="dropdown-item" href="logout">Logout <i class="fa fa-power-off" aria-hidden="true"></i></a>
            </div>
            <span class="language-span" onclick="changeLanguage(this)">UA</span><span class="language-span checked" onclick="changeLanguage(this)">EN</span>
        </div>
    </div>
</nav>
<div class="container film-list">
    <div class="row" id="movies">


    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 pag-btn">
        <button class="btn prev"><i class="fa fa-chevron-left" aria-hidden="true"></i> Prev</button>
        <button class="btn next">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
    </div>
</div>
