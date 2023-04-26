<?php 
require_once './inc/functions.php';
require_once './inc/headers.php';

$dbcon = openDb();

$artist = $_POST["artist"];
$album = $_POST["album"];
$tracks = $_POST["track"];
$mediatype = $_POST["mediatype"];

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
        $sql = "INSERT INTO tracks (Name, AlbumId, MediaTypeId) VALUES ('$track','$last_id','$mediatype')";
        $statement = $dbcon->prepare($sql);
        $statement->execute();
    }

    $dbcon->commit();
}catch(Exception $e){

    $dbcon->rollBack();
    echo $e->getMessage();
}