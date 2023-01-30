<head>
    <meta charset='utf-8'>
    <title>Book List</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/main-page.css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body style="background: none transparent;">
    <div id="content">
        <?php
        // get the title and type
        $title = $_GET["title"];
        $type = $_GET["type"];

        // prevent script injection
        $title = str_replace("..", "", str_replace("/", "", $title));
        $type = str_replace("..", "", str_replace("/", "", $type));

        $author = "";
        $link = "";

        // check if the title is empty
        if ($title != "") {
            $data_reset = true;
            include "get_" . $type . "_data.php";

            $id = "temp";

            $single_view = true;
            $to_view = false;
            include("../media.php");
        }
        ?>
    </div>
</body>