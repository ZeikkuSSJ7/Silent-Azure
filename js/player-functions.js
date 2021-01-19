var host;
var hostLink;
var songPos = 0;
var songs = [];
var repeat = 0;
var random = 0;
var rows;

/**
 * Avanza a la siguiente canción
 */
function next() {
    if (repeat == 2) {
        newSong(songPos);
        return;
    }
    if (random == 1) {
        newSong(rand(0, songs['data'].length));
    } else {
        if (repeat == 0) {
            if (songPos != songs.length - 1) {
                newSong(songPos + 1);
            }
        } else if (repeat == 1) {
            if (songPos == songs['data'].length - 1) {
                newSong(0);
            } else {
                newSong(songPos + 1);
            }
        }
    }
}

/**
 * Retrocede a la canción anterior
 */
function prev() {
    if (repeat == 2) {
        newSong(songPos);
        return;
    }
    if (random == 1) {
        newSong(rand(0, songs['data'].length));
    } else {
        if (repeat == 0) {
            if (songPos != 0) {
                newSong(songPos - 1);
            }
        } else if (repeat == 1) {
            if (songPos == 0) {
                newSong(songPos['data'].length - 1);
            } else {
                newSong(songPos - 1);
            }
        }
    }
}

/**
 * Reproduce la canción en la posición indicada
 * @param {int} pos la posición de la canción
 */
function newSong(pos) {
    $(rows[songPos + 1]).children().css("backgroundColor", "");
    songPos = ~~pos; // aproxima por los rand()
    audio.src = hostLink + "/" + songs['data'][songPos]['filename'];
    setSongAlbum(songPos);
    playNew();
    $(rows[songPos + 1]).children().css("backgroundColor", "#555555");
}

/**
 * Pone los datos de la canción en el reporductor
 * @param {int} index la posición de la canción
 */
function setSongAlbum(index) {
    $('.album').text(songs['data'][index]['album']);
    $('.song').text(songs['data'][index]['song']);
    $('.song').attr('id', index);
    if ($('.like').hasClass('liked')) 
        $('.like').removeClass('liked');
    $('.like').addClass(songs['data'][index]['liked'])
}

/**
 * Pide un reproductor al servidor en caso de necesitarlo
 */
function requestPlayer() {
    var player = $(".player");
    if (player.length > 1) {
        audio.pause();
        player.first().remove();
        return true;
    } else {
        player.appendTo(masterWrapper.parent());
        return false;
    }
}

/**
 * Actualiza los datos de la canción para el usuario
 * @param {int} id el ID de la banda sonora
 * @param {int} song el número de la pista
 * @param {int} liked si gusta o no
 */
function updateSong(id, song, liked) {
    $.ajax({
        url: "/ajax/updateSong.php",
        data: {
            id: id,
            song: song,
            liked: liked
        }
    });
    songs['data'][songPos]['liked'] = liked ? 'liked' : 'like';
}