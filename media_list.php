<?php
// filter out the . and .. files
$files = array_diff($files, array('.', '..'));

// if there is a search term
if (isset($_GET["search"])) {
    $search = $_GET["search"];

    include("media_search.php");
}

// if there is a sort term
if ($sort == null || $sort == "") {
    $sort = "title:ascending";
}

include("media_sort.php");

foreach ($files as $file) {
    // open the json file
    $media_json = file_get_contents($fdir . "/" . $file);
    // decode the json
    $media = json_decode($media_json, true);

    $id = $media["id"];
    $custom = $media["custom"];
    $cteated = $media["created"];
    $type = $media["type"];
    $title = $media["title"];
    $link = $media["link"];
    $author = $media["author"];
    $length = $media["length"];
    $image = $media["image"];

    $editing = false;
    if (isset($_GET["edit"])) {
        if ($_GET["edit"] == $id) {
            $editing = true;
        }
    }

    $single_view = false;
    $moveable = $sort_by == "custom";

    include("media.php");
}
?>