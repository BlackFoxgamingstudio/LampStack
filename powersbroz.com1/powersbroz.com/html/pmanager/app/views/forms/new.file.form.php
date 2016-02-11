<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['folder'])) {
    $folder = '';
} else {
    $folder = $_GET['folder'];
}
?>

<form id="createFile" method="post" enctype="multipart/form-data">
    <input type="hidden" name="create" value="file"/>
    <input type="hidden" name="directory" value="<?= $folder;?>"/>
    <?php if (isset($_GET['modifier'])) { ?>
    <input type="hidden" name="modifier" value="<?= $_GET['modifier'];?>"/>
    <input type="hidden" name="stagetask" value="<?= $_GET['stagetask'];?>"/>
    <?php } ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Upload a New File <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p>Directory: <?= ($folder == '') ? 'Home Folder': $_GET['folder'];?></p>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="filename">File Name:</label>
            <p class="subdued">An easy name for your file so you can remember what it is</p>
            <input type="text" name="filename" id="filename"/>
            <label for="filedesc">File Description:</label>
            <textarea name="filedesc" id="filedesc" rows="5"></textarea>
            <p>Is this is a document? <input type="radio" name="isdoc" value="1"/> Yes <input type="radio" name="isdoc" value="0" checked /> No </p>
            <label for="upload">Select a File: Max Upload is <span class="label label-primary"><?= ini_get('upload_max_filesize');?></span></label>
            <input type="file" name="upload" id="upload"/>
            <h3>File Types Not Allowed:</h3>
            <ul class="list-unstyled list-inline">
                <?php foreach(File::list_not_allowed() as $type) { ?>
                <li>
                    <div class="file-icon">
                        <?= '.'.strtoupper($type);?>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <p><span id="createFileBtn" class="btn btn-success"><i class="fa fa-check"></i> Make it</span></p>
        </div>
    </div>
</form>

<script>
    var needsRefresh = false;
    $('#closeThis').click(function() {
        if (needsRefresh) {
            window.location.reload();
        }
        closeForm();
    });
    $('#createFileBtn').click(function() {
        $('#createFile').submit();
    });
</script>