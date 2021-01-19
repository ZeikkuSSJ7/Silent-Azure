/**
 * Obtiene la URL del tipo de contenido a cargar
 * @param {int} id la ID del tipo de contenido
 */
function getURL(id) {
    if (id == 0) {
        return '/views/anime-info.php';
    } else if (id == 1) {
        return '/views/game-info.php';
    } else if (id == 2) {
        return '/views/soundtrack-info.php';
    }
}

/**
 * Obtiene un número aleatorio entre los indicados
 * @param {int} min minimo del rand
 * @param {int} max max del rand
 */
function rand(min, max) {
    return min + Math.random() * (max - min);
}