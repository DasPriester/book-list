<?php
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
        // Writer
        $author = $omdb_search["Writer"];
    }

    if ($image == null || $image == "") {
        $image = $omdb_search["Poster"];
    }

    if ($length == null || $length == "") {
        // Number of seasons
        $seasons = $omdb_search["totalSeasons"];
        $length = $seasons . " S.";
    }
}