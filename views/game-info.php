<?php
include "../config.php";
include "../vendor/autoload.php";

use Archive7z\Archive7z;

$sql = 'SELECT g.id, g.name, g.location, g.filesize, g.dlcount, p.platform, g.imageformat, g.releasedate, g.developer FROM games g inner join platforms p on g.platform = p.id where g.id = ' . $_GET['id'];

$result = $database->query($sql);
if ($result->num_rows) :
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $name = $row['name'];
    $filename = str_replace('/', '', $row['location']) ;
    $filesize = $row['filesize'];
    $dlCount = $row['dlcount'];
    $platform = $row['platform'];
    $developer = $row['developer'];
    $releasedate = $row['releasedate'];
    $imageformat = $row['imageformat'];
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


<div style="width: 100%;">
    <div class="game-info-wrapper" style="display: flex">
        <div style="width: 60%;">
            <div>
                <h2><?php echo $name ?></h2>
            </div>
            <div style="display: flex;">
                <img src="<?php echo $image ?>" style="max-width: 65%; max-height: 55vh; margin: 10px auto">
            </div>
            <div>
                <table class="rom-info"><thead><tr><th colspan="2">GAME INFORMATION</th></tr></thead>
			    	<tbody>
			    		<tr>
                            <th>Game Name</th>
			    		    <td><?php echo $name ?></td>
                        </tr>
			    		<tr>
                            <th>Console</th>
			    		    <td><?php echo $platform ?></td>
                        </tr>
			    		<tr>
                            <th>Game Release</th>
			    		    <td><?php echo $releasedate ?></td>
                        </tr>
			    		<tr>
                            <th>Developer</th>
			    		    <td><?php echo $developer ?></td>
                        </tr>
			    		<tr>
                            <th>Download count</th>
			    		    <td><?php echo $dlCount ?> times downloaded</td>
                        </tr>
			    		<tr>
                            <th>File Size</th>
			    		    <td><?php echo sizeString($filesize) ?></td>
                        </tr>
			    		<tr>
                            <th>Image Format</th>
			    		    <td><?php echo $imageformat ?> file</td>
                        </tr> 					
			    		<tr>
                            <th>Users Score</th>
			    		    <td>
                                <div style="width: 100%; padding: 6px 12px; background-color: #444444; box-sizing: border-box;">
                                    <?php echo 'Tu nota: '; include "./widgets/score-options.php"; ?>
                                </div>
                            </td>
                        </tr>
			    	</tbody>
			    </table>
                <?php echo 'Descargar: <a id="download" href="/ajax/down.php?contentId=' . $id . '&contentType=1">' . $filename . '</a><br>'; ?>
            </div>
        </div>
        <div class="game-screenshots" style="width:40%; margin: 10px auto;">
            <b>Capturas de pantalla</b><br>
            <?php 
                $screenshotsFolder = IMAGES_ROOT_LOCAL . GAME_SCREENSHOTS . "/" . $id . "/*";
                $index = 0;
                foreach (glob($screenshotsFolder, ) as $screenshot){
                    echo '<img style="max-width: 49%; margin: 0.5%;" src="' . GAME_SCREENSHOTS . "/" . $id . "/" . $index . '.png">';
                    $index++;
                }
            ?>
        </div>
    </div>
</div>
