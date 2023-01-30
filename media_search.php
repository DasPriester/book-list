<?php
function media_search($files, $search, $to_view, $fdir)
{
    // prevent script injection
    $search = str_replace("..", "", str_replace("/", "", $search));

    // filter out the files that don't match the search term
    $files = array_filter($files, function ($file) use ($search) {
        global $to_view;
        global $fdir;

        // open the json file
        $media_json = file_get_contents($fdir . "/" . $file);
        // decode the json
        $media = json_decode($media_json, true);

        // check if the title or author match the search term
        if (strpos(strtolower($media["title"]), strtolower($search)) !== false) {
            return true;
        } else if (strpos(strtolower($media["author"]), strtolower($search)) !== false) {
            return true;
        } else {
            return false;
        }
    });

    return $files;
}
?>