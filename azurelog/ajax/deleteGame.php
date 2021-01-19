<?php
include "../../config.php";
$id = $_GET['id'];

$database->query('DELETE FROM azurelog_rel_users_games where idgame = ' . $id . ' AND iduser = ' . $_SESSION['id']);

