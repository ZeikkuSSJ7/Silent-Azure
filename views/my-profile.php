<?php
include "../config.php";

$username = $_SESSION['username'];
$id = $_SESSION['id'];
$email = $_SESSION['email'];

$sql = "SELECT animesdownloads, gamesdownloads, soundtracksdownloads FROM users WHERE id = " . $id;

$result = $database->query($sql);

if ($result->num_rows) :
    $row = $result->fetch_assoc();
    $animesdownloads = $gamesdownloads = $soundtracksdownloads = "";

    $animesdownloads = $row['animesdownloads'];
    $gamesdownloads = $row['gamesdownloads'];
    $soundtracksdownloads = $row['soundtracksdownloads'];
endif;

$sql = 'SELECT a.id, name, typename from rel_users_downloads rel inner join animes a on a.id = rel.idcontent inner join animetypes aty on aty.id = a.type where iduser = ' . $id . ' AND idtype = 0 order by name';

$result = $database->query($sql);

$animes = array();
$cont = 0;
while ($row = $result->fetch_assoc()) :
    $animes[$cont++] = array('name' => $row['name'], 'id' => $row['id'], 'type' => $row['typename']);
endwhile;

$sql = 'SELECT g.id, name, p.platform from rel_users_downloads rel inner join games g on g.id = rel.idcontent inner join platforms p on g.platform = p.id where iduser = ' . $id . ' AND idtype = 1 order by name';

$result = $database->query($sql);

$games = array();
$cont = 0;
while ($row = $result->fetch_assoc()) :
    $games[$cont++] = array('name' => $row['name'], 'id' => $row['id'], 'platform' => $row['platform']);
endwhile;

$sql = 'SELECT id, name from rel_users_downloads rel inner join soundtracks s on s.id = rel.idcontent where iduser = ' . $id . ' AND idtype = 2 order by name';

$result = $database->query($sql);

$soundtracks = array();
$cont = 0;
while ($row = $result->fetch_assoc()) :
    $soundtracks[$cont++] = array('name' => $row['name'], 'id' => $row['id']);
endwhile;

$img = USER_IMAGE_DIR . '/' . getUserToken() . '.png';
$img = file_exists(IMAGES_ROOT_LOCAL . $img) ? $img : '/images/users/nopic.png';

?>
<style>

</style>
<div class="category" style="border: 1px solid black; box-sizing: border-box;">
    <div class="content-text">Perfil</div>
</div>
<div>
    <div class="form-image-wrapper">
        <form id="form-image" method="POST" enctype="multipart/form-data">
            <div class="form-img-wrapper">
                <img src="<?php echo $img . '?nocache=' . rand(); ?>" class="profile-pic" style="max-width: 100%; max-height: 100%; margin: auto;">
            </div>
            <input type="file" name="image" id="file-upload" style="display: none;">
            <button class="upload-image">Subir Imagen de perfil</button>
        </form>
    </div>

    <div class="user-info-wrapper">
        <div class="user category">
            <h4>Datos de usuario</h4>
        </div>
        <div class="user-info-data">
            <b>Id de usuario:</b> <?php echo $id; ?><br>
            <b>Nombre de usuario:</b> <?php echo $username; ?><br>
            <b>Correo electrónico:</b> <?php echo $email; ?><br>
            <?php if ($_SESSION['superuser'] == 0) : ?>
                <b>Descargas de anime disponibles:</b> <?php echo $animesdownloads; ?><br>
                <b>Descargas de juegos disponibles:</b> <?php echo $gamesdownloads; ?><br>
                <b>Descargas de bandas sonoras disponibles:</b> <?php echo $soundtracksdownloads; ?><br>
            <?php else : ?>
                <b>¡Eres superusuario! ¡A descargar como un descosido! </b>
            <?php endif; ?>

        </div>
        <div class="user category">
            <h4>Mis links</h4>
        </div>
        <div class="user-info-data">
            Tus links para referentes se encuentran aquí en dos formatos para tu uso: <br>
            Link clickable <br>
            <textarea style="color: black;" cols="45" rows="2">http://silentazure.net/?uid=<?php echo $id ?></textarea>
            <br>
            Link HTML: <br>
            <textarea style="color: black;" cols="45" rows="2"><a href="http://silentazure.net/?uid=<?php echo $id ?>">Silent Azure</a></textarea>
        </div>
    </div>

</div>
<div class="category" style="border: 1px solid black; box-sizing: border-box;">
    <div class="content-text">Contenidos descargados</div>
</div>
<div class="user-content-downloaded-wrapper">
    <div class="user category">
        <h4>Anime descargados<div style="float: right;">Tipo</div>
        </h4>
    </div>
    <?php
    foreach ($animes as $anime) :
    ?>
        <div class="user-content-downloaded">
            <span data-id="<?php echo $anime['id'] ?>" class="content content-anime"><?php echo $anime['name'] ?></span>
            <div style="float: right;"><?php echo $anime['type'] ?> </div>
        </div>
    <?php
    endforeach;
    ?>
</div>
<div class="user-content-downloaded-wrapper">
    <div class="user category">
        <h4>Juegos descargados<div style="float: right;">Plataforma</div>
        </h4>
    </div>
    <?php
    $cont = 0;
    foreach ($games as $game) :
    ?>
        <div style="margin: 6px; line-height: 20px; text-align: left;">
            <span data-id="<?php echo $game['id'] ?>" class="content content-game"><?php echo $game['name'] ?></span>
            <div style="float: right;"><?php echo $game['platform'] ?> </div>
        </div>
    <?php
    endforeach;
    ?>
</div>
<div class="user-content-downloaded-wrapper">
    <div class="user category">
        <h4>Bandas sonoras descargadas</h4>
    </div>
    <?php
    $cont = 0;
    foreach ($soundtracks as $soundtrack) :
    ?>
        <div style=" margin: 6px; line-height: 20px; text-align: left;">
            <span data-id="<?php echo $soundtrack['id'] ?>" class="content content-soundtrack"><?php echo $soundtrack['name'] ?></span>
        </div>
    <?php
    endforeach;
    ?>
</div>