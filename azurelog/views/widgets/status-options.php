<?php 

echo '<select id="status" style="color: black;">';

$result = $database->query("SELECT * FROM azurelog_status_types;");
while ($row = $result->fetch_assoc()) :
    $idoption = $row['id'];
    $selected = '';
    if($idoption == $index) :
        $selected = 'selected="selected"';
    endif;
    echo '<option value="' . $row['value'] . '"' . $selected . '>' . $idoption . ' - ' . $row['value'] . '</option>';
endwhile;
echo '</select>';
?>