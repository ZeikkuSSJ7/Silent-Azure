<?php include "../config.php" ?>
<div style="width: 100%;">
    <?php include "widgets/search-bar.html" ?>
</div>
<div class="master-main-page">
    <div class="category">
        <div class="content-text">Búsqueda por letra</div>
    </div>
     <?php include "widgets/search-by-letter.html";?>
    <div class="category">
        <div class="content-text">Ultimas Actualizaciones</div>
    </div>
    <div class="z-flow">
        <div class="container-cards">
            <?php
            $sql = 'SELECT * FROM animes order by id desc LIMIT 25';

            $result = $database->query($sql);
            $type = 0;

            while ($row = $result->fetch_assoc()) :
                $id = $row['id'];
                $name = $row['name'];
                $image = '/images/animes/covers/' . $id . '.png';
                include 'widgets/card.php';
            endwhile;
            ?>
        </div>
    </div>
    <div class="category">
        <div class="content-text">Top animes definidos por los usuarios</div>
    </div>
    <div class="z-flow">
        <div class="container-cards">
            <?php
            $sql = 'SELECT id, name, type, avg(score) as avg FROM animes a inner join rel_animes_users rel on rel.idanime = a.id group by idanime order by avg desc LIMIT 25';

            $result = $database->query($sql);
            $type = 0;

            while ($row = $result->fetch_assoc()) :
                $id = $row['id'];
                $name = $row['name'] . "<br>Nota de usuario: " .  (float) $row['avg'] . "/10";
                $image = '/images/animes/covers/' . $id . '.png';
                include 'widgets/card.php';
            endwhile;
            ?>
        </div>
    </div>
</div>