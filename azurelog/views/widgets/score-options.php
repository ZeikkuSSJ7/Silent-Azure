<?php 

echo '<select id="score" style="color: black;">';

$result = $database->query("SELECT * FROM azurelog_score_types;");
while ($row = $result->fetch_assoc()) :
    $idoption = $row['id'];
    $selected = '';
    
    if($idoption == $index) :
        $selected = 'selected="selected"';
    endif;
    echo '<option value="' . $idoption . '"' . $selected . '>' . $idoption . ' - ' . $row['value'] . '</option>';
endwhile;
echo '</select>';
?>