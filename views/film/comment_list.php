<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 28.09.17
 * Time: 16:43
 */

?>
<div class="comment-item row">
    <div class="user-info col-sm-3 col-md-3	col-lg-3 col-xl-3">
        <img src="<?= $model->user_avatar?>" alt="user avatar" class="img-fluid">
        <h1><?=$model->user_name?> <?=$model->user_secondname?></h1>
    </div>
    <div class="user-comment col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <p>  <?=$model->text?>     </p>
    </div>
    <div class="comment-date col-sm-3 col-md-3 col-lg-3 col-xl-3">
        <h4><?=$model->time?></h4>
    </div>
</div>
