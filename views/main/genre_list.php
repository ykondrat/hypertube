<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 16.09.17
 * Time: 17:59
 */

$session = Yii::$app->session;

if ($session['language'] == 'ua')
    $lan = $model[1];
else{
    $lan = $model[0];
}
?>


<li data-ganre="<?=$model[0]?>" onclick="setGenre(this)"><?=$lan?></li>
