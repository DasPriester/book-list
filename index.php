<?php
// start session
session_start();

include("media_list.php");
include("media_search.php");
include("media_sort.php");

// load config
$config_json = file_get_contents("config.json");
$config = json_decode($config_json, true);

// get the stoage folder
$storage_folder = $config["storage-folder"];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Book List</title>
    <link rel='stylesheet' type='text/css' media='screen' href='css/main-page.css'>
    <script src='js/main.js'></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=0.5">
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
            if (!file_exists($storage_folder . "/" . $dir) && $dir != "fallback") {
                mkdir($storage_folder . "/" . $dir);
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

            $media_json = file_get_contents($storage_folder . "/" . $_GET["dir"] . "/" . $_GET["edit"] . ".json");
            $media = json_decode($media_json, true);

            $add_media = $media["type"];
        } else {
            if (isset($_GET["add_media"])) {
                $add_media = $_GET["add_media"];
            } else {
                if (isset($_SESSION["add_media"])) {
                    $add_media = $_SESSION["add_media"];
                } else {
                    // get first type from config
                    $add_media = array_key_first($config["types"]);
                }
            }
        }
        $_SESSION["add_media"] = $add_media;

        // get info from config "types"
        $config_type = $config["types"][$add_media];

        $form_tp = $add_media;
        $form_icon = str_replace(" ", "_", strtolower($config_type["icon"]));
        $form_artist_name = $config_type["artist-name"];

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
                } ?>>Type of media
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
            <?php
            construct_type_menu($config, $add_media, $dir);
            ?>
        </div>

        <?php
        if ($dir != "fallback") {
            include "add_form.php";

            $fdir = $storage_folder . "/" . $dir;
            $files = scandir($fdir);
            // filter out the . and .. files
            $files = array_diff($files, array('.', '..'));

            // if there is a search term
            if (isset($_GET["search"])) {
                $search = $_GET["search"];

                $files = media_search($files, $search, false, $fdir);
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
            media_list($storage_folder, $dir, "", $sort, $_GET["search"] ?? "", false);
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

<?php
function construct_type_menu($config, $add_media, $dir)
{
    $categories = array();

    foreach ($config["types"] as $name => $config_type) {
        $can_show = $config_type["key-file"] == "" || data_key_exists($config_type["key-file"]);

        if ($can_show) {
            if (!isset($categories[$config_type["category"]])) {
                // make new category if it doesn't exist
                $categories[$config_type["category"]] = array();
            }

            // add the type to the category
            $categories[$config_type["category"]][$name] = $config_type;
        }
    }

    // sort the categories
    ksort($categories);

    foreach ($categories as $category => $types) {
        echo "<div class='category'>";
        foreach ($types as $name => $config_type) {
            if ($can_show) {
                // make lowercase and replace spaces with underscores
                $icon_string = str_replace(" ", "_", strtolower($config_type["icon"]));
                echo "<a href='index.php?dir=" . $dir . "&add_media=" . $name . "' title='" . ucfirst($name) . "' class='" . ($add_media == $name ? "active" : "")
                    . "' tabindex='0'>"
                    . "<i class='material-icons'>" . $icon_string . "</i>"
                    . "</a>";
            }
        }
        echo "</div>";
    }
}

function data_key_exists($key)
{
    $url = "data/" . $key . ".txt";
    if (file_exists($url)) {
        $file = fopen($url, "r");
        if (filesize($url) != 0) {
            $content = fread($file, filesize($url));
            fclose($file);
            if ($content != "") {
                return true;
            }
        }
    }
    return false;
}
?>