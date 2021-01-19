<?php
include "../../config.php";
$data = json_decode(file_get_contents('php://input'));
$sql = 'SELECT id, password from users where username = "' . $data->username . '"';
$result = $database->query($sql);
$row = $result->fetch_assoc();
$gamesWithErrors = '';
$cont = 0;
$contInserts = 0;
$contUpdates = 0;
$contErrors = 0;
if (password_verify($data->password, $row['password'])) :
    foreach ($data->data as $game) :
        $startdate = empty($game->startdate) || !isset($game->startdate) ?  'null' : '"' . $game->startdate . '"';
        $finishdate = empty($game->finishdate) || !isset($game->finishdate) ? 'null' : '"' . $game->finishdate . '"';
        $sqlInsert = 'INSERT INTO azurelog_rel_users_games values (' . $row['id'] . ', ' . $game->id . ', ' . $game->favourite . ', ' . $game->score . ', ' . $game->status . ', ' . $startdate . ', ' . $finishdate . ', "' . $game->comment . '", ' . $game->playtime . ', ' . $game->replaying . ')';
        $sqlUpdate = sprintf('UPDATE azurelog_rel_users_games set isfavourite = ' . $game->favourite . ', score = ' . $game->score . ', status = ' . $game->status . ', startdate = ' . $startdate . ', finishdate = ' . $finishdate . ', comment = "' . $game->comment . '", playtime = ' . $game->playtime . ', replaying = ' . $game->replaying . ' where iduser = ' . $row['id'] . ' and idgame = ' . $game->id);
        $result = $database->query($sqlInsert);
        if ($result == false || $database->affected_rows == 0) :
            $result = $database->query($sqlUpdate);
            if ($result == false || $database->affected_rows == 0) :
                $sql = 'SELECT id from azurelog_games where id = ' . $game->id;
                $resultSelect = $database->query($sql);
                if (is_object($resultSelect)) :
                    if ($resultSelect->num_rows == 0) :
                        if (empty($gamesWithErrors)) :
                            $gamesWithErrors .= 'Algunos juegos no han podido ser insertados, posiblemente por no existir en la base de datos de Silent Azure o por ser juegos customizados. La lista de todos ellos se incluye aquí, manda una captura de esta pantalla al desarrollador para añadirlos: ' . "\r\n";
                        endif;
                        $gamesWithErrors .= "\r\n";
                        $gamesWithErrors .= 'ID del juego: ' . $game->id . "\r\n";
                        $gamesWithErrors .= 'Nombre del juego: ' . $game->name . "\r\n";
                        $gamesWithErrors .= "\r\n";
                        $contErrors++;
                    endif;
                else :
                    if (empty($gamesWithErrors)) :
                        $gamesWithErrors .= 'Algunos juegos no han podido ser insertados, posiblemente por no existir en la base de datos de Silent Azure o por ser juegos customizados. La lista de todos ellos se incluye aquí, manda una captura de esta pantalla al desarrollador para añadirlos: ' . "\r\n";
                    endif;
                    $gamesWithErrors .= "\r\n";
                    $gamesWithErrors .= 'ID del juego: ' . $game->id . "\r\n";
                    $gamesWithErrors .= 'Nombre del juego: ' . $game->name . "\r\n";
                    $gamesWithErrors .= "\r\n";
                    $contErrors++;
                endif;
            else :
                $contUpdates++;
            endif;
        else :
            $contInserts++;
        endif;
        $cont++;
    endforeach;
    header('Content-Type: text/plain');
    echo 'Total: ' . $cont . ', Insertados: ' . $contInserts . ', Actualizados: ' . $contUpdates . ', Errores: ' . $contErrors . "\n";
    echo $gamesWithErrors;
endif;
