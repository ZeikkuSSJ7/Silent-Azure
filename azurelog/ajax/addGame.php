<?php
include "../../config.php";

$id = $_GET['id'];

$database->query('INSERT INTO azurelog_rel_users_games (idgame, iduser) values (' . $id . ', ' . $_SESSION['id'] . ')');
if ($database->affected_rows) :
?>
    <tr>
        <td class="padding game-user-info score">Puntuación: Sin clasificar</td>
        <td class="padding game-user-info status">Estado: Planeado jugar</td>
    </tr>
    <tr>
        <td class="padding game-user-info comment" colspan="2">Comentario: </td>
    </tr>
    <script>gameOptions(<?php echo $id ?>)</script>
<?php endif; ?>