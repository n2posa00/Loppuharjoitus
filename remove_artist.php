<?php 
require_once './inc/functions.php';
require_once './inc/headers.php';

$dbcon = openDb();

$artist_id = strip_tags($_GET["id"]);

$sql = "SELECT AlbumId FROM albums WHERE ArtistId = $artist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();

$album_ids = $statement->fetchAll(PDO::FETCH_COLUMN);
$album_id = implode(',',$album_ids);

$sql = "SELECT TrackId FROM tracks WHERE AlbumId IN ($album_id)";
$statement = $dbcon->prepare($sql);
$statement->execute();

$track_ids = $statement->fetchAll(PDO::FETCH_COLUMN);
$track_id = implode(',',$track_ids);

$sql = "SELECT PlaylistId FROM playlist_track WHERE TrackId IN ($track_id)";
$statement = $dbcon->prepare($sql);
$statement->execute();

$playlist_ids = $statement->fetchAll(PDO::FETCH_COLUMN);
$playlist_id = implode(',', $playlist_ids);

try {
    $dbcon->beginTransaction();

    $sql = "DELETE FROM playlist_track WHERE TrackId IN ($track_id)";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $sql = "DELETE FROM invoice_items WHERE TrackId IN ($track_id)";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $sql = "DELETE FROM tracks WHERE TrackId IN ($track_id)";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $sql = "DELETE FROM albums WHERE ArtistId = $artist_id";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $sql = "DELETE FROM artists WHERE ArtistId = $artist_id";
    $statement = $dbcon->prepare($sql);
    $statement->execute();

    $dbcon->commit();
}catch(Exception $e){

    $dbcon->rollBack();
    echo $e->getMessage();
}