<?php
include "../config.php";
include "../vendor/autoload.php";

use Archive7z\Archive7z;

$sql = 'SELECT g.id, g.name, g.filesize, g.dlcount, p.platform FROM games g inner join platforms p on g.platform = p.id where g.id = ' . $_GET['id'];

$result = $database->query($sql);
if ($result->num_rows) :
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $name = $row['name'];
    $filesize = $row['filesize'];
    $dlCount = $row['dlcount'];
    $platform = $row['platform'];
endif;

$file = 'E:/AZURE_CONSOLES/' . $platform . '/' . $name . '/' . $name . '.7z'; 

$sql = 'SELECT score FROM rel_games_users where iduser = ' . $_SESSION['id'] . ' AND idgame = ' . $id;

$result = $database->query($sql);
if($result->num_rows) :
    $row = $result->fetch_assoc();
    $index = $row['score'];
endif;

$image = '/images/games/covers/' . $id . '.png';

if (file_exists(IMAGES_ROOT_LOCAL . $image) === false) :
    $image = '/images/nocover.png';
endif;

?>


<div style="width: 100%;  /**height: 100%*/">
    <div class="game-cover-score" style="padding: 10px; box-sizing: border-box; color: #AAAAAA; border-right: 1px solid black">
        <img src="<?php echo $image ?>" style="width: 100%;">
        <?php echo 'Nombre: ' . $name . '<br>';?>
        <div style="width: 100%; padding: 6px 12px; background-color: #444444; box-sizing: border-box;">
            <?php echo 'Tu nota: '; include "./widgets/score-options.php"; ?>
        </div>
        
    </div>
    <div class="game-info-wrapper">
        <table style="width: 60%; margin: 10px auto; ">
            <tbody>
                <tr>
                    <td valign="top" style="padding: 10px;">
                        <b>Información del juego</b><br>

                        <?php

                        echo 'Nombre: ' . $name . '<br>';
                        echo 'Descargar: <a id="download" href="/ajax/down.php?contentId=' . $id . '&contentType=1">' . $name . '.7z</a><br>';
                        echo 'Plataforma: ' . $platform . '<br>';
                        echo 'Tamaño de la descarga: ' . sizeString(filesize($file)) . '<br>';
                        echo 'Número de descargas: ' . $dlCount . '<br>';
                        echo '<b>Lista de Archivos</b><br>';

                        ?>
                        <table class="roms">
                            <tbody>
                                <tr>
                                    <th>Nombre de archivo</th>
                                    <th>Tamaño</th>
                                    <th>CRC</th>
                                </tr>
                                <?php
                                if (file_exists($file)) :
                                    if ($platform == "Wii U") : // debido a problemas de RAM, los zip de Wii U no pueden abrirse
                                        echo '<tr>
                                    <td>' . basename($file) . '</td>
                                    <td>' . sizeString(filesize($file)) . '</td>
                                    <td>' . strtoupper(dechex(crc32($file))) . '</td>
                                </tr>';
                                    else :
                                        $zip = new Archive7z($file);
                                        foreach ($zip->getEntries() as $entry) :
                                            $entrySize = $entry->getSize();
                                            if ($entrySize > 0) :
                                                echo '<tr>
                                            <td>' . basename($entry->getPath()) . '</td>
                                            <td>' . basename(sizeString($entrySize)) . '</td>
                                            <td>' . basename($entry->getCrc()) . '</td>
                                        </tr>';
                                            endif;
                                        endforeach;
                                    endif;
                                else :
                                    echo "The game is not available yet! We're working on the zip...";
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="game-screenshots" style="width:90%; margin: 10px auto;">
            <b>Capturas de pantalla</b><br>
            <?php 
                $screenshotsFolder = IMAGES_ROOT_LOCAL . GAME_SCREENSHOTS . "/" . $id . "/*";
                $index = 0;
                foreach (glob($screenshotsFolder, ) as $screenshot){
                    echo '<img style="max-width: 31.85%; margin: 0.5%;" src="' . GAME_SCREENSHOTS . "/" . $id . "/" . $index . '.png">';
                    $index++;
                }
            ?>
        </div>
    </div>
</div>
