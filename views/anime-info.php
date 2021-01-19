<?php
include "../config.php";

$sql = 'SELECT a.id, a.name, a.year, a.description, a.location, aty.typename FROM animes a inner join animetypes aty on a.type = aty.id where a.id = ' . $_GET['id'];

$result = $database->query($sql);
if ($result->num_rows) :
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $name = $row['name'];
    $year = $row['year'];
    $description = $row['description'];
    $ftplocation = $row['location'];
    $type = $row['typename'];
endif;

$sql = 'SELECT score FROM rel_animes_users where iduser = ' . $_SESSION['id'] . ' AND idanime = ' . $id;

$result = $database->query($sql);
if ($result->num_rows) :
    $row = $result->fetch_assoc();
    $index = $row['score'];
endif;

?>


<div style="width: 100%; height: 100%">
    <div class="anime-cover-score">
        <img src="/images/animes/covers/<?php echo $id ?>.png" style="max-width: 100%;">

        <div style="width: 100%; padding: 6px 12px; background-color: #444444; box-sizing: border-box;">
            <?php echo 'Tu nota: ';
            include "./widgets/score-options.php"; ?>
        </div>
    </div>
    <div class="info-text-wrapper">
        <div class="content-text info">
            <h3><?php echo $name; ?></h3>
        </div>
        <div class="content-text info">Año: <?php echo $year; ?></div>
        <div class="content-text info">Descripción: <?php echo $description; ?></div>
    </div>
    <div class="info-seasons-wrapper">
        <?php
        $folder = 'E:/AZURE_MULTIMEDIA/Anime/' . $type . $ftplocation . '/*';
        $seasonCont = 0;

        $folders = glob($folder);
        if (endsWith($folders[0], "emporada")) : // Según la cantidad de carpetas de temporadas se entiende si es una película o una serie
            foreach ($folders as $season) : ?>
                <div class="season-menu">
                    <div class="season-menu-opener content-text">
                        <img src="/images/animes/icons/plus.png" width="20px" style="margin-right: 5px;">
                        <?php echo basename($season) ?>
                    </div>
                    <div>
                        <?php
                        $videos = glob($season . "/*");
                        $count = 0;
                        foreach ($videos as $video) :
                            echo '
                                <div class="season-menu-video">
                                    <a target="_blank" href="/watch.php?id=' . $id . '&season=' . $seasonCont . '&v=' . $count . '">'
                                . preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($video)) .
                                    '</a>
                                    <a href="/ajax/down.php?contentId=' . $id . '&season=' . $seasonCont . '&v=' . $count++ . '">
                                        <img src="/images/soundtracks/player/download.png" width="20px" style="margin-left: 10px;">
                                    </a>
                                </div>';
                        endforeach;
                        ?>
                    </div>
                </div>
        <?php
                $seasonCont++;
            endforeach;
        else :
            echo '
                <div class="movie-video">
                    <a target="_blank" href="/watch.php?id=' . $id . '&season=0&v=0">Haz click aquí para ver la película</a>
                    <a href="/ajax/down.php?contentId=' . $id . '&season=0&v=0">
                        <img src="/images/soundtracks/player/download.png" width="20px" style="margin-left: 10px;">
                    </a>
                </div>';

        endif;
        ?>
    </div>
</div>