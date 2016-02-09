<?php
$filename = $_GET['filename'];
$dir = $_GET['drive'].':/video_upload/';
if (is_file($dir . $filename)) {
    $file_parts = pathinfo($filename);

    switch ($file_parts['extension']) {
        case "mp4":
            header('Content-type: video/mp4');
            break;

        case "avi":
            header('Content-type: video/quicktime');
            break;

        case "mov":
            header('Content-type: video/x-msvideo');
            break;

        case "wmv":
            header('Content-type: video/x-ms-wmv');
            break;

        case "3gp":
            header('Content-type: video/3gpp');
            break;

        default:
            header('Content-type: text/plain');
    }
    header('Content-Disposition: attachment; filename="'.$filename.'"');
        echo file_get_contents($dir.$filename);
    }
?>