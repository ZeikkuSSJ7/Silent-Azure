<?php
include "../../config.php";

$username = $_SESSION['username'];
$id = $_SESSION['id'];
$email = $_SESSION['email'];
$joined = $_SESSION["joined"];


$sql = 'SELECT status, score from azurelog_rel_users_games where iduser = ' . $id;

$result = $database->query($sql);

$playing = 0;
$planned = 0;
$hold = 0;
$dropped = 0;
$completed = 0;
$mastered = 0;
$mediumScore = 0;
$scoreCount = 0;

while ($row = $result->fetch_assoc()) :
    switch ($row['status']) {
        case 0:
            $planned++;
            break;
        case 1:
            $playing++;
            break;
        case 2:
            $hold++;
            break;
        case 3:
            $dropped++;
            break;
        case 4:
            $completed++;
            break;
        case 5:
            $mastered++;
            break;
    }
    $score = $row['score'];
    if ($score){
        $mediumScore += $score;
        $scoreCount++;
    }
endwhile;

$img = USER_IMAGE_DIR . '/' . getUserToken() . '.png';
$img = file_exists(IMAGES_ROOT_LOCAL . $img) ? $img : '/images/users/nopic.png';

?>
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
            <b>Se unió:</b> <?php echo $joined; ?><br>
        </div>
        <div style="padding: 6px 12px;">
            <table class="planned" style="max-width: 600px; width: 100%; border-collapse: collapse; margin: auto; background-color: #444444;">
                <tr>
                    <td class="planned" style="padding: 5px;">
                        Total de juegos:  <?php echo $result->num_rows ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;">
                        <table class="hold" style="margin: auto; width: 100%; border-collapse: collapse;">
                            <tr>
                                <td class="hold" style="padding: 5px;">Planeado jugar: <?php echo $planned ?></td>
                                <td class="hold" style="padding: 5px;">Jugando: <?php echo $playing ?></td>
                            </tr>
                            <tr>
                                <td class="hold" style="padding: 5px;">Abandonados: <?php echo $dropped ?></td>
                                <td class="hold" style="padding: 5px;">En espera: <?php echo $hold ?></td>
                            </tr>
                            <tr>
                                <td class="hold" style="padding: 5px;">Completados: <?php echo $completed ?></td>
                                <td class="hold" style="padding: 5px;">Dominados: <?php echo $mastered ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="mastered" style="padding: 5px;">Juegos completados: <?php echo $completed + $mastered ?></td>
                </tr>
                <tr>
                    <td class="completed" style="padding: 5px;">Media de puntuación: <?php echo round($mediumScore / $scoreCount, 2) ?></td>
                </tr>

            </table>
        </div>
    </div>

</div>