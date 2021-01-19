<?php
include "../../config.php";

if (isset($_GET['id'])) :
    $id = $_GET['id'];
endif;
if (isset($id) && !empty($id)) :
    $sql = 'SELECT iduser FROM azurelog_rel_users_games where iduser = ' . $_SESSION['id'] . ' AND idgame = ' . $id;
    $result = $database->query($sql);
    $isInDB = $result->num_rows;
    if ($isInDB) :
        $sql = 'SELECT ag.game_title, ag.release_date, ag.overview, ag.rating, p.name as platform, 
        d.name as developer, pu.name as publisher, getGenres(ag.id) as genres, 
        relgu.isfavourite as favourite, sc.id as score, st.id as statusId, st.value as status,
        relgu.startdate, relgu.finishdate, relgu.comment, relgu.playtime, relgu.replaying
        FROM azurelog_games ag 
        inner join azurelog_rel_users_games relgu on ag.id = relgu.idgame 
        inner join azurelog_platforms p on ag.platform = p.id
        inner join azurelog_developers d on ag.developer = d.id
        inner join azurelog_publishers pu on ag.publisher = pu.id
        inner join azurelog_score_types sc on relgu.score = sc.id
        inner join azurelog_status_types st on relgu.status = st.id
        inner join users u on u.id = relgu.iduser 
        where relgu.iduser = ' . $_SESSION['id'] . ' AND relgu.idgame = ' . $id;
    else :
        $sql = 'SELECT ag.game_title, ag.release_date, ag.overview, ag.rating, p.name as platform, 
        d.name as developer, pu.name as publisher, getGenres(ag.id) FROM azurelog_games ag 
        inner join azurelog_platforms p on ag.platform = p.id
        inner join azurelog_developers d on ag.developer = d.id
        inner join azurelog_publishers pu on ag.publisher = pu.id
        where ag.id = ' . $id;
    endif;

    $result = $database->query($sql);
    $row = $result->fetch_assoc();

    $game_title = $row['game_title'];
    $release_date = isNull($row['release_date']);
    $overview = isNull($row['overview']);
    $rating = isNull($row['rating']);
    $platform = isNull($row['platform']);
    $developer = isNull($row['developer']);
    $publisher = isNull($row['publisher']);
    $genres = isNull($row['genres']);
    $favourite = isNull($row['favourite']);
    $score = isNull($row['score']);
    $statusId = isNull($row['statusId']);
    $status = isNull($row['status']);
    $startdate = isNull($row['startdate']);
    $finishdate = isNull($row['finishdate']);
    $comment = isNull($row['comment']);
    $playtime = isNull($row['playtime']);
    $replaying = isNull($row['replaying']);
    if (is_numeric($playtime)) :
        $playtime = round($playtime, 2);
    endif;

    $sql = 'SELECT playtime from azurelog_rel_users_games where idgame = ' . $id;
    $result = $database->query($sql);
    $media = 0;
    $count = 0;
    while ($row = $result->fetch_assoc()) :
        $rowPlaytime = $row['playtime'];
        if ($rowPlaytime != 0 && $rowPlaytime != "") :
            $media += $rowPlaytime;
            $count++;
        endif;
    endwhile;
    if ($count == 0) $count  = 1;
    $media = round($media / $count, 2);

endif;
?>

