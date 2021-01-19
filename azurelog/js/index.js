var progressBar = $('<div style="display: inline-flex; width: 100%; height: 90%;"><progress class="pure-material-progress-circular" style="margin: auto;"></div></div>'); // progress
var masterWrapper = $('.master-wrapper');
var goBack = new Array();

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
$('.content-button:not(.web):not(.advanced)').click(function () {
    $(this).css('backgroundColor', '#111111').siblings().css('backgroundColor', '');
    loadGames($(this).attr('data-id'));
});

// En el caso del botón de búsqueda en la web
$('.content-button.web').click(function () {
    $(this).css('backgroundColor', '#111111').siblings().css('backgroundColor', '');
    masterWrapper.html(progressBar);
    $.ajax({
        url: '/azurelog/views/search-web.php',
        success: function (response) {
            masterWrapper.html(response);
            $('input#search').keypress(function (e) {
                onEnterPressedSearch($(this).val(), e.which);
            });
        }
    });
})

// En el caso del botón de búsqueda avanzada
$('.content-button.advanced').click(function () {
    $(this).css('backgroundColor', '#111111').siblings().css('backgroundColor', '');
    advancedSearch();
})

$('.user-profile-link').click(loadProfilePage);

loadGames(1); // Cargamos los juegos que estás jugando

/**
 * Normaliza un campo de fecha en caso de tener un dígito a dos dígitos
 * @param {int} date el campo de fecha a normalizar
 */
function correctPattern(date) {
    return date < 10 ? "0" + date : date;
}

/**
 * Carga una lista de juegos con el estado indicado y actualiza la vista principal
 * @param {int} statusId el estado de los juegos
 */
function loadGames(statusId) {
    var opened = 0;
    masterWrapper.html(progressBar);
    $.ajax({
        url: "/azurelog/views/game-list.php",
        data: { statusId: statusId },
        success: function (response) {
            masterWrapper.html(response);
            var cards = $('.card');

            // Filtro por letras en la barra de búsqueda
            $('input#search').val('').keyup(function () {
                var input = $(this).val();
                cards.each(function (i, card) {
                    if ($(card).children('.card-title').text().toLowerCase().includes(input)) {
                        $(card).css('display', 'flex');
                    } else {
                        $(card).css('display', 'none');
                    }
                });
            })

            // Asignación de onclick a cada card (juego) cargado
            cards.click(function () {
                onClickCardInfo($(this).attr('id'), function () {
                    loadGames(statusId); // Lo que hacer al volver atrás
                });
            });

            let timeoutId;
            cards.mouseup(function () {
                clearTimeout(timeoutId)
            });

            cards.mousedown(function () {
                var card = $(this);
                var name = card.find(".card-title").html().split("<br>")
                timeoutId = window.setTimeout(function () {
                    var result = confirm("¿Deseas borrar el juego " + name[0] + " de tu lista?");
                    if (result == true) {
                        $.ajax({
                            url: "/azurelog/ajax/deleteGame.php",
                            data: {
                                id: card.attr('id')
                            },
                            success: function () {
                                card.remove();
                            }
                        })
                    }
                }, 1000)
            });

            // Control de los FABs
            $('.div-fab-wrapper .big').click(function () {
                var minifabs = $(this).siblings();
                if (opened == 1) {
                    $(minifabs[0]).animate({
                        bottom: "0px"
                    }, 300);
                    $(minifabs[1]).animate({
                        bottom: "0px",
                        right: "0px"
                    }, 300);
                    $(minifabs[2]).animate({
                        right: "0px"
                    }, 300);
                    opened = 0;
                } else {
                    $(minifabs[0]).animate({
                        bottom: "70px"
                    }, 300);
                    $(minifabs[1]).animate({
                        bottom: "49px",
                        right: "49px"
                    }, 300);
                    $(minifabs[2]).animate({
                        right: "70px"
                    }, 300);
                    opened = 1;
                }
            });
            
            // Invierte el orden de los cards
            $('.fab.order').click(function () {
                masterWrapper.append($('.card').get().reverse());
            });

            // Hace click en un juego de la lista actual de forma aleatoria
            $('.fab.random').click(function () {
                $('.card:eq(' + ~~rand(0, cards.get().length) + ')').click();
            });

            // Busca un juego aleatoria de los que quieres jugar y actualiza la vista principal
            $('.fab.new-game').click(function () {
                masterWrapper.html(progressBar);
                $.ajax({
                    url: '/azurelog/ajax/getNewGame.php',
                    success: function (response) {
                        goBack.push(function () {
                            loadGames(statusId);
                        });
                        masterWrapper.html(response);
                    }
                });
            });
        }
    });
};

