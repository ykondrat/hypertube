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
</div>
<nav class="navbar navbar-inverse bg-inverse">
    <div class="nav-container">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()" class="open-nav">&#9776;</span>
        <input type="text" style="background-image: url('search-icon.png');" name="search" id="search-film" placeholder="Search..">
        <div class="right-nav">
            <a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php
                if ($user->user_login != NULL):
                echo $user->user_login;
                else :
                echo $user->user_name;
                endif;
                ?></a>
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