<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 17.09.17
 * Time: 14:25
 */

use yii\widgets\ListView;
use yii\widgets\Pjax;
?>

<nav class="navbar navbar-inverse bg-inverse">
    <a class="btn-film-view" id="lan" href="http://localhost:8080/hypertube/web/main">На головну <i class="fa fa-film" aria-hidden="true"></i></a>
    <div class="nav-container">
        <div class="right-nav">
            <a class="user-login dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php

                    echo $user->user_name;

                ?></a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <img src="<?= $user->user_avatar?>" alt="user_avatar" class="avatar-drop img-fluid" />
                <p class="dropdown-item"><?= $user->user_name." ".$user->user_secondname?></p>
                <a class="dropdown-item" href="http://localhost:8080/hypertube/web/settings">Підправити фейс <i class="fa fa-wrench" aria-hidden="true"></i></a>
                <a class="dropdown-item" href="http://localhost:8080/hypertube/web/logout">Дати драла <i class="fa fa-power-off" aria-hidden="true"></i></a>

            </div>
            <span class="language-span" id='ua' onclick="changeLanguage(this)">UA</span><span id='en' class="language-span" onclick="changeLanguage(this)">EN</span>
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
                <p id="imdbID" style="visibility: hidden"><?=$film->imdbID?> </p>
                <h6>(<?=$film->Year?> year)</h6>
                <p class="plot lead"><?=$film->Plot?></p>
                <p class="second-info"><span>Зняв:</span><?=$film->Director?></p>
                <p class="second-info"><span>Придумав:</span><?=$film->Writer?></p>
                <p class="second-info"><span>Звьозди:</span><?=$film->Actors?></p>
                <p class="second-info"><span>Побачив світ в:</span><?=$film->Released?></p>
                <p class="second-info"><span>Скільки йде:</span><?=$film->Runtime?></p>
            </div>
            <div class="torrent-list">
                <p class="lead ">Тикни щоб почати скачувати і втикать</p>
                <div class="list-group">
                    <?php Pjax::begin() ?>
                    <?= ListView::widget([
                        'dataProvider' => $torrents,
                        'itemOptions' => ['class' => 'item'],
                        'summary'=>'',
                        'itemView' => 'torrent_list',
                        'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
                    ]); ?>
                    <?php Pjax::end();?>

                </div>
            </div>
        </div>

    </div>


</div>

<div class="container">

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#comments" role="tab">Коменти</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#add" role="tab">Добавити комент</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="comments" role="tabpanel">
            <div class="comment-list">
                <?php Pjax::begin(['id' => 'some_pjax_id']) ?>
                <?= ListView::widget([
                    'dataProvider' => $comments,
                    'itemOptions' => ['class' => 'item'],
                    'summary'=>'',
                    'itemView' => 'comment_list',
                    'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
                ]); ?>
                <?php Pjax::end();?>

            </div>
        </div>
        <div class="tab-pane" id="add" role="tabpanel">
            <div class="comment-form">
                <textarea id="comment_text" name="name" rows="8" cols="80" placeholder="Додай свій комент..."></textarea>
                <button id="sendcomment" type="button" class="btn btn-outline-info">Відправити</button>
            </div>
        </div>
    </div>

</div>

