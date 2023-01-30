<?php
// Edits media from the media folder

$dir = $_GET["dir"];

// check if directory is "fallback"
if ($dir == "fallback") {
    // redirect to the home page
    header("Location: ../index.php?dir=" . $dir);
    exit();
}

// get the data from the form
if (isset($_POST["id"])) {
    $id = $_POST["id"];
} else if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    // redirect to the home page
    header("Location: ../index.php?dir=" . $dir);
    exit();
}

// prevent script injection
$id = str_replace("..", "", str_replace("/", "", $id));
$dir = str_replace("..", "", str_replace("/", "", $dir));

if (isset($_POST["title"]) && isset($_POST["author"]) && isset($_POST["length"]) && isset($_POST["image"]) && isset($_POST["link"])) {
    // get data from the file
    $media_json = file_get_contents("../media/" . $dir . "/" . $id . ".json");
    $media = json_decode($media_json, true);

    $title = $_POST["title"];
    $author = $_POST["author"];
    $length = $_POST["length"];
    $image = $_POST["image"];
    $link = $_POST["link"];

    // prevent script injection
    $title = str_replace("..", "", str_replace("/", "", $title));
    $author = str_replace("..", "", str_replace("/", "", $author));
    $length = str_replace("..", "", str_replace("/", "", $length));

    // check if the title is empty
    if ($title == null || $title == "") {
        // redirect to the home page
        header("Location: ../index.php?dir=" . $dir);
        exit();
    }

    $type = $media["type"];

    include "get_" . $type . "_data.php";
    $data = get_data(false, $title, $author, $image, $length, $link);

    // update the data
    $media["title"] = $title;
    $media["author"] = $data[0];
    $media["image"] = $data[1];
    $media["length"] = $data[2];
    $media["link"] = $data[3];

    // encode the array as json
    $media_json = json_encode($media, JSON_PRETTY_PRINT);

    // save the json to a file
    file_put_contents("../media/" . $dir . "/" . $id . ".json", $media_json);
} else if (isset($_GET["move"])) {
    // get all the files in the directory
    $files = scandir("../media/" . $dir);

    // filter out the . and .. files
    $files = array_diff($files, array('.', '..'));

    // put the files in a list
    $file_list = array();
    $i = 0;
    foreach ($files as $file) {
        $file_json = file_get_contents("../media/" . $dir . "/" . $file);
        $file_list[$i] = json_decode($file_json, true);
        $i++;
    }

    // sort the files by the "custom" property
    usort($file_list, function ($a, $b) {
        return $a["custom"] <=> $b["custom"];
    });

    // get the index of the file
    $index = -1;
    for ($i = 0; $i < count($file_list); $i++) {
        if ($file_list[$i]["id"] == $id) {
            $index = $i;
            break;
        }
    }

    // get the direction
    $direction = $_GET["move"];

    // prevent script injection
    $direction = str_replace("..", "", str_replace("/", "", $direction));

    // move the file
    if ($direction == "left") {
        if ($index > 0) {
            $temp = $file_list[$index - 1];
            $file_list[$index - 1] = $file_list[$index];
            $file_list[$index] = $temp;
        }
    } else if ($direction == "right") {
        if ($index < count($file_list) - 1) {
            $temp = $file_list[$index + 1];
            $file_list[$index + 1] = $file_list[$index];
            $file_list[$index] = $temp;
        }
    }

    // set the "custom" property according to the index and save the files
    for ($i = 0; $i < count($file_list); $i++) {
        $file_list[$i]["custom"] = $i;
        $file_json = json_encode($file_list[$i], JSON_PRETTY_PRINT);
        file_put_contents("../media/" . $dir . "/" . $file_list[$i]["id"] . ".json", $file_json);
    }
}

// redirect to the home page
header("Location: ../index.php?dir=" . $dir);
exit();