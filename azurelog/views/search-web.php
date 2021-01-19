<?php
include "../../config.php";

if (isset($_GET['q'])) :
    $q = trim($_GET['q']);
    if (!empty($q) && strlen($q) > 3) :

        $sql = 'SELECT ag.id, game_title, release_date, p.name as platform FROM azurelog_games ag 
            inner join azurelog_platforms p on ag.platform = p.id 
            where game_title like "%' . str_replace(" ", "%", $_GET['q']) . '%" ORDER BY game_title';
    
        $result = $database->query($sql);
        $value = $q;
        if ($result->num_rows) :
            while ($row = $result->fetch_assoc()) :
                $id = $row['id'];
                $name = $row['game_title'];
                #$releasedate = $row['release_date'];
                $platform = $row['platform'];
                $status = 'playing';
                $image = AZURELOG_IMAGES_DIR . '/' . $id . '.png';
                include ROOT_LOCAL . "/azurelog/views/widgets/card.php";
            endwhile;
        else:
            echo "No hay ningún resultado...";
        endif;
    else :
        echo "No hay ningún resultado...";
    endif;

else : ?>

    <div class="search-web-results" style="width: 100%;">

    </div>

<?php
endif;
?>