<!DOCTYPE html>
<html style="width: 100%;" lang="en">
<?php

include "config.php";

if (isset($_GET['uid']) && !empty($_GET['uid'])) :

    $uid = $_GET['uid'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = 'SELECT id FROM referrals where touser = ' . $uid . ' AND ip = "' . $ip . '"';

    $result = $database->query($sql);

    if ($result->num_rows) :
        $sql = 'UPDATE referrals SET nexttime = NOW() + INTERVAL 1 DAY WHERE ip = "' . $ip . '" AND NOW() > nexttime';
    else :
        $sql = 'INSERT INTO referrals (touser, ip, nexttime) VALUES (' . $uid . ', "' . $ip . '", now() + interval 1 day)';
    endif;

    $result = $database->query($sql);
    $numrows = $database->affected_rows;

    if ($numrows) :
        $sql = 'UPDATE users SET 
                animesdownloads = animesdownloads + 1,  
                gamesdownloads = gamesdownloads + 1,  
                soundtracksdownloads = soundtracksdownloads + 1 
                WHERE id = ' . $uid;

        $result = $database->query($sql);
    endif;
endif;

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) :
    header('Location: login.php');
endif;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/icon.png">
    <link rel="stylesheet" href="/css/jquery-ui.min.css">
    <?php
    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) :
        echo '<link rel="stylesheet" href="/css/index-mobile.css">';
        echo '<link rel="stylesheet" href="/css/sections-mobile.css">';
        echo '<link rel="stylesheet" href="/css/widgets-mobile.css">';
        echo '<link rel="stylesheet" href="/css/player-mobile.css">';
        $isMobile = true;
    else :
        echo '<link rel="stylesheet" href="/css/index.css">';
        echo '<link rel="stylesheet" href="/css/sections.css">';
        echo '<link rel="stylesheet" href="/css/widgets.css">';
        echo '<link rel="stylesheet" href="/css/player.css">';
        $isMobile = false;
    endif;
    ?>
    
    <title>Silent Azure</title>
</head>

<body>
    <div style="width: 100%; height: 100%; display: flex; flex-direction: column;">
        <div class="user-menu">
            <div class="user-controls" style="background-color: rgb(1, 101, 252);">
                <div class="back-button"></div>
                <div class="drawer-trigger"></div>
                <div class="user-data">
                    <div style="height: 100%; display: flex;">
                        <div class="profile"></div>
                        <div class="content-text" style="color: black;">
                            <?php echo $_SESSION['username'] ?>
                        </div>
                    </div>
                    <div class="dropdown-content">
                        <a href="#" class="user-profile-link">Perfil</a>
                        <a href="logout.php" style="color: cyan;">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-container">
            <div class="drawer">
                <img src="/images/header.png" style="width: 100%;">
                <div style="height: 75%;" class="web-contents">
                    <div class="content-button" style="background-color: #111111;">
                        <i class="icon-search"></i>
                        <div class="content-text">Actualizaciones recientes</div>
                    </div>
                    <div class="content-button">
                        <i class="icon-anime"></i>
                        <div class="content-text">Anime</div>

                    </div>
                    <div class="content-button">
                        <i class="icon-games"></i>
                        <div class="content-text">Games</div>
                    </div>
                    <div class="content-button">
                        <i class="icon-soundtracks"></i>
                        <div class="content-text">Soundtracks</div>
                    </div>
                    <a href="azurelog" style="text-decoration: none;" class="content-button">
                        <i class="icon-azurelog"></i>
                        <div class="content-text">AzureLog</div>
                    </a>
                </div>
            </div>
            <div style="height: 100%; box-sizing: border-box; <?php if ($isMobile) echo 'width: 100%;'; else echo 'width: 78%'; ?>">
                <div class="master-wrapper"></div>
            </div>
        </div>
    </div>

    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/jquery.ui.touch-punch.min.js"></script>
    <script src="/js/index.js"></script>
    <script src="/js/player.js"></script>
    <script src="/js/sections-loaded.js"></script>
    <script src="/js/player-functions.js"> </script>
    <script src="/js/events.js"></script>
    <script src="/js/util.js"></script>
</body>

</html>