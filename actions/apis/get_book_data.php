<?php
function get_data($data_reset, $title, $author, $image, $length, $link)
{
    // get info from google
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($title) . ($author != null && $author != "" ? urlencode(" " . $author) : "");
    $google_search_json = file_get_contents($url);
    $google_search = json_decode($google_search_json, true);

    // did we get results?
    if (count($google_search["items"]) == 0) {
        if ($data_reset) {
            $author = "Unknown";
            $image = "none";
            $length = "Unknown";
        }
    } else {
        if ($author == null || $author == "") {
            $i = 0;
            // check if there is an author
            while (!array_key_exists("authors", $google_search["items"][$i]["volumeInfo"]) && $i < count($google_search["items"])) {
                // check if the title is the same (ignore case)
                if (!strtolower($google_search["items"][$i]["volumeInfo"]["title"]) == strtolower($title)) {
                    break;
                }
                $i++;
            }
            if (array_key_exists("authors", $google_search["items"][$i]["volumeInfo"])) {
                $author = implode(", ", $google_search["items"][$i]["volumeInfo"]["authors"]);
            } else {
                $author = "Unknown";
            }
        }

        if ($length == null || $length == "") {
            $i = 0;
            // check if there is a length
            while (!array_key_exists("pageCount", $google_search["items"][$i]["volumeInfo"]) && $i < count($google_search["items"])) {
                // check if the title is the same (ignore case)
                if (!strtolower($google_search["items"][$i]["volumeInfo"]["title"]) == strtolower($title)) {
                    break;
                }
                $i++;
            }
            if (array_key_exists("pageCount", $google_search["items"][$i]["volumeInfo"])) {
                $length = $google_search["items"][$i]["volumeInfo"]["pageCount"] . " pp.";
            } else {
                $length = "Unknown";
            }
        }

        if ($image == null || $image == "") {
            $i = 0;
            // check if there is an image
            while (!array_key_exists("imageLinks", $google_search["items"][$i]["volumeInfo"]) && $i < count($google_search["items"])) {
                // check if the title is the same (ignore case)
                if (!strtolower($google_search["items"][$i]["volumeInfo"]["title"]) == strtolower($title)) {
                    break;
                }

                $i++;
            }
            if (array_key_exists("imageLinks", $google_search["items"][$i]["volumeInfo"])) {
                $image = $google_search["items"][$i]["volumeInfo"]["imageLinks"]["thumbnail"];
            } else {
                $image = "none";
            }

            // if image is not none try to get a bigger image
            if ($image != "none") {
                $zoom = 1;
                while ($zoom < 3) {
                    $image = str_replace("zoom=" . ($zoom - 1), "zoom=" . $zoom, $image);
                    $image_data = getimagesize($image);
                    if ($image_data[0] > 200) {
                        break;
                    }
                    $zoom++;
                }
            }
        }
    }

    return array($author, $image, $length, $link);
}
?>