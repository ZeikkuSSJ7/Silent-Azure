<div class="card" id="<?php echo $id ?>">
    <div class="card-image" style="background: url('<?php echo $image ?>') center center / contain no-repeat"></div>
    <div class="card-title" id="<?php echo $type ?>"><?php echo $name; if(isset($platform)) echo '<br>' . $platform; ?></div>
</div>