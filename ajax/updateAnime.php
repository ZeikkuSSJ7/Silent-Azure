<?php
include "../config.php";

$id = $_GET['id'];
$score = $_GET['score'];

$sql = "UPDATE rel_animes_users SET score = " . $score . " WHERE idanime = " . $id . " AND iduser = " . $_SESSION['id'];

$result = $database->query($sql);

if (!$database->affected_rows) :
    $sql = "INSERT INTO rel_animes_users values (" . $id . ", " . $_SESSION['id'] . ", " . $score . ")";
    $database->query($sql);
    if($database->affected_rows) :
        echo 'success';
    endif;
else:
    echo 'success';
endif;