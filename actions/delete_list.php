<?php
// get the directory
$dir = "fallback";
if (isset($_GET["dir"])) {
    $dir = $_GET["dir"];

    // check for script injection
    if (strpos($dir, "..") !== false || strpos($dir, "/") !== false) {
        $dir = "fallback";
    }

    if ($dir == "") {
        $dir = "fallback";
    }

    // check if the directory exists, if not, create it
    if (!file_exists("media/" . $dir) && $dir != "fallback") {
        mkdir("media/" . $dir);
    }
}

if ($dir != "fallback") {
    // delete the list folder
    $folder = "../media/" . $dir;

    deleteDir($folder);
}

// redirect to the home page
header("Location: ../index.php");

function deleteDir($dirPath)
{
    if (!is_dir($dirPath)) {
        return;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}
?>