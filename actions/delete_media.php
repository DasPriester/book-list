<?php
// deletes media from the media folder

// get the id from the url
$id = $_GET["id"];
$dir = $_GET["dir"];

// delete the file
unlink("../media/" . $dir . "/" . $id . ".json");

// redirect to the home page
header("Location: ../index.php?dir=" . $dir);
?>