<?php 

echo '<select id="publisher" style="color: black;">';

$result = $database->query("SELECT * FROM azurelog_publishers;");
while ($row = $result->fetch_assoc()) :
    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
endwhile;
echo '</select>';
?>