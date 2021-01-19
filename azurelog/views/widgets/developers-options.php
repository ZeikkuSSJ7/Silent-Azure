<?php 

echo '<select id="developer" style="color: black;">';
$result = $database->query("SELECT * FROM azurelog_developers;");
while ($row = $result->fetch_assoc()) :
    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
endwhile;
echo '</select>';
?>