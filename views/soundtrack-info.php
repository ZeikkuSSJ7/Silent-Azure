<?php
include "../config.php";
include "../vendor/autoload.php";

use wapmorgan\Mp3Info\Mp3Info;

$sql = "SELECT * FROM soundtracks WHERE id = " . $_GET['id'] . " ORDER BY name";
$result = $database->query($sql);

$row = $result->fetch_assoc();

$id = $row['id'];
$name = $row['name'];

$localDir = 'E:/AZURE_MULTIMEDIA/Soundtracks/' . $name;
$hostLink = 'http://' . $_SERVER['HTTP_HOST'] . '/multimedia/Soundtracks/' . $name;

$files = glob($localDir . '/*.mp3');
$firstSong = basename($files[0]);

$sql = 'SELECT score FROM rel_soundtracks_users where iduser = ' . $_SESSION['id'] . ' AND idsoundtrack = ' . $id;

$result = $database->query($sql);
if ($result->num_rows) :
    $row = $result->fetch_assoc();
    $index = $row['score'];
endif;

if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) :
    $isMobile = true;
else :
    $isMobile = false;
endif;

?>

<div style="width: 100%; height: 100%">
    <div class="soundtrack-cover-score" style="padding: 10px; box-sizing: border-box; color: #AAAAAA; border-right: 1px solid black">
        <img class="img-cover" src="/images/soundtracks/covers/<?php echo $id ?>.png" style="width: 100%;">
        <div style="width: 100%; padding: 6px 12px; background-color: #444444; box-sizing: border-box;">
            <?php echo 'Nombre: ' . $name . '<br>'; ?>
            <?php echo 'Tu nota: ';
            include "./widgets/score-options.php"; ?>
        </div>

    </div>
    <div class="soundtrack-info-wrapper">
        <div class="audio-list-player">
            <div class="content-text" style="display: flex;">
                <a style="margin: auto; padding: 10px;" href="/ajax/down.php?contentId=<?php echo $id ?>&contentType=2">Click here to download all songs at once!</a>
            </div>
            <div class="song-list">
                <div style="border: 1px solid black;">
                    <table style="margin: auto;">
                        <tr style="border: 1px solid black; background-color: #CCCC99;">
                            <th style="width: 15px"></th>
                            <th style="width: 7%; text-align: center;">#</th>
                            <th>Nombre de Canción</th>
                            <th style="width: 9%; text-align: center;">Longitud</th>
                            <th style="width: 65px; text-align: right;">MP3</th>
                            <th style="width: 15px;"></th>
                        </tr>
                        <?php
                        $count = 1;
                        foreach ($files as $file) :
                            if (endsWith($file, 'mp3')) :
                                $tag = new Mp3Info($file, true);
                                echo
                                    '<tr>
                                        <td class="play" style="height: 15px; text-align: center;"><img class="fake-link" src="/images/soundtracks/player/play.png" width="100%" height="100%" alt=""></td>
                                        <td style="text-align: center;">' . $count++ . '</td>
                                        <td>' . (isset($tag->tags['song']) ? $tag->tags['song'] : basename($file)) . '</td>
                                        <td style="text-align: center;">' . parseLength($tag->duration) . '</td>
                                        <td style="text-align: right;">' . sizeString(filesize($file)) . '</td>
                                        <td class="download" style="text-align: center;"><img class="fake-link" download src="/images/soundtracks/player/download.png" width="15px" height="15px" alt=""></td>
                                    </tr>';
                            endif;
                        endforeach;
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include "widgets/player.php"; ?>
</div>