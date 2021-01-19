var audio;
var length;
var progressWidth;
var step;
var $volume;
var $progress;
var $timerText;
var $playbutton;

/**
 * Carga el player e inicializa todo lo necesario para su correcto funcionamiento
 */
function loadPlayer() {
    audio = $(".real-audio")[0];
    
    // asignación de funciones al objeto Audio
    audio.onloadedmetadata = setTime;
    audio.ontimeupdate = updatePlayer;
    audio.onended = next;
    audio.volume = 0.4;
    audio.load();

    // Control de los slider de volumen y progreso
    $volume = $('.volume');
    $progress = $('.progress');
    $timerText = $(".timer-text");
    $playbutton = $('.play-button');
    $volume.css({ background: "hsla(51, " + audio.volume * 100 + "%, 50%, 1)" })
    $volume.slider({
        value: audio.volume * 100,
        step: 0.1,
        slide: function (ev, ui) {
            $volume.css({ background: "hsla(51, " + ui.value + "%, 50%, 1)" });
            audio.volume = ui.value / 100;
        }
    });
    $progress.slider({
        value: audio.currentTime,
        slide: function (ev, ui) {
            audio.currentTime = audio.duration / 100 * ui.value;
        }
    });
    $('.progress span, .volume span').css({
        background: 'rgb(200, 175, 35)',
        border: 'none'
    });

    // Asignación de funciones al frontend del objeto Audio
    $playbutton.click(play);
    $playbutton.click(function() {
        $(this).toggleClass('pause-button');
    });

    $('.like').click(function () {
        $(this).toggleClass('liked');
        updateSong($('.album').attr('id'), $('.song').attr('id'), $(this).hasClass('liked'));
    });

    $('.next').click(next);

    $('.previous').click(prev);

    $('.random').click(function () {
        $(this).toggleClass('random-active');
        if ($(this).hasClass('random-active')) {
            random = 1;
        } else {
            random = 0;
        }
    });

    $('.no-loop').click(function () {
        var $this = $(this);
        if ($this.hasClass('no-loop')){
            $this.removeClass('no-loop');
            $this.addClass('loop-all');
            repeat = 1;
        } else if ($this.hasClass('loop-all')){
            $this.removeClass('loop-all');
            $this.addClass('loop-one');
            repeat = 2;
        } else {
            $this.removeClass('loop-one');
            $this.addClass('no-loop');
            repeat = 0;
        }
    });

}

/**
 * Refresca el reproductor cuando se carga una canción nueva
 */
function loadNewContent(){
    songPos = 0;
    $playbutton.removeClass('pause-button');
    audio.src = hostLink + "/" + songs['data'][songPos]['filename'];
    $progress.slider('value', 0);
    setTime();
    updatePlayer();
}

/**
 * Mueve los slider al principio
 */
function updatePlayer() {
    $progress.slider({
        step: 0.1,
        value: 100 / audio.duration * audio.currentTime
    });
    $timerText.text(time(audio.currentTime) + " / " + time(length));
}

/**
 * Ajusta el tiempo de duración de la pista actual
 */
function setTime() {
    length = audio.duration;
    $timerText.text(time(audio.currentTime) + " / " + time(length));
}

/**
 * Calcula la duración de la canciñon y la devuelve como una String formada correctamente
 * @param {int} length la longitud de la canción 
 */
function time(length) {
    var m = ~~(length / 60),
        s = ~~(length % 60);
    return (m < 10 ? "0" + m : m) + ':' + (s < 10 ? "0" + s : s);
}

/**
 * Reproduce o para una canción
 */
function play() {
    if (audio.paused) {
        audio.play();
    } else {
        audio.pause();
    }
}

/**
 * Reproduce una canción nueva
 */
function playNew() {
    $progress.slider('value', 0);
    $('.play-button').addClass('pause-button');
    play();
}