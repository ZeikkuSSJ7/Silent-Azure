<?php
include "../config.php";

$id = $_GET['id'];
$song = $_GET['song'];
$liked = $_GET['liked'] ? 1 : 0;

$sql = "UPDATE rel_songs_users SET liked = " . $liked . " WHERE idsoundtrack = " . $id . " AND idsong = " . $song . " AND iduser = " . $_SESSION['id'];

$result = $database->query($sql);

if (!$database->affected_rows) :
    $sql = "INSERT INTO rel_songs_users values (" . $id . ", " . $_SESSION['id'] . ", " . $song . ", " . $liked . ")";
    $database->query($sql);
    if($database->affected_rows) :
        die($sql);
    endif;
else:
    die($sql);
endif;