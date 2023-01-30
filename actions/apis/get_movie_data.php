<?php
function get_data($data_reset, $title, $author, $image, $length, $link)
{
    // get info from omdb
    $url = "http://www.omdbapi.com/?t=" . urlencode($title) . ($author != null && $author != "" ? urlencode(" " . $author) : "") . "&apikey=2f6435d9";
    $omdb_search_json = file_get_contents($url);
    $omdb_search = json_decode($omdb_search_json, true);

    // did we get results?
    if ($omdb_search["Response"] == "False") {
        if ($data_reset) {
            $author = "Unknown";
            $image = "none";
            $length = "Unknown";
        }
    } else {
        if ($author == null || $author == "") {
            $author = $omdb_search["Director"];
        }

        if ($image == null || $image == "") {
            $image = $omdb_search["Poster"];
        }

        if ($length == null || $length == "") {
            $length = $omdb_search["Runtime"];

            // replace "min" with "min."
            $length = str_replace("min", "min.", $length);
        }
    }

    return array($author, $image, $length, $link);
}
?>