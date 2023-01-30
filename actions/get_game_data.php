<?php
// get key
$rawg_key = file_get_contents("../data/rawg_key.txt");

// get info from rawg
$url = "https://api.rawg.io/api/games?key=" . $rawg_key . "&search=" . urlencode($title);
$rawg_search_json = file_get_contents($url);
$rawg_search = json_decode($rawg_search_json, true);

// did we get results?
if (count($rawg_search["results"]) == 0) {
    if ($data_reset) {
        $author = "Unknown";
        $image = "none";
        $length = "Unknown";
    }
} else {
    if ($author == null || $author == "") {
        // add platforms together into a string
        $platforms = [];
        foreach ($rawg_search["results"][0]["platforms"] as $platform) {
            array_push($platforms, $platform["platform"]["name"]);
        }
        $author = implode(", ", $platforms);
    }

    if ($image == null || $image == "") {
        $image = $rawg_search["results"][0]["background_image"];
    }

    if ($length == null || $length == "") {
        $length = $rawg_search["results"][0]["playtime"] . " h.";
    }
}