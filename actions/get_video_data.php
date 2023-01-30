<?php
// get key
$youtube_key = file_get_contents("../data/youtube_key.txt");

// get info from youtube
$url = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=1&q=" . urlencode($title) . "&type=video&key=" . $youtube_key;
$youtube_search_json = file_get_contents($url);
$youtube_search = json_decode($youtube_search_json, true);

// did we get results?
if (count($youtube_search["items"]) == 0) {
    if ($data_reset) {
        $author = "Unknown";
        $image = "none";
        $length = "Unknown";
    }
} else {
    // get the video id
    $video_id = $youtube_search["items"][0]["id"]["videoId"];

    // get the video info
    $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=" . $video_id . "&key=" . $youtube_key;
    $youtube_video_json = file_get_contents($url);
    $youtube_video = json_decode($youtube_video_json, true);

    if ($author == null || $author == "") {
        $author = $youtube_video["items"][0]["snippet"]["channelTitle"];
    }

    if ($image == null || $image == "") {
        $image = $youtube_video["items"][0]["snippet"]["thumbnails"]["high"]["url"];
    }

    if ($length == null || $length == "") {
        $length = $youtube_video["items"][0]["contentDetails"]["duration"];

        // convert the duration from ISO 8601 to a human readable format
        $interval = new DateInterval($length);
        $length = $interval->format('%H:%I:%S');

        // remove the leading 0s
        $length = ltrim($length, "0");

        // remove the leading :
        $length = ltrim($length, ":");
    }

    if ($link == null || $link == "") {
        $link = "https://www.youtube.com/watch?v=" . $video_id;
    }
}