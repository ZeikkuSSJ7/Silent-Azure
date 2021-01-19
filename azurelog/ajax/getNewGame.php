<?php 
include "../../config.php";

$result = $database->query('SELECT idgame from azurelog_rel_users_games where status = 0 AND iduser = ' . $_SESSION['id']);

$games = array();

while ($row = $result->fetch_assoc()) :
    array_push($games, $row['idgame']);
endwhile;

$id = $games[rand(0, sizeof($games))];

include "../views/game-info.php";

?>