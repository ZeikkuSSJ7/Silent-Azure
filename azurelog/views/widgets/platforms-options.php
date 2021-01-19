<?php 

echo '<select id="platform" style="color: black;">';

$result = $database->query("SELECT * FROM azurelog_platforms;");
while ($row = $result->fetch_assoc()) :
    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
endwhile;
echo '</select>';
?>