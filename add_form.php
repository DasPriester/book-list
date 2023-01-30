<form action="actions/<?php if ($edit) {
    echo "edit_media.php?dir=" . $dir;
} else {
    echo "add_media.php?dir=" . $dir;
} ?>" method="post" class="add-<?php echo $form_tp; ?>" <?php
    if ($dir == "fallback") {
        echo " style'=display: none;'";
    } ?>>
    <label for="title">
        <i class="material-icons">
            <?php if ($edit) {
                echo "edit";
            } else {
                echo $form_icon;
            } ?>
        </i>
        <?php if ($edit) {
            echo "Edit";
        } else {
            echo "Add";
        } ?>
        <?php echo ucfirst($form_tp); ?>
    </label>
    <input type="hidden" name="type" value="<?php echo $form_tp; ?>">
    <input type="hidden" name="id" value="<?php if ($edit) {
        echo $media["id"];
    } ?>">
    <input type="text" name="title" placeholder="Title*" required value="<?php if ($edit) {
        echo $media["title"];
    } ?>">
    <input type="text" name="author" placeholder="<?php echo $form_author_name ?>" value="<?php if ($edit) {
           echo $media["author"];
       } ?>">
    <?php
    if ($edit) {
        echo "<input type='text' name='length' placeholder='Length' value='" . $media["length"] . "'>";
    }
    ?>
    <input type="text" name="link" placeholder="Link" value="<?php if ($edit) {
        echo $media["link"];
    } ?>">
    <?php
    if ($edit) {
        echo "<input type='text' name='image' placeholder='Image' value='" . $media["image"] . "'>";
    }
    ?>
    <p>* required</p>
    <div class="form-buttons">
        <?php if ($edit) {
            echo "<button type='submit' title='Cancel' formaction='index.php?dir=" . $dir . "' tabindex='0'>";
            echo "<i class='material-icons'>arrow_back</i>";
            echo "</button>";
            echo "<button type='submit' title='Delete' formaction='actions/delete_media.php?id=" . $media["id"] . "&dir=" . $dir . "' tabindex='0'>";
            echo "<i class='material-icons'>delete</i>";
            echo "</button>";
        } ?>
        <button type="submit" title="<?php if ($edit) {
            echo "Edit";
        } else {
            echo "Add";
        } ?>" tabindex="0">
            <i class="material-icons">
                <?php if ($edit) {
                    echo "check";
                } else {
                    echo "add";
                } ?>
            </i>
        </button>
    </div>
</form>