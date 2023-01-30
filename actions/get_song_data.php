<?php
function get_data($data_reset, $title, $author, $image, $length, $link)
{
    $cid_csk = file_get_contents("../data/spotify_cid_cis.txt");
    $cid_csk = explode("\n", $cid_csk);
    $client_id = $cid_csk[0];
    $client_key = $cid_csk[1];

    //get token from spotify
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($client_id . ':' . $client_key)));

    $result = curl_exec($ch);
    $token = json_decode($result, true)["access_token"];

    //get info from spotify
    $url = "https://api.spotify.com/v1/search?q=" . urlencode($title) . ($author != null && $author != "" ? urlencode(" " . $author) : "") . "&type=track";
    $spotify_search_json = file_get_contents(
        $url,
        false,
        stream_context_create(
            array(
                "http" => array(
                    "method" => "GET",
                    "header" => "Accept: application/json\r\nAuthorization: Bearer " . $token . "\r\n" . "Content-Type: application/json\r\n",
                )
            )
        )
    );
    $spotify_search = json_decode($spotify_search_json, true);

    // did we get results?
    if (count($spotify_search["tracks"]["items"]) == 0) {
        if ($data_reset) {
            $author = "Unknown";
            $image = "none";
            $length = "Unknown";
        }
    } else {
        if ($author == null || $author == "") {
            $author = $spotify_search["tracks"]["items"][0]["artists"][0]["name"];
        }

        if ($image == null || $image == "") {
            $image = $spotify_search["tracks"]["items"][0]["album"]["images"][0]["url"];
        }

        if ($length == null || $length == "") {
            $length = $spotify_search["tracks"]["items"][0]["duration_ms"] / 1000;
            $length = floor($length / 60) . ":" . str_pad($length % 60, 2, "0", STR_PAD_LEFT);
        }
    }

    return array($author, $image, $length, $link);
}
?>