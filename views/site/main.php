<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 13.09.17
 * Time: 16:14
 */

?>
<script>
    function getShowOptionsFromName () {

        $.ajax({
            url: "imdb",
            method: "POST",
            data: {q: $("#test").val()},
            dataType: "json"
        }).done(function(data){
            console.log(data);
        });
    }
</script>

<h1>HELLO =)</h1>

<input type="text" id="test">
<button onclick="getShowOptionsFromName()">ghghgh</button>
