<?php 
require_once './inc/functions.php';
require_once './inc/headers.php';

$dbcon = openDb();

$artist_id = 90;

$Results = array();

$sql = "SELECT Name FROM artists WHERE ArtistId = $artist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();
$artistName = $statement->fetch(PDO::FETCH_COLUMN);

$albums = array();

$sql = "SELECT Title, AlbumId FROM albums WHERE ArtistId = $artist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();

foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $album) {

  $tracks = array();

  $sql = "SELECT Name FROM tracks WHERE AlbumId = " . $album['AlbumId'];
  $statement = $dbcon->prepare($sql);
  $statement->execute();

  foreach ($statement->fetchAll(PDO::FETCH_COLUMN) as $track) {

    $tracks[] = $track;
  }

  $albums[] = array(
    'title' => $album['Title'],
    'tracks' => $tracks
  );
}

$Results[] = array(
  'artist' => $artistName,
  'albums' => $albums
);

$json = json_encode($Results, JSON_PRETTY_PRINT);
header('Content-type: application/json');
echo $json;
