/**
 * Busca una cadena en la base de datos y modifica la vista principal al presionar enter
 * @param {string} q la cadena a buscar
 * @param {int} which la tecla pulsada (enter)
 * @param {int} category la categoría a buscar (0, 1 o 2)
 * @param {function} goBackFunction la función a la que volver después, normalmente el menú anterior
 * @param {object} cardsAfter objeto conteniendo lo que debe cargar la funcion de las cards que saldrán de la búsqueda
 */
function onEnterPressedSearch(q, which, category, goBackFunction, cardsAfter) {
    if (which == 13) {
        masterMainPage.html(progressBar);
        $.ajax({
            url: "/views/search.php",
            data: {
                q: q,
                category: category
            },
            success: function (responseSearch) {
                goBack.push(goBackFunction);
                masterMainPage.html(responseSearch);
                $('.card').click(function () {
                    onClickCardInfo($(this).attr('id'), cardsAfter.pageToLoad, function () { onEnterPressedSearch(q, which, category, goBackFunction, cardsAfter); }, cardsAfter.functionAfter);
                });
            }
        })
    }
}

/**
 * Busca en la base de datos contenidos que empiecen por una letra concreta y modifica la vista principal
 * @param {char} letter la letra por la que empieza
 * @param {int} category la categoría a buscar (0, 1 o 2)
 * @param {function} goBackFunction la función a la que volver después, normalmente el menú anterior
 * @param {object} cardsAfter objeto conteniendo lo que debe cargar la funcion de las cards que saldrán de la búsqueda
 */
function onClickLetterSearch(letter, category, goBackFunction, cardsAfter) {
    masterMainPage.html(progressBar);
    $.ajax({
        url: "/views/search.php",
        data: {
            letter: letter,
            category: category
        },
        success: function (responseSearch) {
            goBack.push(goBackFunction);
            masterMainPage.html(responseSearch);
            $('.card').click(function () {
                onClickCardInfo($(this).attr('id'), cardsAfter.pageToLoad, function () { onClickLetterSearch(letter, category, goBackFunction, cardsAfter); }, cardsAfter.functionAfter);
            });
        }
    })
}

/**
 * Carga los datos del contenido seleccionado
 * @param {int} id el ID de la card
 * @param {string} pageToLoad la página a cargar, normalmente una de *-info.php
 * @param {function} goBackFunction la función a la que volver después, normalmente el menú anterior
 */
function onClickCardInfo(id, pageToLoad, goBackHtml, functionAfter) {
    masterMainPage.html(progressBar);
    $.ajax({
        url: pageToLoad,
        data: { id: id },
        success: function (response) {
            goBack.push(goBackHtml);
            masterMainPage.html(response);
            functionAfter(id);
        }
    });
}

/**
 * Usado en el perfil del usuario cuando hace click en uno de los contenidos ya descargados
 * @param {*} id el ID del usuario
 * @param {*} pageToLoad la página a carga, normalmente *-info.php
 * @param {function} goBackFunction la función a la que volver después, normalmente el menú anterior
 * @param {function} functionAfter funcion conteniendo lo que debe cargar despuñes de cargar la página
 */
function onClickUserContent(id, pageToLoad, goBackHtml, functionAfter) {
    masterWrapper.html(progressBar);
    $.ajax({
        url: pageToLoad,
        data: { id: id },
        success: function (response) {
            goBack.push(goBackHtml);
            masterWrapper.html(response);
            functionAfter(id);
        }
    });
}

/**
 * 
 * @param {int} id el ID de la plataforma
 * @param {function} goBackFunction la función a la que volver después, normalmente el menú anterior
 * @param {object} cardsAfter objeto conteniendo lo que debe cargar la funcion de las cards que saldrán de la búsqueda
 */
function onClickPlatformCardSearch(id, goBackFunction, cardsAfter){
    masterMainPage.html(progressBar);
    $.ajax({
        url: '/views/search.php',
        data: { 
            platform : id,
            category: 1
        },
        success: function (response) {
            goBack.push(goBackFunction);
            masterMainPage.html(response);
            $('.card').click(function () {
                onClickCardInfo($(this).attr('id'), cardsAfter.pageToLoad, function () { onClickPlatformCardSearch(id, goBackFunction, cardsAfter) }, cardsAfter.functionAfter);
            });
        }
    })
}