/**
 * Busca una cadena al pulsar ENTER
 * @param {string} q la cadena a buscar
 * @param {int} which la tecla pulsada (13 sería ENTER)
 */
function onEnterPressedSearch(q, which) {
    if (which == 13) {
        masterWrapper.html(progressBar);
        $.ajax({
            url: "/azurelog/views/search-web.php",
            data: {
                q: q
            },
            success: function (response) {
                goBack.push(function () {
                    $('.content-button:not(.web):eq(0)').click();
                });
                masterWrapper.html(response);
                $('.card').click(function () {
                    onClickCardInfo($(this).attr('id'), function () {
                        onEnterPressedSearch(q, which);
                    });
                });
            }
        })
    }
}

/**
 * Maneja el evento de hacer clic sobre un juego y modifica la vista con los datos del juego
 * @param {int} id el ID del juego
 * @param {function} goBackFunction la función a realizar pulsando el botón de atrás
 */
function onClickCardInfo(id, goBackFunction) {
    masterWrapper.html(progressBar);
    $.ajax({
        url: '/azurelog/views/game-info.php',
        data: { id: id },
        success: function (response) {
            goBack.push(goBackFunction);
            masterWrapper.html(response);
            
            // En caso de no tener el juego, hay que añadir funcionalidad a la celda que hace que se añada
            $('.no-game').click(function () {
                var nogame = $(this);
                nogame.html(progressBar);
                $.ajax({
                    url: "/azurelog/ajax/addGame.php",
                    data: { id: id },
                    success: function (response) {
                        var parent = nogame.parent();
                        $(response).insertBefore(parent); // La respuesta devuelve el estado y puntuación del juego para ser editado
                        parent.remove();
                    }
                });
            });
            gameOptions(id);
        }
    })
}

/**
 * Carga la página de búsqueda avanzada y la muestra en la vista principal
 */
function advancedSearch() {
    masterWrapper.html(progressBar);
    $.ajax({
        url: '/azurelog/views/advanced-search.php',
        success: function (response) {
            masterWrapper.html(response);

            // Modificamos los input:date para que se busquen todos los juegos entre el 
            // principio del timestamp UNIX y la actualidad
            var init = new Date(0).toLocaleDateString().split("/"); // Fecha inicial UNIX
            var today = new Date().toLocaleDateString().split("/"); // Fecha actual
            init = init[2] + "-" + correctPattern(init[1]) + "-" + correctPattern(init[0]);
            today = today[2] + "-" + correctPattern(today[1]) + "-" + correctPattern(today[0]);
            $('.planned #startdate1, .planned #finishdate1').val(init);
            $('.planned #startdate2, .planned #finishdate2').val(today);

            $('.fab.search').click(searchGameAdvanced);
        }
    });
}

/**
 * Recoje los datos de la búsqueda avanzada, los añade a un objeto y realiza la búsqueda
 * modifcando la vista principal
 */
function searchGameAdvanced() {
    var obj = {};
    obj.gamename = $('#gamename').val();
    obj.publisher = $('#publisher')[0];
    obj.publisher = obj.publisher[obj.publisher.selectedIndex].value;
    obj.developer = $('#developer')[0];
    obj.developer = obj.developer[obj.developer.selectedIndex].value;
    obj.releasedate = $('#releasedate').val();
    obj.favourite = $('#favourite').is(':checked');
    obj.platform = $('#platform')[0];
    obj.platform = obj.platform[obj.platform.selectedIndex].value;
    obj.startdate1 = $('#startdate1').val();
    obj.startdate2 = $('#startdate2').val();
    obj.finishdate1 = $('#finishdate1').val();
    obj.finishdate2 = $('#finishdate2').val();
    obj.playtime1 = $('#playtime1').val();
    obj.playtime2 = $('#playtime2').val();
    masterWrapper.html(progressBar);
    _searchGameAdvanced(obj);
    
}

/**
 * Realiza una búsqueda avanzada
 * @param {object} obj el objeto conteniendo los datos de búsqueda
 */
