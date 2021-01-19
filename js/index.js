var progressBar = $('<div style="display: inline-flex; width: 100%; height: 90%;"><progress class="pure-material-progress-circular" style="margin: auto;"></div></div>'); // progress
var masterWrapper = $('.master-wrapper');
var goBack = new Array();
var masterMainPage;

// Configura el boton de atrás
$('.user-controls .back-button').click(function () {
    if (!goBack.length) {
        history.go(-1);
    } else {
        goBack[goBack.length - 1]();
        goBack.pop();
    }
});

// Drawer usado en la versión móvil
var drawer = $('.drawer');
var originalright = drawer.css('right');
drawer.css('right', '400px');
$('.user-controls .drawer-trigger').click(function () {
    if (drawer.css('right') == originalright) {
        drawer.animate({ right: '400px' });
    } else {
        drawer.animate({ right: originalright });
    }
});

$('.dropdown-content').width($('.dropdown-content').siblings().first().width()); // Ajuste del botón del usuario

// Ajuste de botones del drawer y de la barra de acción
$('.content-button').click(function () {
    $(this).css('backgroundColor', '#111111').siblings().css('backgroundColor', '');
});
$('.content-button .icon-search').parent().click(loadDatabaseUpdates);
$('.content-button .icon-anime').parent().click(loadAnimeMainPage);
$('.content-button .icon-games').parent().click(loadGamesMainPage);
$('.content-button .icon-soundtracks').parent().click(loadSoundtracksMainPage);
$('.user-profile-link').click(loadProfilePage);

// Si hay una lista de busqueda, ajustar tamaño a tamaño de ventana
$(window).on('resize', function () {
    var width = $('.master-main-page .search-wrapper').parent().width();
    if (width >= 250 && width < 500) {
        $('.master-main-page > div').width("250px");
    } else if (width >= 500 && width < 750) {
        $('.master-main-page > div').width("500px");
    } else if (width >= 750 && width < 1000) {
        $('.master-main-page > div').width("750px");
    } else if (width >= 1000) {
        $('.master-main-page > div').width("1000px");
    }
    var form = $('.form-image-wrapper');
    form.height(form.parent().height());
});
$(window).on('resize');
$(loadDatabaseUpdates);



function loadDatabaseUpdates() {
    masterWrapper.html(progressBar);
    $.ajax({
        type: 'GET',
        url: "/views/latest-updates.php",
        success: function (response) {
            masterWrapper.html(response);
            var container_width = 250 * 10;
            $(".container-cards").css("width", container_width);
            $('.card').click(function () {
                goBack.push(loadDatabaseUpdates);
                masterWrapper.html(progressBar);
                var id = $(this).attr('id');
                var type = $(this).children().last().attr('id');
                var url = getURL(type);
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        id: id
                    },
                    success: function (responseCard) {
                        masterWrapper.html(responseCard);
                        if (type == 0) {
                            animeInfoLoaded(id);
                        } else if (type == 1) {
                            gameInfoLoaded(id);
                        } else if (type == 2) {
                            soundtrackInfoLoaded(id);
                        }
                    }
                });
            });
        }
    });
}

function loadAnimeMainPage() {
    masterWrapper.html(progressBar);
    $.ajax({
        url: "/views/animes-main-page.php",
        success: function (response) {
            goBack.push(loadDatabaseUpdates);
            masterWrapper.html(response);
            masterMainPage = $('.master-main-page');
            // Ajuste de la barra de búsqueda
            $('.search-bar #search').keydown(function (e) {
                onEnterPressedSearch($(this).val(), e.which, 0, loadAnimeMainPage, {
                    pageToLoad: "/views/anime-info.php",
                    functionAfter: animeInfoLoaded
                });
            });
            // Ajuste de la búsqueda por letra
            $('.search-by-letter .letter').click(function () {
                onClickLetterSearch($(this).text(), 0, loadAnimeMainPage, {
                    pageToLoad: "/views/anime-info.php",
                    functionAfter: animeInfoLoaded
                });
            });
            // Ajuste del click en una card
            $('.card').click(function () {
                onClickCardInfo($(this).attr('id'), "/views/anime-info.php", loadAnimeMainPage, animeInfoLoaded);
            });
        }
    });
}

