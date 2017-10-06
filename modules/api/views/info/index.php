<?php
/**
 * Created by PhpStorm.
 * User: sandruse
 * Date: 05.10.17
 * Time: 16:17
 */
?>
<style>
    table.table{
        border-spacing: 2px;
    }
</style>
<h1>Hypertube API Documentation</h1>
<hr>
<p>
    In order to use our api you need to send a POST request to <u>http://localhost:8080/hypertube/web/api/call</u>  with the following parameters:
    <ul type="disc">
        <li>Content-Type : application/json</li>
        <li><pre>Body : {
          "Model"  : " <u>Name of model</u> ",
          "Method" : " <u>Name of method in this model</u> ",
          "Args"   : [ <u>Array of arguments</u> ]
       }</pre>
        </li>
    </ul>
    which will return a JSON object.
</p>
<br>
<hr>
<br>
<table class="table" border="4">
    <thead>
    <tr>
        <th><u>Model name</u></th>
        <th><u>Method name</u></th>
        <th><u>Arguments</u></th>
        <th><u>Description</u></th>
        <th><u>Exemple</u></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td rowspan="8">User</td>
        <td>GetUserList</td>
        <td>[ null ]</td>
        <td>Return list of all users (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`)</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "GetUserList",
    "Args"   : []
}</pre></td>
    </tr>
    <tr>
        <td>GetUserById</td>
        <td>[ user id, ... , ]</td>
        <td>Return users information (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`) by `user_id`</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "GetUserById",
    "Args"   : [1, 2, 3]
}</pre></td>
    </tr>
    <tr>
        <td>SearchUserByName</td>
        <td>[ "name of user or part of it" ]</td>
        <td>Search users by name and return information about them (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`)</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "SearchUserByName",
    "Args"   : ["John"]
}</pre></td>
    </tr>
    <tr>
        <td>SerchUserBySecondname</td>
        <td>[ "surname of user or part of it" ]</td>
        <td>Search users by surname and return information about them (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`)</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "SerchUserBySecondname",
    "Args"   : ["Smith"]
}</pre></td>
    </tr>
    <tr>
        <td>SerchUserByEmail</td>
        <td>[ "email of user or part of it" ]</td>
        <td>Search users by email and return information about them (`user_id`, `user_name`, `user_secondname`, `user_email`, `user_avatar`)</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "SerchUserByEmail",
    "Args"   : ["johnsmith@mail.com"]
}</pre></td>
    </tr>
    <tr>
        <td>UpdateUserData</td>
        <td>[ user id, the parameter you want to change { "user_name" or "user_secondname" } , "new value ​​of this parameter" ]</td>
        <td>Update user name or user surname by `user_id`</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "UpdateUserData",
    "Args"   : [1, "user_name", "Mike"]
}</pre></td>
    </tr>
    <tr>
        <td>DeleteUser</td>
        <td>[ user id ]</td>
        <td>Delete user by `user_id`</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "DeleteUser",
    "Args"   : [1]
}</pre></td>
    </tr>
    <tr>
        <td>CreateUser</td>
        <td>[ "user name", "user secondname", "user email", "user password" ]</td>
        <td>Create new user</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "CreateUser",
    "Args"   : ["John", "Smith", "johnsmith@mail.com", "11111111"]
}</pre></td>
    </tr>
    <tr>
        <td rowspan="7">Imdbid</td>
        <td>GetFilmList</td>
        <td>[ null ]</td>
        <td>Return list of all films ('imdbID', 'Title' ,'Year' ,'Runtime' ,'Released','Genre' ,'Director' ,'Writer' ,'Actors' ,'Plot' ,'Language' ,'Country' ,'Awards' ,'Poster' ,'Metascore' ,'imdbRating' ,'Production'</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "GetFilmList",
    "Args"   : []
}</pre></td>
    </tr>
    <tr>
        <td>GetFilmByImdbId</td>
        <td>[ "imdb id", ..., ]</td>
        <td>Return film information  by `ImdbID`</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "GetFilmByImdbId",
    "Args"   : ["tt0010323", "tt0013442"]
}</pre></td>
    </tr>
    <tr>
        <td>SearchFilmByTitle</td>
        <td>[ "title of film or part of it" ]</td>
        <td>Search films by title and return information about them</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "SearchFilmByTitle",
    "Args"   : ["Frankenstein"]
}</pre></td>
    </tr>
    <tr>
        <td>SearchFilmByYear</td>
        <td>[ Year ]</td>
        <td>Search films by Year and return information about them</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "SearchFilmByYear",
    "Args"   : [1994]
}</pre></td>
    </tr>
    <tr>
        <td>SearchFilmByGenre</td>
        <td>["genre of film"]</td>
        <td>Search films by genre and return information about them</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "SearchFilmByGenre",
    "Args"   : ["Action"]
}</pre></td>
    </tr>
    <tr>
        <td>SearchFilmByActor</td>
        <td>[ "name or surname of actor or part of it" ]</td>
        <td> Search films by actor name or surname and return information about them</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "SearchFilmByActor",
    "Args"   : ["Will Smith"]
}</pre></td>
    </tr>
    <tr>
        <td>SearchFilmByRating</td>
        <td>[ imdb rating (float) ]</td>
        <td>Search films by Imdb rating and return information about them</td>
        <td><pre>{
    "Model"  : "Imdbid",
    "Method" : "SearchFilmByRating",
    "Args"   : [8.2]
}</pre></td>
    </tr>
    <tr>
        <td>Genre</td>
        <td>GetGenreList</td>
        <td>[ null ]</td>
        <td>Return list of all genres</td>
        <td><pre>{
    "Model"  : "Genre",
    "Method" : "GetGenreList",
    "Args"   : []
}</pre></td>
    </tr>
    <tr>
        <td rowspan="2">Torrentlink</td>
        <td>GetTorrentList</td>
        <td>[ null ]</td>
        <td>Return list of all torrent's ('imdbID', 'url' ,'hash', 'qualiti', 'seeds', 'peers', 'size' )</td>
        <td><pre>{
    "Model"  : "Torrentlink",
    "Method" : "GetTorrentList",
    "Args"   : []
}</pre></td>
    </tr>
    <tr>
        <td>GetTorrentByImdbId</td>
        <td>[ "imdb id", ..., ]</td>
        <td>Return torrent's  by `ImdbID`</td>
        <td><pre>{
    "Model"  : "Torrentlink",
    "Method" : "GetTorrentByImdbId",
    "Args"   : ["tt0010323", "tt0013442"]
}</pre></td>
    </tr>
    <tr>
        <td rowspan="2">Subtitle</td>
        <td>GetSubtitleList</td>
        <td>[ null ]</td>
        <td>Return list of all subtitles ('imdbID', 'Tlanguage' ,'url path' )</td>
        <td><pre>{
    "Model"  : "Subtitle",
    "Method" : "GetSubtitleList",
    "Args"   : []
}</pre></td>
    </tr>
    <tr>
        <td>GetSubtitleByImdbId</td>
        <td>[ "imdb id", ..., ]</td>
        <td>Return subtitles  by `ImdbID`</td>
        <td><pre>{
    "Model"  : "Subtitle",
    "Method" : "GetSubtitleByImdbId",
    "Args"   : ["tt0010323", "tt0013442"]
}</pre></td>
    </tr>
    <tr>
        <td rowspan="8">Comment</td>
        <td>GetCommentList</td>
        <td>[ null ]</td>
        <td>Return list of all comments (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`)</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "GetCommentList",
    "Args"   : []
}</pre></td>
    </tr>
    <tr>
        <td>GetCommentById</td>
        <td>[ comment id, ..., ]</td>
        <td>Return comments information (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`) by `id`</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "GetCommentById",
    "Args"   : [1, 2, 3]
}</pre></td>
    </tr>
    <tr>
        <td>SearchCommentByUserName</td>
        <td>[ "name of user or part of it" ]</td>
        <td>Search comments by user name and return information about them (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`)</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "SearchCommentByUserName",
    "Args"   : ["John"]
}</pre></td>
    </tr>
    <tr>
        <td>SerchCommentByUserSecondname</td>
        <td>[ "surname of user or part of it" ]</td>
        <td>Search comments by user surname and return information about them (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`)</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "SerchCommentByUserSecondname",
    "Args"   : ["Smith"]
}</pre></td>
    </tr>
    <tr>
        <td>SerchCommentByImdbId</td>
        <td>["imdb id"]</td>
        <td>Search comments by imdbID and return information about them (`id`, `user_name`, `user_secondname`, `imdbID`, `time`, `text`)</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "SerchCommentByImdbId",
    "Args"   : ["tt0010323"]
}</pre></td>
    </tr>
    <tr>
        <td>UpdateCommentData</td>
        <td>[ comment id, "new text" ]</td>
        <td>Update comment text by `id`</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "UpdateCommentData",
    "Args"   : [1, "some text"]
}</pre></td>
    </tr>
    <tr>
        <td>DeleteComment</td>
        <td>[ comment id ]</td>
        <td>Delete comment by `id`</td>
        <td><pre>{
    "Model"  : "Comment",
    "Method" : "DeleteComment",
    "Args"   : [1]
}</pre></td>
    </tr>
    <tr>
        <td>CreateComment</td>
        <td>[ "user_name", "user_secondname", "imdbID", "text" ]</td>
        <td>Create new comment. User name and surname must be valid (user whith such name must exist in database)</td>
        <td><pre>{
    "Model"  : "User",
    "Method" : "CreateComment",
    "Args"   : ["John", "Smith", "tt0010323", "some text"]
}</pre></td>
    </tr>
    </tbody>
</table>

