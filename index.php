<?php
// start session
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Book List</title>
    <?php
    // if on mobile or portrait, use mobile css
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/(android|blackberry|iemobile|ipad|iphone|ipod|opera mini|webos)/i', $user_agent)) {
        echo "<link rel='stylesheet' type='text/css' media='screen' href='css/main-page-mobile.css'>";
    } else {
        echo "<link rel='stylesheet' type='text/css' media='screen' href='css/main-page.css'>";
    }
    ?>
    <script src='js/main.js'></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div id="menu">
        <?php
        // get the directory
        $dir = "fallback";
        if (isset($_GET["dir"])) {
            $dir = $_GET["dir"];

            // check for script injection
            if (strpos($dir, "..") !== false || strpos($dir, "/") !== false) {
                $dir = "fallback";
            }

            if ($dir == "") {
                $dir = "fallback";
            }

            // check if the directory exists, if not, create it
            if (!file_exists("media/" . $dir) && $dir != "fallback") {
                mkdir("media/" . $dir);
            }
        }

        if ($dir == "fallback") {
            echo "<form action='index.php' method='get' class='search'>"
                . "<input type='text' name='dir' placeholder='Book list' tabindex='0'>";
            echo "<button type='submit' title='Go to book list' tabindex='0'>"
                . "<i class='material-icons'>arrow_forward</i>"
                . "</button>"
                . "</form>";

            echo "<div class='tut-arrow'>"
                . "<i class='material-icons'>arrow_upward</i>"
                . "<p>here</p>"
                . "</div>";
        } else {
            echo "<form action='index.php' method='get' class='search'>"
                . "<input type='text' name='search' placeholder='Search' value='";
            if (isset($_GET["search"])) {
                echo $_GET["search"];
            }
            echo "' tabindex='0'>";
            echo "<input type='hidden' name='dir' value='" . $dir . "'>";
            echo "<button type='submit' title='Search' tabindex='0'>"
                . "<i class='material-icons'>search</i>"
                . "</button>"
                . "</form>";
        }

        $edit = false;
        if (isset($_GET["edit"])) {
            $edit = true;

            $media_json = file_get_contents("media/" . $_GET["dir"] . "/" . $_GET["edit"] . ".json");
            $media = json_decode($media_json, true);

            $add_media = $media["type"];
        } else {
            if (isset($_GET["add_media"])) {
                $add_media = $_GET["add_media"];
            } else {
                if (isset($_SESSION["add_media"])) {
                    $add_media = $_SESSION["add_media"];
                } else {
                    $add_media = "book";
                }
            }
        }
        $_SESSION["add_media"] = $add_media;

        switch ($add_media) {
            case "book":
                $form_tp = "book";
                $form_icon = "book";
                $form_author_name = "Author";
                break;
            case "movie":
                $form_tp = "movie";
                $form_icon = "movie";
                $form_author_name = "Director";
                break;
            case "series":
                $form_tp = "series";
                $form_icon = "tv";
                $form_author_name = "Director";
                break;
            case "video":
                $form_tp = "video";
                $form_icon = "videocam";
                $form_author_name = "Channel";
                break;
            case "song":
                $form_tp = "song";
                $form_icon = "music_note";
                $form_author_name = "Artist";
                break;
            case "album":
                $form_tp = "album";
                $form_icon = "album";
                $form_author_name = "Artist";
                break;
            case "game":
                $form_tp = "game";
                $form_icon = "videogame_asset";
                $form_author_name = "Developer";
                break;
        }

        $sort_by = "";
        $sort_order = "";
        if (isset($_GET["sort_by"])) {
            $sort_by = $_GET["sort_by"];
        }
        if (isset($_GET["sort_order"])) {
            $sort_order = $_GET["sort_order"];
        }

        if (isset($_SESSION["sort"])) {
            if (str_contains($_SESSION["sort"], ":")) {
                $ses_sort_exp = explode(":", $_SESSION["sort"]);
                if ($sort_by == "") {
                    $sort_by = $ses_sort_exp[0];
                }
                if ($sort_order == "") {
                    $sort_order = $ses_sort_exp[1];
                }
            } else {
                if ($sort_by == "") {
                    $sort_by = "custom";
                }
                if ($sort_order == "") {
                    $sort_order = "custom";
                }
            }
        }

        $_SESSION["sort"] = $sort_by . ":" . $sort_order;
        $sort = $sort_by . ":" . $sort_order;
        ?>

        <!-- Form for selecting sorting -->
        <form action="index.php" method="get" class="sort" style="<?php if ($edit || $dir == "fallback") {
            echo "display: none;";
        } ?>">
            <input type="hidden" name="dir" value="<?php echo $dir ?>">
            <div class="dropdown" style="margin: -10px;">
                <a href="index.php?dir=<?php echo $dir ?>&sort_by=custom&sort_order=custom" title="Custom" class="<?php if ($sort_order == "custom") {
                       echo "active";
                   } ?>" tabindex="0" style="height: 1.5em;">
                    <i class="material-icons" style="font-size: 1.2em;">swap_vert toc</i>
                </a>
                <a href="index.php?dir=<?php echo $dir ?>&sort_by=<?php
                   if ($sort_by == "custom") {
                       echo "title";
                   } else {
                       echo $sort_by;
                   }
                   ?>&sort_order=ascending" title="Ascending" class="<?php if ($sort_order == "ascending") {
                       echo "active";
                   } ?>" tabindex="0" style="height: 1.5em;">
                    <i class="material-icons" style="font-size: 1.2em;">arrow_upward sort</i>
                </a>
                <a href="index.php?dir=<?php echo $dir ?>&sort_by=<?php
                   if ($sort_by == "custom") {
                       echo "title";
                   } else {
                       echo $sort_by;
                   }
                   ?>&sort_order=descending" title="Descending" class="<?php if ($sort_order == "descending") {
                       echo "active";
                   } ?>" tabindex="0" style="height: 1.5em;">
                    <i class="material-icons" style="font-size: 1.2em;">arrow_downward sort</i>
                </a>
            </div>
            <hr style="<?php if ($sort_by == "custom") {
                echo "display: none;";
            } ?>">
            <select name="sort_by" onchange="this.form.submit()" tabindex="0" style="<?php if ($sort_by == "custom") {
                echo "display: none;";
            } ?>">
                <option value="title" <?php if ($sort_by == "title") {
                    echo "selected";
                } ?>>Title
                </option>
                <option value="author" <?php if ($sort_by == "author") {
                    echo "selected";
                } ?>>Artist
                </option>
                <option value="type" <?php if ($sort_by == "type") {
                    echo "selected";
                } ?>>Media
                </option>
                <option value="created" <?php if ($sort_by == "created") {
                    echo "selected";
                } ?>>Added to list
                </option>
            </select>
        </form>


        <!-- choose between type of media -->
        <div class="dropdown" <?php if ($edit || $dir == "fallback") {
            echo "style='display: none;'";
        } ?>>
            <a href="index.php?dir=<?php echo $dir ?>&add_media=book" title="Book" class="<?php if ($add_media == "book") {
                   echo "active";
               } ?>" tabindex="0">
                <i class="material-icons">book</i>
            </a>
            <div class="category">
                <a href="index.php?dir=<?php echo $dir ?>&add_media=movie" title="Movie" class="<?php if ($add_media == "movie") {
                       echo "active";
                   } ?>" tabindex="0">
                    <i class="material-icons">movie</i>
                </a>
                <a href="index.php?dir=<?php echo $dir ?>&add_media=series" title="Series" class="<?php if ($add_media == "series") {
                       echo "active";
                   } ?>" tabindex="0">
                    <i class="material-icons">tv</i>
                </a>
                <a href="index.php?dir=<?php echo $dir ?>&add_media=video" title="Video" class="<?php if ($add_media == "video") {
                       echo "active";
                   } ?>" tabindex="0">
                    <i class="material-icons">videocam</i>
                </a>
            </div>
            <div class="category">
                <a href="index.php?dir=<?php echo $dir ?>&add_media=song" title="Song" class="<?php if ($add_media == "song") {
                       echo "active";
                   } ?>" tabindex="0">
                    <i class="material-icons">music_note</i>
                </a>
                <a href="index.php?dir=<?php echo $dir ?>&add_media=album" title="Album" class="<?php if ($add_media == "album") {
                       echo "active";
                   } ?>" tabindex="0">
                    <i class="material-icons">album</i>
                </a>
            </div>
            <a href="index.php?dir=<?php echo $dir ?>&add_media=game" title="Game" class="<?php if ($add_media == "game") {
                   echo "active";
               } ?>" tabindex="0">
                <i class="material-icons">videogame_asset</i>
            </a>
        </div>

        <?php
        if ($dir != "fallback") {
            include "add_form.php";

            $fdir = "media/" . $dir;
            $files = scandir($fdir);
            // filter out the . and .. files
            $files = array_diff($files, array('.', '..'));

            // if there is a search term
            if (isset($_GET["search"])) {
                $search = $_GET["search"];

                include("media_search.php");
            }

            if (count($files) < 1 && !isset($_GET["search"])) {
                echo "<div class='tut-arrow'>"
                    . "<i class='material-icons'>arrow_upward</i>"
                    . "<p>You can add media to it by selecting the type and typing in a name</p>"
                    . "</div>";
            }

            echo "<a href='index.php' title='Go back' class='back' tabindex='0'>"
                . "<i class='material-icons'>arrow_back</i>"
                . "</a>"
                . "<a href='actions/delete_list.php?dir=" . $dir . "' title='Delete book list' class='delete' tabindex='0'>"
                . "<i class='material-icons'>delete</i>"
                . "</a>";
        }
        ?>

    </div>
    <div id="content">
        <?php
        if ($dir != "fallback") {
            $fdir = "media/" . $dir;
            $files = scandir($fdir);
            $to_view = false;

            include("media_list.php");
        }

        if ($dir == "fallback" || count($files) == 0) {
            if ($dir == "fallback") {
                echo "<div class='no-media'>Enter a book list name to get started...</div>";
            } else {
                echo "<div class='no-media'>This list is empty...</div>";
            }
        }
        ?>
    </div>
</body>

</html>