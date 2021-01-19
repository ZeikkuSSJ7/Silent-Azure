<?php
include "../../config.php";

$gamename = " AND game_title like \"%" . str_replace(" ", "%", trim($_POST['gamename'])) . "%\"";
$publisher = trim($_POST['publisher']) == -1 ? "" : " AND publisher = " . trim($_POST['publisher']);
$developer = trim($_POST['developer']) == -1 ? "" : " AND developer = " . trim($_POST['developer']);
$releasedate = trim($_POST['releasedate']) == "" ? "" : " AND release_date = \"" . trim($_POST['releasedate']) . "\"";
$favourite = " AND isfavourite = " . (trim($_POST['favourite']) == "true" ? 1 : 0);
$platform = trim($_POST['platform']) == -1 ? "" : " AND platform = " . trim($_POST['platform']);
$startdate1 = trim($_POST['startdate1']) == "" ? "" : " AND startdate >= \"" . trim($_POST['startdate1']) . "\"";
$startdate2 = trim($_POST['startdate2']) == "" ? "" : " AND startdate <= \"" . trim($_POST['startdate2']) . "\"";
$finishdate1 = trim($_POST['finishdate1']) == "" ? "" : " AND finishdate >= \"" . trim($_POST['finishdate1']) . "\"";
$finishdate2 = trim($_POST['finishdate2']) == "" ? "" : " AND finishdate <= \"" . trim($_POST['finishdate2']) . "\"";
$playtime1 = trim($_POST['playtime1']) == "" ? "" : " AND playtime >= \"" . trim($_POST['playtime1']) . "\"";
$playtime2 = trim($_POST['playtime2']) == "" ? "" : " AND playtime <= \"" . trim($_POST['playtime2']) . "\"";


    $sql = 'SELECT ag.id, game_title, release_date, p.name as platform, relgu.status, relgu.finishdate FROM azurelog_games ag 
        INNER JOIN azurelog_rel_users_games relgu on ag.id = relgu.idgame 
        inner join azurelog_platforms p on ag.platform = p.id 
        where relgu.iduser = ' . $_SESSION['id'] . $gamename . 
        $publisher . $developer . $platform . $favourite . 
        $releasedate . $startdate1 . $startdate2 . $finishdate1 .
        $finishdate2 . $playtime1 . $playtime2 . ' ORDER BY finishdate';

    $result = $database->query($sql);
    
    if ($result->num_rows) :
        while ($row = $result->fetch_assoc()) :
            $id = $row['id'];
            $name = $row['game_title'];
            $platform = $row['platform'];
            $releasedate = $row['release_date'];
            $status = $row['status'] == 4 ? "completed" : "mastered";
            $image = AZURELOG_IMAGES_DIR . '/' . $id . '.png';
            include ROOT_LOCAL . "/azurelog/views/widgets/card.php";
        endwhile;
    else :
        echo "No hay ningún resultado...";
    endif;
