<?php
if ($image == "none") {
    echo "<div class='media" . ($type == "song" || $type == "album" ? " squared" : "") . ($type == "video" ? " landscape" : "") . ($to_view ? " to-view" : "") . ($single_view ? " single-view" : "") . ($editing ? " editing" : "") . "' style='background-color: #ccc;' tabindex='0'>";
    echo "<div class='no-image'>"
        . "<i class='material-icons'>hide_image</i>"
        . "</div>";
} else {
    echo "<div class='media" . ($type == "song" || $type == "album" ? " squared" : "") . ($type == "video" ? " landscape" : "") . ($to_view ? " to-view" : "") . ($single_view ? " single-view" : "") . ($editing ? " editing" : "") . "' style='background-image: url($image)' tabindex='0'>";
}
echo "<div class='overlay'>"
    . "<h2>$title</h2>"
    . "<h3>$author</h3>"
    . "<h4>$length</h4>";
if ($moveable) {
    echo "<a class='move_left' href='actions/edit_media.php?id=$id&dir=" . $dir . "&move=left'>"
        . "<i class='material-icons'>arrow_left</i>"
        . "</a>"
        . "<a class='move_right' href='actions/edit_media.php?id=$id&dir=" . $dir . "&move=right'>"
        . "<i class='material-icons'>arrow_right</i>"
        . "</a>";
}

if (!$to_view && !$single_view) {
    echo "<a class='delete' href='actions/delete_media.php?id=$id&dir=" . $dir . "'>"
        . "<i class='material-icons'>delete</i>"
        . "</a>"
        . "<a class='edit' href='index.php?edit=$id&dir=" . $dir . "'>"
        . "<i class='material-icons'>edit</i>"
        . "</a>";
}
if ($link != null && $link != "") {
    echo "<a class='link' href='$link' target='_blank'>"
        . "<i class='material-icons'>link</i>"
        . "</a>";
}
echo "</div>"
    . "</div>";
?>