<?php 
include "config.php";

if (!isset($_GET['id']) || !isset($_GET['season']) || !isset($_GET['v'])) :
    header('location: /');
endif;


$sql = 'SELECT name, location, typename from animes a inner join animetypes aty on a.type = aty.id where a.id = ' . $_GET['id'];
$result = $database->query($sql);
$row = $result->fetch_assoc();
$seasons = glob('E:/AZURE_MULTIMEDIA/Anime/' . $row['typename'] . $row['location'] . '/*');
if (sizeof($seasons)) :
    if (!endsWith($seasons[0], 'mp4') && !endsWith($seasons[0], 'mkv')) :
        $seasonVideos = glob($seasons[$_GET['season']] . '/*');
        if (sizeof($seasonVideos)) :
            $video = $seasonVideos[$_GET['v']];
            $toPlay = explode('E:/AZURE_MULTIMEDIA/', $video)[1];
            $toPlay = 'http://' . $_SERVER['HTTP_HOST'] . '/multimedia/' . $toPlay;
        endif;
    else :
        $video = $seasons[$_GET['v']];
        $toPlay = explode('E:/AZURE_MULTIMEDIA/', $video)[1];
        $toPlay = 'http://' . $_SERVER['HTTP_HOST'] . '/multimedia/' . $toPlay;
    endif;
endif;
?>

<html>
<head>
    <meta name="viewport" content="width=device-width">
    <style>
        video {
            position: absolute;
            top: 0px;
            right: 0px;
            bottom: 0px;
            left: 0px;
            max-height: 100%;
            max-width: 100%;
            margin: auto;
        }
    </style>
    <title><?php echo $row['name'] ?></title>
</head>

<body style="background-color: black; margin: auto; max-width: 100%;">
    <video controls autoplay name="media" controlsList="nodownload">
        <source src="<?php echo $toPlay ?>" type="video/mp4">
    </video>
    <script>
        document.getElementsByTagName('video').item(0).addEventListener('contextmenu', function (e) { e.preventDefault(); });
    </script>
</body>

</html>