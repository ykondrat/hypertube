<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 17.09.17
 * Time: 14:25
 */


?>

<nav class="navbar navbar-inverse bg-inverse">
    <a class="btn-film-view" href="http://localhost:8080/hypertube/web/main">Main page <i class="fa fa-film" aria-hidden="true"></i></a>
    <div class="nav-container">
        <div class="right-nav">
            <a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php

                    echo $user->user_name;

                ?></a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <img src="<?= $user->user_avatar?>" alt="user_avatar" class="avatar-drop img-fluid" />
                <p class="dropdown-item"><?= $user->user_name." ".$user->user_secondname?></p>
                <a class="dropdown-item" href="http://localhost:8080/hypertube/web/settings">Settings <i class="fa fa-wrench" aria-hidden="true"></i></a>
                <a class="dropdown-item" href="http://localhost:8080/hypertube/web/logout">Logout <i class="fa fa-power-off" aria-hidden="true"></i></a>

            </div>
            <span class="language-span" onclick="changeLanguage(this)">UA</span><span class="language-span checked" onclick="changeLanguage(this)">EN</span>
        </div>
    </div>

</nav>
<div class="container film-view">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6 film-poster">
            <img src="<?=$film->Poster?>" alt="<?=$film->Title?>" class="img-fluid" style="width: 100%">
        </div>
        <div class="col-sm-12 col-md-4 col-lg-6 col-xl-6 film-info">
            <div class="header-film">
                <h4>&#9734; <?=$film->imdbRating?></h4> <!-- Metascore -->
                <h1><?=$film->Title?></h1>

                <h6>(<?=$film->Year?> year)</h6>
                <p class="plot lead"><?=$film->Plot?></p>
                <p class="second-info"><span>Director:</span><?=$film->Director?></p>
                <p class="second-info"><span>Writers:</span><?=$film->Writer?></p>
                <p class="second-info"><span>Stars:</span><?=$film->Actors?></p>
                <p class="second-info"><span>Release Date:</span><?=$film->Released?></p>
                <p class="second-info"><span>Runtime:</span><?=$film->Runtime?></p>
            </div>
            <div class="torrent-list">
                <p class="lead ">Click on link to start download and watch film</p>
                <div class="list-group">
                    <div>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">torrent tracker 1 <span class="sid">sid: 42</span> <span class="pid">pid: 42</span></a>
                        <span class="play ready">Watch &#9658;</span>
                        <div id="progress">
                            <div id="bar"></div>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">torrent tracker 2 <span class="sid">sid: 42</span> <span class="pid">pid: 42</span></a>
                        <span class="play">Watch &#9658;</span>
                    </div>
                    <div>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">torrent tracker 3 <span class="sid">sid: 42</span> <span class="pid">pid: 42</span></a>
                        <span class="play">Watch &#9658;</span>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>

