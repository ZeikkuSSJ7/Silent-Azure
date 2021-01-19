<?php include "../config.php"; ?>
<div style="width: 100%;">
    <?php include "widgets/search-bar.html" ?>
</div>
<div class="master-main-page">
    <div class="category">
        <div class="content-text">Búsqueda por letra</div>
    </div>
    <?php include "widgets/search-by-letter.html"; ?>
    <div class="category">
        <div class="content-text">Búsqueda por plataforma</div>
    </div>
    <div class="z-flow">
        <div class="container-cards">
            <?php

            $queryDlCounts = $database->query("SELECT platform, count(*) as totalGames,  sum(dlCount) as totalDl, sum(filesize) as totalSize from games group by platform");
            $platforms = array();
            while (($row = $queryDlCounts->fetch_assoc())) :
                $platforms[$row['platform']] = array('totalGames' => $row['totalGames'], 'totalDl' => $row['totalDl'], 'totalSize' => $row['totalSize']);
            endwhile;

            $sql = "SELECT * FROM platforms order by platform";
            $result = $database->query($sql);

            while (($row = $result->fetch_assoc())) :
                $platform = $row['platform'];
                $id = $row['id'];
                $htmlString = '<div class="card platform" id="' . $id . '">
                        <img width="100%" src="/images/games/systems/' . $platform . '.png">
                        <p>' . $platform . '</p>
                        <p>Juegos: ' . $platforms[$id]['totalGames'] . '</p>
                        <p>Descargas: ' . $platforms[$id]['totalDl'] . '</p>
                        <p>Tamaño de la base de datos: ' . sizeString($platforms[$id]['totalSize']) . '</p>
                    </div>';
                echo $htmlString;
            endwhile;
            ?>
        </div>
    </div>
    <div class="category">
        <div class="content-text">Ultimas Actualizaciones</div>
    </div>
    <div class="z-flow">
        <div class="container-cards">
            <?php
            $sql = 'SELECT g.id as id, name, p.platform as platform FROM games g inner join platforms p on p.id = g.platform order by g.id desc LIMIT 25';

            $result = $database->query($sql);
            $type = 0;

            while ($row = $result->fetch_assoc()) :
                $id = $row['id'];
                $name = $row['name'];
                $platform = $row['platform'];
                $image = '/images/games/covers/' . $id . '.png';
                if (file_exists(IMAGES_ROOT_LOCAL . $image) === false) :
                    $image = '/images/nocover.png';
                endif;
                include 'widgets/card.php';
            endwhile;
            ?>
        </div>
    </div>
    <div class="category">
        <div class="content-text">Top juegos definidos por los usuarios</div>
    </div>
    <div class="z-flow">
        <div class="container-cards">
            <?php
            $sql = 'SELECT g.id as id, name, p.platform as platform, avg(score) as avg FROM games g inner join platforms p on p.id = g.platform inner join rel_games_users rel on rel.idgame = g.id group by idgame order by avg desc LIMIT 25';

            $result = $database->query($sql);
            $type = 1;

            while ($row = $result->fetch_assoc()) :
                $id = $row['id'];
                $name = $row['name'] . "<br>Nota de usuario: " .  (float) $row['avg'] . "/10";
                $platform = $row['platform'];
                $image = '/images/games/covers/' . $id . '.png';
                if (file_exists(IMAGES_ROOT_LOCAL . $image) === false) :
                    $image = '/images/nocover.png';
                endif;
                include 'widgets/card.php';
            endwhile;
            ?>
        </div>
    </div>
</div>