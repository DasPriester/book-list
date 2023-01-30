<?php
// Adds media to the media folder

// get the data from the form
$type = $_POST["type"];
$title = $_POST["title"];
$author = $_POST["author"];
$link = $_POST["link"];

$dir = $_GET["dir"];

// prevent script injection
$title = str_replace("..", "", str_replace("/", "", $title));
$author = str_replace("..", "", str_replace("/", "", $author));

// check if the title is empty
if ($title == null || $title == "") {
    // redirect to the home page
    header("Location: ../index.php?dir=" . $dir);
    exit();
}

// check if directory is "fallback"
if ($dir == "fallback") {
    // redirect to the home page
    header("Location: ../index.php?dir=" . $dir);
    exit();
}

include "get_" . $type . "_data.php";
$data = get_data(true, $title, $author, "", "", $link);

// create an array with the data
$media = array(
    "id" => uniqid(),
    "custom" => 0,
    "created" => time(),
    "type" => $type,
    "title" => $title,
    "author" => $data[0],
    "image" => $data[1],
    "length" => $data[2],
    "link" => $data[3]
);

// encode the array as json
$media_json = json_encode($media, JSON_PRETTY_PRINT);

// save the json to a file
file_put_contents("../media/" . $dir . "/" . $media["id"] . ".json", $media_json);

// redirect to the home page
header("Location: ../index.php?dir=" . $dir);
exit();
?>