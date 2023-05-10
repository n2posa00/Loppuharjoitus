<?php 
require_once './inc/functions.php';
require_once './inc/headers.php';

$dbcon = openDb();

$artist = filter_var(strip_tags($_POST["artist"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$album = filter_var(strip_tags($_POST["album"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tracks = $_POST["track"];
$mediatype = filter_var(strip_tags($_POST["mediatype"]), FILTER_SANITIZE_NUMBER_INT);

try {
    
    $dbcon->beginTransaction();

    $sql = "INSERT INTO artists (Name) VALUES ('$artist')";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $last_id = $dbcon->lastInsertId();

    $sql = "INSERT INTO albums (Title, ArtistId) VALUES ('$album','$last_id')";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $last_id = $dbcon->lastInsertId();

    foreach($tracks as $track) {
        $track = filter_var(strip_tags($track), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sql = "INSERT INTO tracks (Name, AlbumId, MediaTypeId) VALUES ('$track','$last_id','$mediatype')";
        $statement = $dbcon->prepare($sql);
        $statement->execute();
    }

    $dbcon->commit();
}catch(Exception $e){

    $dbcon->rollBack();
    echo $e->getMessage();
}