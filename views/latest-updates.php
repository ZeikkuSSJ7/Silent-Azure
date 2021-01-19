<div style="width: 100%; height: 100%;">
    <?php
include "../config.php";
    $sectionTitle = 'Anime - Añadido recientemente';
    $sectionName = 'animes';
    $type = 0;
    include 'widgets/section.php';

    $sectionTitle = 'Juegos - Añadido recientemente';
    $sectionName = 'games';
    $type = 1;
    include 'widgets/section.php';

    $sectionTitle = 'Bandas sonoras - Añadido recientemente';
    $sectionName = 'soundtracks';
    $type = 2;
    include 'widgets/section.php';
    ?>
</div>