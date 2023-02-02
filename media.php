<?php
function media($media, $to_view, $single_view, $moveable, $editing, $css = "", $dir = "")
{
    if ($media["image"] == "none") {
        echo "<div class='media" . ($css != "" ? " " . $css : "")
            . ($to_view ? " to-view" : "")
            . ($single_view ? " single-view" : "")
            . ($editing ? " editing" : "")
            . "' style='background-color: #ccc;' tabindex='0'>";
        echo "<div class='no-image'>"
            . "<i class='material-icons'>hide_image</i>"
            . "</div>";
    } else {
        echo "<div class='media" . ($css != "" ? " " . $css : "")
            . ($to_view ? " to-view" : "")
            . ($single_view ? " single-view" : "")
            . ($editing ? " editing" : "")
            . "' style='background-image: url(" . $media["image"] . ")' tabindex='0'>";
    }
    echo "<div class='overlay'>"
        . "<h2>" . $media["title"] . "</h2>"
        . "<h3>" . $media["author"] . "</h3>"
        . "<h4>" . $media["length"] . "</h4>";
    if ($moveable) {
        echo "<a class='move_left' href='actions/edit_media.php?id=" . $media["id"] . "&dir=" . $dir . "&move=left'>"
            . "<i class='material-icons'>arrow_left</i>"
            . "</a>"
            . "<a class='move_right' href='actions/edit_media.php?id=" . $media["id"] . "&dir=" . $dir . "&move=right'>"
            . "<i class='material-icons'>arrow_right</i>"
            . "</a>";
    }

    if (!$to_view && !$single_view) {
        echo "<a class='delete' href='actions/delete_media.php?id=" . $media["id"] . "&dir=" . $dir . "'>"
            . "<i class='material-icons'>delete</i>"
            . "</a>"
            . "<a class='edit' href='index.php?edit=" . $media["id"] . "&dir=" . $dir . "'>"
            . "<i class='material-icons'>edit</i>"
            . "</a>";
    }
    if ($media["link"] != null && $media["link"] != "") {
        echo "<a class='link' href='" . $media["link"] . "' target='_blank'>"
            . "<i class='material-icons'>link</i>"
            . "</a>";
    }
    echo "</div>"
        . "</div>";
}
?>