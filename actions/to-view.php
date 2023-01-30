<?php
include("../media_list.php");
?>

<head>
    <meta charset='utf-8'>
    <title>Book List</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/main-page.css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body style="background: none transparent;">
    <div id="content">
        <?php
        // get the directory
        $dir = "fallback";
        if (isset($_GET["dir"])) {
            $dir = $_GET["dir"];
        }
        media_list($dir, "../", "", "", true);
        ?>
    </div>

    <!-- Button to go to site -->
    <!-- set base to _parent -->
    <a id="go-to-site" href="../index.php?dir=<?php echo $dir; ?>" target="_blank">
        <i class="material-icons">link</i>
    </a>
</body>