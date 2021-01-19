<div class="card <?php echo $status ?>" id="<?php echo $id ?>">
    <div class="card-image" style="background: url('<?php echo $image ?>') center center / contain no-repeat"></div>
    <div class="card-title"><?php echo $name . '<br>' . $platform; if (isset($releasedate)) echo '<br>' . $releasedate; ?></div>
</div>