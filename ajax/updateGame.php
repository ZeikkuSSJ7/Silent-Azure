<?php
include "../config.php";

$id = $_GET['id'];
$score = $_GET['score'];

$sql = "UPDATE rel_games_users SET score = " . $score . " WHERE idgame = " . $id . " AND iduser = " . $_SESSION['id'];

$result = $database->query($sql);

if (!$database->affected_rows) :
    $sql = "INSERT INTO rel_games_users values (" . $id . ", " . $_SESSION['id'] . ", " . $score . ")";
    $database->query($sql);
    if($database->affected_rows) :
        echo 'success';
    endif;
else:
    echo 'success';
endif;