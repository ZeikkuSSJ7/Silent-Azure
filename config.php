<?php
if (!isset($database)) :
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'phpmyadmin');
    define('DB_PASSWORD', 'qWOKferZEIK93tPoKENe24');
    define('DB_NAME', 'silentazure');
    define("ROOT_LOCAL", $_SERVER["DOCUMENT_ROOT"]);
    define("ROOT_HOST", 'http://' . $_SERVER["HTTP_HOST"]);
    define('USER_IMAGE_DIR', '/images/users');
    define('IMAGES_ROOT_LOCAL', 'C:/ServerData');
    define('AZURELOG_IMAGES_DIR', '/images/azurelog/covers');
    define('GAME_SCREENSHOTS', '/images/games/screenshots');

    session_start();

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $database = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($database->connect_error) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    }

    /**
     * Comprueba si puedes descargar el contenido indicado por los argumentos
     * @param $database la base de datos
     * @param $userId el ID del usuario
     * @param $contentId el ID del contenido
     * @param $contentType el ID del tipo de contenido
     */
    function canDownloadContent(mysqli $database, int $userId, int $contentId, int $contentType)
    {
        $sql = 'SELECT iduser FROM rel_users_downloads where iduser = ' . $userId . ' AND idtype = ' . $contentType . ' AND idcontent = ' . $contentId;

        $result = $database->query($sql);
        if ($result->num_rows) :
            return true;
        endif;

        switch ($contentType):
            case 0:
                $contentType = "animesdownloads";
                break;
            case 1:
                $contentType = "gamesdownloads";
                break;
            case 2:
                $contentType = "soundtracksdownloads";
                break;
        endswitch;

        $sql = 'SELECT ' . $contentType . ' FROM users WHERE id = ' . $userId;

        $result = $database->query($sql);
        if ($result->num_rows) :
            $row = $result->fetch_assoc();
            if ($row[$contentType]) :
                $sql = 'UPDATE users set ' . $contentType . ' = ' . $contentType . ' - 1 where id = ' . $userId;
                $database->query($sql);
                if ($database->affected_rows) :
                    return true;
                else :
                    return false;
                endif;
            else :
                return false;
            endif;
        else :
            return false;
        endif;
    }

    /**
     * Devuelve el token de usuario
     */
    function getUserToken()
    {
        return md5($_SESSION['email'] . '[]' . $_SESSION['username']);
    }

    /**
     * Comprueba si la variable está vacía de forma customizada
     * @param &$var la variable a comprobar
     */
    function isNull(&$var)
    {
        return isset($var) ? $var : '';
    }

    /**
     * Envía el archivo al usuario a través del módulo X-Sendfile
     * @param $filepath la ruta de descarga al archivo
     * @param $filename el nombre del archivo adjunto
     */
    function xSendfile($filepath, $filename)
    {
        if (file_exists($filepath)) :
            header('Content-type: application/x-7z-compressed');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('X-Sendfile: ' . $filepath);
            exit;
        else :
            echo 'The requested file does not exist in the server';
        endif;
    }

    /**
     * Actualiza la lista de descargas del usuario
     * @param $database la base de datos
     * @param $userId el ID del usuario
     * @param $contentId el ID del contenido
     * @param $contentType el ID del tipo de contenido
     */
    function updateDownloadContent(mysqli $database, int $userId, int $contentId, int $contentType)
    {
        $sql = 'SELECT iduser FROM rel_users_downloads where iduser = ' . $userId . ' AND idtype = ' . $contentType . ' AND idcontent = ' . $contentId;

        $result = $database->query($sql);
        if ($result->num_rows == 0) :
            $sql = 'INSERT INTO rel_users_downloads values (' . $userId . ', ' . $contentType . ', ' . $contentId . ')';
            $database->query($sql);
        endif;
    }

    /**
     * Devuelve una cadena con el tamaño del archivo entrante formateado
     * @param $fileSize el tamaño del archivo a formatear
     */
    function sizeString($fileSize)
    {
        $len = strlen($fileSize);
        if ($len > 3 && $len <= 6) :                      // kb
            $fileSize /= 1024;
            $fileSize = round($fileSize, 2) . " KB";
        elseif ($len > 6 && $len <= 9) :                  // mb
            $fileSize /= (1024 * 1024);
            $fileSize = round($fileSize, 2) . " MB";
        elseif ($len > 9 && $len <= 12) :                 // gb
            $fileSize /= (1024 * 1024 * 1024);
            $fileSize = round($fileSize, 2) . " GB";
        elseif ($len > 12 && $len <= 15) :                 // tb
            $fileSize /= (1024 * 1024 * 1024 * 1024);
            $fileSize = round($fileSize, 2) . " TB";
        else :                                            // bytes
            $fileSize = round($fileSize, 2) . " bytes";
        endif;
        return $fileSize;
    }

    /**
     * Comprueba si la cadena indicada termina con la aguja indicada
     * @param $target la cadena a comprobar
     * @param $needle la aguja (regexp) a buscar
     */
    function endsWith($target, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($target, -$length) === $needle);
    }

    /**
     * Formatea la longitud de un número para su uso en la celda de una pista de una banda sonora
     */
    function parseLength(int $length)
    {
        $minutes = 0;
        while ($length >= 60) :
            $minutes++;
            $length -= 60;
        endwhile;
        $minutes = strlen($minutes . "") == 1 ? "0" . $minutes : $minutes;
        $seconds = strlen($length . "") == 1 ? "0" . $length : $length;
        return $minutes . ":" . $seconds;
    }

endif;
