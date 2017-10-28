<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 29.09.17
 * Time: 11:23
 */
?>

<div>
    <a href="javascript:void(0);" onclick='sendTorrent(this, "<?=$model->imdbID?>,<?=$model->number?>")' class="list-group-item list-group-item-action list-group-item-success"><?=$model->quality?> (<?=$model->size?> )<span class="sid">sid: <?=$model->seeds?></span> <span class="pid">pid: <?=$model->peers?></span><span class="done"><?=$model->torent_done?></span></a>
</div>
