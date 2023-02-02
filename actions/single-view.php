<?php
include("../media.php");
?>

<head>
    <meta charset='utf-8'>
    <title>Book List</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/main-page.css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body style="background: none transparent;">
    <div id="content" style="overflow: hidden;">
        <?php
        // get the title and type
        $title = $_GET["title"];
        $type = $_GET["type"];

        // get the config
        include "../config.php";

        // get the layout from the config
        $layout = $config["types"][$type]["layout"];

        // prevent script injection
        $title = str_replace("..", "", str_replace("/", "", $title));
        $type = str_replace("..", "", str_replace("/", "", $type));

        $author = "";
        $link = "";

        // check if the title is empty
        if ($title != "") {
            include "apis/get_" . $type . "_data.php";
            $data = get_data(true, $title, $author, "", "", "");

            $media = array(
                "type" => $type,
                "title" => $title,
                "author" => $data[0],
                "image" => $data[1],
                "length" => $data[2],
                "link" => $data[3]
            );

            media($media, false, true, false, false, $layout);
        }
        ?>
    </div>
</body>