function loadGamesMainPage() {
    masterWrapper.html(progressBar);
    $.ajax({
        url: "/views/games-main-page.php",
        success: function (response) {
            goBack.push(loadDatabaseUpdates);
            masterWrapper.html(response);
            masterMainPage = $('.master-main-page');
            // Ajuste de la barra de búsqueda
            $('.search-bar #search').keydown(function (e) {
                onEnterPressedSearch($(this).val(), e.which, 1, loadGamesMainPage, {
                    pageToLoad: "/views/game-info.php",
                    functionAfter: gameInfoLoaded
                });
            });
            // Ajuste de la búsqueda por letra
            $('.search-by-letter .letter').click(function () {
                onClickLetterSearch($(this).text(), 1, loadGamesMainPage, {
                    pageToLoad: "/views/game-info.php",
                    functionAfter: gameInfoLoaded
                });
            });
            // Ajuste del click en una card
            $('.card:not(.platform)').click(function () {
                onClickCardInfo($(this).attr('id'), "/views/game-info.php", loadGamesMainPage, gameInfoLoaded);
            });
            // Ajuste del click en una card de plataforma
            $('.card.platform').click(function () {
                onClickPlatformCardSearch($(this).attr('id'), loadGamesMainPage, {
                    pageToLoad: "/views/game-info.php",
                    functionAfter: gameInfoLoaded
                });
            })
        }
    });
}

function loadSoundtracksMainPage() {
    masterWrapper.html(progressBar);
    $.ajax({
        url: "/views/soundtracks-main-page.php",
        success: function (response) {
            goBack.push(loadDatabaseUpdates);
            masterWrapper.html(response);
            masterMainPage = $('.master-main-page');
            // Ajuste de la barra de búsqueda
            $('.search-bar #search').keydown(function (e) {
                onEnterPressedSearch($(this).val(), e.which, 2, loadSoundtracksMainPage, {
                    pageToLoad: "/views/soundtrack-info.php",
                    functionAfter: soundtrackInfoLoaded
                });
            });
            // Ajuste de la búsqueda por letra
            $('.search-by-letter .letter').click(function () {
                onClickLetterSearch($(this).text(), 2, loadSoundtracksMainPage, {
                    pageToLoad: "/views/soundtrack-info.php",
                    functionAfter: soundtrackInfoLoaded
                });
            });
            // Ajuste del click en una card
            $('.card').click(function () {
                onClickCardInfo($(this).attr('id'), "/views/soundtrack-info.php", loadSoundtracksMainPage, soundtrackInfoLoaded);
            });
        }
    });
}

function loadProfilePage() {
    masterWrapper.html(progressBar);
    $.ajax({
        url: "/views/my-profile.php",
        success: function (response) {
            goBack.push(loadDatabaseUpdates);
            masterWrapper.html(response);
            // Envío de datos del formulario de la imagen de perfil
            $('#form-image').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '/ajax/uploadProfilePic.php',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (responseimg) {
                        $('.profile-pic')[0].src = responseimg;
                    }
                });

            });
            var form = $('.form-image-wrapper');
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent)) {
                // no redimensionar form de imagen
            } else {
                form.height(form.parent().height());

            }
            $('.upload-image').click(function () {
                $('#file-upload').click();
            });
            $('#file-upload').change(function () {
                $('#form-image').submit();
            });
            $('.content-anime').click(function () {
                onClickUserContent($(this).attr('data-id'), '/views/anime-info.php', loadProfilePage, animeInfoLoaded)
            });
            $('.content-game').click(function () {
                onClickUserContent($(this).attr('data-id'), '/views/game-info.php', loadProfilePage, gameInfoLoaded)
            });
            $('.content-soundtrack').click(function () {
                onClickUserContent($(this).attr('data-id'), '/views/soundtrack-info.php', loadProfilePage, soundtrackInfoLoaded)
            });
        }
    })
}