<div class="game-data-wrapper">
    <div class="game-cover">
        <img src="/images/azurelog/covers/<?php echo $id ?>.png" class="img-cover">
    </div>
    <div class="game-data">
        <table style="background-color: #444444; width: 100%; margin: auto;">
            <tr>
                <td class="padding" style="font-size: 22px;" colspan="2"> <div style="float: left; font-size: 18px;"><input type="checkbox" id="favourite" <?php if ($favourite == "1") echo "checked" ?> > Favorito</div> <?php echo $game_title ?> <div style="float: right; font-size: 18px;"><input type="checkbox" id="replaying" <?php if ($replaying == "1") echo "checked"; ?>> Rejugando</div>  </td>
            </tr>
            <?php if ($isInDB) :  // Si tienes el juego en la lista tuya, te aparecen sus datos específicos ?> 
                <tr>
                    <td class="padding game-user-info score">
                        Puntuación: <?php echo $score == 0 ? 'Sin clasificar' : $score ?>
                    </td>
                    <td class="padding game-user-info status">
                        Estado: <?php echo $status; ?>
                    </td>
                </tr>
                <tr>
                    <td class="padding game-user-info comment" colspan="2">Comentario: <?php echo $comment ?></td>
                </tr>
            <?php else : // Si no, aparece la opción de añadirlo ?>
                <tr>
                    <td class="padding game-user-info no-game" colspan="2" rowspan="1">Este juego no está en tu lista. Haz click aquí para añadirlo.</td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="border-green">Plataforma:</td>
                <td class="border-green">Publicador:</td>
            </tr>
            <tr>
                <td class="padding border-green"><?php echo empty($platform) ? 'Plataforma desconocida' : $platform ?></td>
                <td class="padding border-green"><?php echo empty($publisher) ? 'Publicador desconocido' : $publisher ?></td>
            </tr>
            <tr>
                <td class="border-green">Desarrollador:</td>
                <td class="border-green">Fecha de salida:</td>
            </tr>
            <tr>
                <td class="padding border-green"><?php echo empty($developer) ? 'Desarrollador desconocido' : $developer ?></td>
                <td class="padding border-green"><?php echo empty($release_date) ? 'Fecha de salida desconocida' : $release_date  ?></td>
            </tr>
            <tr>
                <td class="border-green">Clasificación:</td>
                <td class="border-green">Géneros:</td>
            </tr>
            <tr>
                <td class="padding border-green"><?php echo empty($rating) ? 'Clasificación desconocida' : $rating ?></td>
                <td class="padding border-green"><?php echo empty($genres) ? 'Géneros desconocidos' : $genres ?></td>
            </tr>
            <tr>
                <td class="padding border-green start-date">Fecha de inicio: <?php echo empty($startdate) ? 'Sin definir' : $startdate ?></td>
                <td class="padding border-green finish-date">Fecha de finalización: <?php echo empty($finishdate) ? 'Sin definir' : $finishdate ?></td>
            </tr>
            <tr>
                <td class="padding border-green playtime">Tiempo de juego: <?php echo empty($playtime) ? 'Sin definir' : $playtime . ' horas' ?></td>
                <td class="padding border-green">Media de juego: <?php echo empty($media) ? 'Nadie ha completado este juego' : $media . ' horas' ?></td>
            </tr>

        </table>
    </div>
</div>
<div style="background-color: #444444;">
    <div class="border-blue">Descipción:</div>
    <div style="padding: 6px;" class="border-blue">
        <?php echo empty($overview) ? 'No hay descripción de este juego' : $overview ?>
    </div>
</div>
<div class="dialog score">
    <div style="display: flex; height: 100%;">
        <div class="modal-body">
            <h3>Puntuación</h3>
            <?php $index = $score;
            include "widgets/score-options.php"; ?>
            <button>Cambiar puntuación</button>
        </div>
    </div>
</div>
<div class="dialog status">
    <div style="display: flex; height: 100%;">
        <div class="modal-body">
            <h3>Estado</h3>
            <?php $index = $statusId;
            include "widgets/status-options.php"; ?>
            <button>Cambiar estado</button>
        </div>
    </div>
</div>
<div class="dialog comment">
    <div style="display: flex; height: 100%;">
        <div class="modal-body">
            <h3>Comentario</h3>
            <input type="text" style="color: black; border-radius: 0em;" id="comment" value="<?php echo $comment ?>">
            <button>Cambiar estado</button>
        </div>
    </div>
</div>
<div class="dialog start-date">
    <div style="display: flex; height: 100%;">
        <div class="modal-body">
            <h3>Fecha de inicio</h3>
            <input type="date" style="color: black; border-radius: 0em;" id="start-date" value="<?php echo $startdate ?>">
            <button>Cambiar fecha</button>
        </div>
    </div>
</div>
<div class="dialog finish-date">
    <div style="display: flex; height: 100%;">
        <div class="modal-body">
            <h3>Fecha de finalización</h3>
            <input type="date" style="color: black; border-radius: 0em;" id="finish-date" value="<?php echo $finishdate ?>">
            <button>Cambiar fecha</button>
        </div>
    </div>
</div>
<div class="dialog playtime">
    <div style="display: flex; height: 100%;">
        <div class="modal-body">
            <h3>Tiempo de juego</h3>
            <input type="number" style="color: black; border-radius: 0em;" id="playtime" value="<?php echo $playtime ?>">
            <button>Cambiar tiempo</button>
        </div>
    </div>
</div>