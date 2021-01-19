<?php
include "../config.php";

$id = $_REQUEST['id'];

$sql = "UPDATE games SET dlCount = dlCount + 1 WHERE id = " . $id;

$result = $database->query($sql);
