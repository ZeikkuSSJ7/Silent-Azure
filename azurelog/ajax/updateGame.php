<?php 
include "../../config.php";

$sql = 'UPDATE azurelog_rel_users_games 
    set score = ' . $_POST['score'] . 
    ', status = ' . $_POST['status'] . 
    ', comment = "' . $_POST['comment'] . '"' . 
    ', isfavourite = "' . ($_POST['favourite'] == "true" ? "1" : "0") . '"' . 
    ', startdate = ' . ($_POST['startDate'] == "" ? "null" : '"' . $_POST['startDate'] . '"') . '' . 
    ', finishdate = ' . ($_POST['finishDate'] == "" ? "null" : '"' . $_POST['finishDate'] . '"') . '' . 
    ', playtime = ' . ($_POST['playtime'] == "" ? "null" : $_POST['playtime']) . '' . 
    ', replaying = ' . ($_POST['replaying'] == "true" ? "1" : "0") . '' . 
    ' where idgame = ' . $_POST['id'] . ' AND iduser = ' . $_SESSION['id'];
    
$database->query($sql);

?>