function _searchGameAdvanced(obj) {
    $.ajax({
        method: "POST",
        url: "/azurelog/views/advanced-search-results.php",
        data: {
            gamename: obj.gamename,
            publisher: obj.publisher,
            developer: obj.developer,
            releasedate: obj.releasedate,
            favourite: obj.favourite,
            platform: obj.platform,
            startdate1: obj.startdate1,
            startdate2: obj.startdate2,
            finishdate1: obj.finishdate1,
            finishdate2: obj.finishdate2,
            playtime1: obj.playtime1,
            playtime2: obj.playtime2
        },
        success: function (response) {
            goBack.push(advancedSearch);
            masterWrapper.html(response);
            var cards = $('.card');
            cards.click(function () {
                onClickCardInfo($(this).attr('id'), function () {
                    _searchGameAdvanced(obj);
                });
            });

            let timeoutId;
            cards.mouseup(function () {
                clearTimeout(timeoutId)
            });

            cards.mousedown(function () {
                var card = $(this);
                var name = card.find(".card-title").html().split("<br>")
                timeoutId = window.setTimeout(function () {
                    var result = confirm("¿Deseas borrar el juego " + name[0] + " de tu lista?");
                    if (result == true) {
                        $.ajax({
                            url: "/azurelog/ajax/deleteGame.php",
                            data: {
                                id: card.attr('id')
                            },
                            success: function () {
                                card.remove();
                            }
                        })
                    }
                }, 1000)
            });

        }
    });
}

/**
 * Carga las opciones a editar del juego cargado
 * @param {int} id el ID del juego
 */
function gameOptions(id) {
    
    // Diálogos
    var wrapperDialogScore = $('.dialog.score');
    var wrapperDialogStatus = $('.dialog.status');
    var wrapperDialogComment = $('.dialog.comment');
    var wrapperDialogStartDate = $('.dialog.start-date');
    var wrapperDialogFinishDate = $('.dialog.finish-date');
    var wrapperDialogPlaytime = $('.dialog.playtime');

    // Campos
    var score = $('#score')[0];
    var status = $('#status')[0];
    var comment = $('#comment')[0];
    var favourite = $('#favourite');
    var startDate = $('#start-date')[0];
    var finishDate = $('#finish-date')[0];
    var playtime = $('#playtime')[0];
    var replaying = $('#replaying');

    // Al hacer click en cada uno, aparecen
    $('td.score').click(function () {
        wrapperDialogScore.fadeIn();
    });

    $('td.status').click(function () {
        wrapperDialogStatus.fadeIn();
    });

    $('td.comment').click(function () {
        wrapperDialogComment.fadeIn();
    });

    $('td.start-date').click(function () {
        wrapperDialogStartDate.fadeIn();
    });

    $('td.finish-date').click(function () {
        wrapperDialogFinishDate.fadeIn();
    });

    $('td.playtime').click(function () {
        wrapperDialogPlaytime.fadeIn();
    });

    // Al terminar de ajustar alguno de ellos, se mandan los cambios a la base de datos
    $('.modal-body button, #replaying, #favourite').click(function () {
        $.ajax({
            type: 'POST',
            url: '/azurelog/ajax/updateGame.php',
            data: {
                id: id,
                score: score[score.selectedIndex].value,
                status: status[status.selectedIndex].textContent.split(" ")[0],
                comment: comment.value,
                favourite: favourite.is(':checked'),
                startDate: startDate.value,
                finishDate: finishDate.value,
                playtime: playtime.value,
                replaying: replaying.is(':checked')
            }
        });

        // Se muestran los cambios al usuario
        $('td.score').text('Puntuación: ' + (score[score.selectedIndex].value == "0" ? "Sin clasificar" : score[score.selectedIndex].value));
        $('td.status').text('Estado: ' + status[status.selectedIndex].value);
        $('td.comment').text('Comentario: ' + comment.value);
        $('td.start-date').text('Fecha de inicio: ' + (startDate.value == '' ? 'Sin definir' : startDate.value));
        $('td.finish-date').text('Fecha de finalización: ' + (finishDate.value == '' ? 'Sin definir' : finishDate.value));
        $('td.playtime').text('Tiempo de juego: ' + (playtime.value == '0' ? 'Sin definir' : playtime.value + ' horas'));

        // Esconder diálogos antes de mostrar la página
        wrapperDialogScore.fadeOut();
        wrapperDialogStatus.fadeOut();
        wrapperDialogComment.fadeOut();
        wrapperDialogStartDate.fadeOut();
        wrapperDialogFinishDate.fadeOut();
        wrapperDialogPlaytime.fadeOut();
    });
}

/**
 * Carga la página de usuario y la muestra en la vista principal
 */
function loadProfilePage() {
    masterWrapper.html(progressBar);
    $.ajax({
        url: "/azurelog/views/my-profile.php",
        success: function (response) {
            goBack.push(function () {
                loadGames(1);
            });
            masterWrapper.html(response);
            
            // Manejo del form de la imagen de perfil
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
            $('.upload-image').click(function () {
                $('#file-upload').click();
            });
            $('#file-upload').change(function () {
                $('#form-image').submit();
            });
        }
    });

}