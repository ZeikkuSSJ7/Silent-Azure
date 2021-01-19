<div class="search-wrapper" style="margin: auto; width: 86.601%;">
    <?php
    include "../config.php";
    $html = '';
    if (isset($_GET['category'])) :

        switch ($_GET['category']): // selecciono categoría
            case 0:
                $category = "animes";
                break;
            case 1:
                $category = "games";
                break;
            case 2:
                $category = "soundtracks";
                break;
        endswitch;

        if (!isset($_GET['platform'])) :
            if (isset($_GET['q'])) : // si hay consulta, se añaden las wildcards
                $q = "\"%" . str_replace(' ', '%', $_GET['q']) . "%\"";
            elseif (isset($_GET['letter'])) : // si hay sólo letra, se añade una wildcard
                $q = "\"" . $_GET['letter'] . '%' . "\"";
            else : // vacio? no hay nada
                $html = '<div class="content-text">No hay ningun resultado...</div>';
            endif;
        endif;
        if ($category == "games") : // en caso de ser juegos...
            if (isset($_GET['platform'])) : // hay plataforma filtrando? añadelo
                $sql = 'SELECT g.id, name, p.platform FROM ' . $category . ' g inner join platforms p on p.id = g.platform where g.platform = ' . $_GET['platform']. ' order by name';
            else : // si no...
                if ($q == "\"#%\"") : // busca por número? cmabialo por un regexp
                    $sql = 'SELECT g.id, name, p.platform FROM ' . $category . ' g inner join platforms p on p.id = g.platform where name regexp "^[0-9]" order by name';
                else: // si no, se busca de todo filtrando por la cadena de búsqueda
                    $sql = 'SELECT g.id, name, p.platform FROM ' . $category . ' g inner join platforms p on p.id = g.platform where name like ' . $q . ' order by name';
                endif;
            endif;
        else :
            $sql = 'SELECT id, name FROM ' . $category . ' where name like ' . $q . ' order by name';
            if ($q == "\"#%\"") : // busca por número? cmabialo por un regexp
                $sql = 'SELECT id, name FROM ' . $category . ' where name regexp "^[0-9]" order by name';
            endif;
        endif;

        $result = $database->query($sql);

        if ($result->num_rows) :

            while ($row = $result->fetch_assoc()) :
                if (isset($row['platform'])) :
                    $platform = $row['platform'];
                endif;
                $id = $row['id'];
                $name = $row['name'];
                $image = '/images/' . $category . '/covers/' . $id . '.png';
                if (file_exists(IMAGES_ROOT_LOCAL . $image) === false) :
                    $image = '/images/nocover.png';
                endif;
                $type = $_GET['category'];
                include 'widgets/card.php';
            endwhile;

        else :
            $html = '<div class="content-text">No hay ningun resultado...</div>';
        endif;
    else :
        $html = '<div class="content-text">No hay ningun resultado...</div>';
    endif;
    echo $html;
    ?>
</div>