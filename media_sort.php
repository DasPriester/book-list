<?php
function media_sort($files, $sort, $fdir)
{
    // prevent script injection
    $sort = str_replace("..", "", str_replace("/", "", $sort));

    if (str_contains($sort, ":")) {
        $sort_exp = explode(":", $sort);
        $sort_by = $sort_exp[0];
        $sort_order = $sort_exp[1];
    } else {
        $sort_by = $sort;
        $sort_order = "ascending";
    }

    // sort the files
    usort($files, function ($a, $b) use ($sort_by, $sort_order) {
        global $fdir;

        // open the json file
        $media_json_a = file_get_contents($fdir . "/" . $a);
        $media_json_b = file_get_contents($fdir . "/" . $b);
        // decode the json
        $media_a = json_decode($media_json_a, true);
        $media_b = json_decode($media_json_b, true);

        if (!isset($media_a[$sort_by]) || !isset($media_b[$sort_by])) {
            return 0;
        }
        // sort by $sort_by
        if ($sort_order == "ascending" || $sort_order == "custom") {
            return strcmp($media_a[$sort_by], $media_b[$sort_by]);
        } else if ($sort_order == "descending") {
            return strcmp($media_b[$sort_by], $media_a[$sort_by]);
        } else {
            return 0;
        }
    });

    return $files;
}
?>