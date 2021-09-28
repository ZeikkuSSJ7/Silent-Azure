<?php
include "../config.php";

if (isset($_GET['nointro'])){
    echo (str_replace('%20', ' ', $_GET['nointro']));
    xSendfile('E:/AZURE_CONSOLES/Vault/' . str_replace('%20', ' ', $_GET['nointro']), str_replace('%20', ' ', $_GET['nointro']));
}

if (isset($_GET['contentType']) && isset($_GET['contentId'])) :
    $contentId = $_GET['contentId'];
    $contentType = $_GET['contentType'];
    $userId = $_SESSION['id'];
    $superuser = $_SESSION['superuser'];
    
    if ($contentType == 2) : // Soundtracks

        if (canDownloadContent($database, $userId, $contentId, $contentType) || $superuser == "1") :

            updateDownloadContent($database, $userId, $contentId, $contentType);

            $result = $database->query('SELECT name FROM soundtracks where id = ' . $contentId);
            $row = $result->fetch_assoc();

            $name = $row['name'];
            $zip = 'E:/AZURE_MULTIMEDIA/Soundtracks/' . $name . '/' . $name . '.zip';

            xSendfile($zip, basename($zip));
        else :
            echo '<title>Oops!</title>Has superado el máximo de descargas de la web :(, para poder descargar más invita a un amigo a entrar en la web por medio de tu link de referente, disponible en la sección de opciones de usuario ;)';
        endif;

    elseif ($contentType == 1) : // Juegos

        if (canDownloadContent($database, $userId, $contentId, $contentType) || $superuser == "1") :

            updateDownloadContent($database, $userId, $contentId, $contentType);

            $result = $database->query('SELECT g.name, g.location, p.platform, location FROM games g INNER JOIN platforms p on g.platform = p.id where g.id = ' . $contentId);
            $row = $result->fetch_assoc();

            $file = 'E:/AZURE_CONSOLES/' . $row['platform'] . '/' . $row['name'] . $row['location'];

            xSendfile($file, basename($file));
        else :
            echo '<title>Oops!</title>Has superado el máximo de descargas de la web :(, para poder descargar más invita a un amigo a entrar en la web por medio de tu link de referente, disponible en la sección de opciones de usuario ;)';
        endif;

    endif;
elseif (isset($_GET['contentId']) && isset($_GET['v']) && isset($_GET['season'])) : // Anime
    $contentId = $_GET['contentId'];
    $video = $_GET['v'];
    $season = $_GET['season'];
    $userId = $_SESSION['id'];

    if (canDownloadContent($database, $userId, $contentId, 0) || $superuser == "1") :

        updateDownloadContent($database, $userId, $contentId, 0);

        $sql = 'SELECT name, location, typename from animes a inner join animetypes aty on a.type = aty.id where a.id = ' . $contentId;
        $result = $database->query($sql);
        $row = $result->fetch_assoc();
        $seasons = glob('E:/AZURE_MULTIMEDIA/Anime/' . $row['typename'] . $row['ftplocation'] . '/*');

        if (sizeof($seasons)) :
            if (!endsWith($seasons[0], 'mp4') && !endsWith($seasons[0], 'mkv')) :
                $seasonVideos = glob($seasons[$_GET['season']] . '/*');
                if (sizeof($seasonVideos)) :
                    $video = $seasonVideos[$_GET['v']];
                    xSendfile($video, basename($video));
                endif;
            else :
                $video = $seasons[$_GET['v']];
                xSendfile($video, basename($video));
            endif;
        endif;
    else :
        echo '<title>Oops!</title>Has superado el máximo de descargas de la web :(, para poder descargar más invita a un amigo a entrar en la web por medio de tu link de referente, disponible en la sección de opciones de usuario ;)';
    endif;

endif;
