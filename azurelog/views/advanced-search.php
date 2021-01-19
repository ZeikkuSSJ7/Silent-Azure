<?php include "../../config.php" ?>

<div>
    <div style="max-width: 600px; background-color: #444444; border: 1px solid black; box-sizing: border-box; margin: 5px auto;">
        Introduce los campos necesarios para encontrar, dentro de tus juegos completados, los que concuerden con tus criterios
        <div class="playing" style="margin: 5px; text-align: left; padding: 10px;">
            <div style="margin: 5px 0px;">
                <span>Nombre del juego: </span><input type="text" id="gamename">
            </div>
            <div style="margin: 5px 0px;">
                <span>Publicador: </span><?php include "./widgets/publishers-options.php" ?>
            </div>
            <div style="margin: 5px 0px;">
                <span>Desarrollador: </span><?php include "./widgets/developers-options.php" ?>
            </div>
            <div style="margin: 5px 0px;">
                <span>Fecha de salida: </span><input type="date" id="releasedate"> Favorito: <input type="checkbox" id="favourite">
            </div>
            <div style="margin: 5px 0px;">
                <span>Plataforma: </span><?php include "./widgets/platforms-options.php" ?>
            </div>
        </div>
        <div class="planned" style="margin: 5px; text-align: left; padding: 5px;">
            <div class="hold" style="margin: 5px 0px; padding: 5px;">
                <span>Fecha de inicio: </span><br><pre>     desde <input type="date" id="startdate1"> hasta <input type="date" id="startdate2"></pre>
            </div>
            <div class="completed" style="margin: 5px 0px; padding: 5px;">
                <span>Fecha de finalización: </span><br><pre>     desde <input type="date" id="finishdate1"> hasta <input type="date" id="finishdate2"></pre>
            </div>
            <div class="mastered" style="margin: 5px 0px; padding: 5px;">
                <span>Tiempo de juego: </span><br><pre>     desde <input type="number" id="playtime1" placeholder="'39.3'"> hasta <input type="number" id="playtime2" placeholder="'62.7'"></pre>
            </div>
        </div>
    </div>
    <div class="fab-wrapper big">
        <div class="fab search"></div>
    </div>
</div>