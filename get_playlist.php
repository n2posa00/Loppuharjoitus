<?php 
require_once './inc/functions.php';
require_once './inc/headers.php';

$dbcon = openDb();

$playlist_id = 1;

$sql = "SELECT TrackId FROM playlist_track WHERE PlaylistId = $playlist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();

$trackRows = $statement->fetchAll(PDO::FETCH_ASSOC);
$track_id = implode(',', array_column($trackRows, 'TrackId'));

echo "<h2>"."Playlist".$playlist_id."</h2>";

$sql = "SELECT * FROM tracks WHERE TrackId IN ($track_id)";
$statement = $dbcon->prepare($sql);
$statement->execute();

$tracks = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($tracks as $track) {
    echo "<h4>".$track["Name"]."</h4>".
    $track["Composer"]."<br>";
}

