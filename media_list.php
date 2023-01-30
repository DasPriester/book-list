<?php
include("media.php");

function media_list($dir, $extern, $sort, $search, $to_view)
{
    $fdir = $extern . "media/" . $dir;
    $files = scandir($fdir);

    // filter out the . and .. files
    $files = array_diff($files, array('.', '..'));

    // if there is a search term
    if ($search != null && $search != "") {
        $files = media_search($files, $search, $to_view, $fdir);
    }

    // if there is a sort term
    if ($sort == null || $sort == "") {
        $sort = "custom:custom";
    }

    $files = media_sort($files, $sort, $fdir);

    foreach ($files as $file) {
        // open the json file
        $media_json = file_get_contents($fdir . "/" . $file);
        // decode the json
        $media = json_decode($media_json, true);

        $id = $media["id"];

        $editing = false;
        if (isset($_GET["edit"])) {
            if ($_GET["edit"] == $id) {
                $editing = true;
            }
        }

        $single_view = false;
        $moveable = str_contains($sort, "custom");

        media($media, $to_view, $single_view, $moveable, $editing, $dir);
    }
}

?>