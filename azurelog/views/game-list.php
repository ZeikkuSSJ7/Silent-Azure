<?php
include '../../config.php';

$uid = $_SESSION['id'];

if (isset($_GET['statusId'])) :
    $status = $_GET['statusId'];

    $sql = 'SELECT ag.id, ag.game_title, ag.image, relgu.status, p.name as platform FROM azurelog_games ag 
	    inner join azurelog_rel_users_games relgu on ag.id = relgu.idgame 
        inner join azurelog_platforms p on ag.platform = p.id
        where relgu.iduser = ' . $uid;

    if ($status != -1) :
        $sql .= ' AND relgu.status = ' . $status;
    endif;
    $sql .= ' ORDER BY ag.game_title';

    $result = $database->query($sql);

    while ($row = $result->fetch_assoc()) :
        $id = $row['id'];
        $name = $row['game_title'];
        $status = $row['status'];
        $platform = $row['platform'];
        switch ($status):
            case 0:
                $status = "planned";
                break;
            case 1:
                $status = "playing";
                break;
            case 2:
                $status = "hold";
                break;
            case 3:
                $status = "dropped";
                break;
            case 4:
                $status = "completed";
                break;
            case 5:
                $status = "mastered";
                break;
        endswitch;
        $image = AZURELOG_IMAGES_DIR . '/' . $id . '.png';
        include 'widgets/card.php';
    endwhile;
endif;
?>
<div class="div-fab-wrapper">
    <div class="fab-wrapper small">
        <button class="fab random"></button>
    </div>
    <div class="fab-wrapper small">
        <button class="fab new-game"></button>
    </div>
    <div class="fab-wrapper small">
        <button class="fab order"></button>
    </div>
    <div class="fab-wrapper big">
        <button class="fab"></button>
    </div>
</div>