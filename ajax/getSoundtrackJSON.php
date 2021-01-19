<?php
include "../config.php";
include "../vendor/autoload.php";

use wapmorgan\Mp3Info\Mp3Info;

$id = $_GET['id'];

$result = $database->query('SELECT name FROM soundtracks where id = ' . $id);
$row = $result->fetch_assoc();

$name = $row['name'];
$directory = 'E:/AZURE_MULTIMEDIA/Soundtracks/' . $name . '/*.mp3';

$files = glob($directory);

$contSongs = 0;
// Inicia el JSON
$json = [
    "data" => [],
    "hostLink" => 'http://' . $_SERVER['HTTP_HOST'] . '/multimedia/Soundtracks/' . $name,
    "host" => $_SERVER['HTTP_HOST']
];
$iduser = $_SESSION['id'];
foreach ($files as $file) :

    $result = $database->query('SELECT liked FROM rel_songs_users where idsoundtrack = ' . $id . ' and iduser = ' . $iduser . ' and idsong = ' . $contSongs);
    if (isset($result)) :
        $row = $result->fetch_assoc();
    endif;

    $mp3 = new Mp3Info($file, true);

    $json["data"][$contSongs]["song"] = (isset($mp3->tags['song']) ? $mp3->tags['song'] : basename($file)); // nombre
    $json["data"][$contSongs]["filename"] = basename($file); // archivo
    $json["data"][$contSongs]["album"] = (isset($mp3->tags['album']) ? $mp3->tags['album'] : "Unknown"); // album
    $json["data"][$contSongs]["liked"] = isset($row['liked']) ? ($row['liked'] ? 'liked' : 'like') : 'like'; // gusta o no
    $contSongs++;

endforeach;
$send = json_encode($json);
echo $send;
