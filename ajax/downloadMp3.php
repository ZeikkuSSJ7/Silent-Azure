<?php
$id = $_GET['id'];

$database = new mysqli("localhost", "phpmyadmin", "root", "silentazure");
if ($database->connect_error) :
    die("Connection failed: " . $database->connect_error);
endif;

$result = $database->query('SELECT name FROM soundtracks where id = ' . $id);
$row = $result->fetch_assoc();

$name = $row['name'];
$song = $_GET['song'];
$dir = 'E:/AZURE_MULTIMEDIA/Soundtracks/' . $name . '/*.mp3';

$files = glob($dir);

if (isset($files) && !empty($files)) :
    $file = $files[$song];
    header('Content-type: audio/mpeg');
    header('Content-Disposition: attachment; filename="' . basename($file)) . '"';
    header('X-Sendfile: ' . $file);
    exit;
else :
    echo 'The requested file does not exist in the server';
    exit;
endif;
