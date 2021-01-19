<div class="category">
    <div class="content-text"><?php echo $sectionTitle ?></div>
</div>
<div class="z-flow">
    <div class="container-cards">

        <?php
        $sql = 'SELECT * FROM ' . $sectionName . ' order by id desc LIMIT 10';

        $result = $database->query($sql);

        while ($row = $result->fetch_assoc()) :
            $id = $row['id'];
            $name = $row['name'];
            $image = '/images/' . $sectionName . '/covers/' . $id . '.png';
            if (file_exists(IMAGES_ROOT_LOCAL . $image) === false) :
                $image = '/images/nocover.png';
            endif;
            include 'widgets/card.php';
        endwhile;
        ?>
    </div>
</div>