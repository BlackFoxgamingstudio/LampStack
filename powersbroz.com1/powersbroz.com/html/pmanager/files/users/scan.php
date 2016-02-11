<?php
// Setup user's directory
require_once '../../app/boot.php';
if (!$current_user) {
    echo 'Unable to access this file directly'; exit;
}

// Initialize Home Placeholder
$home           = '<span class="folders" data-directory="home"><i class="glyphicon glyphicon-home"></i> Home</span>';
$home_directory = __DIR__ . '/'. $current_user->id().'/';

if (!isset($_GET['root'])) {
    // Root was not supplied, show home of that user's file folder
    $current_directory = $home_directory;

    $breadcrumb = $home;

} else {
    // Root was supplied, break it down and process for return
    $current_directory = $home_directory.$_GET['root'];

    $directory_tree = explode('/', $_GET['root']);

    $readable_tree = array();

    $i = 0;
    $string = '';
    foreach ($directory_tree as $item) {

        if ($i + 1 < count($directory_tree)) {
            if (!empty($item)) {
                $readable_tree[] = '<span class="folders" data-directory="'.$string.'/'.$item.'">'.$item.'</span>';
                $string .= $item;
            }
        } else {
            $readable_tree[] = '<span>'.$item.'</span>';
        }
        $i++;
    }

    $breadcrumb_readable = implode(' > ', $readable_tree);
    $breadcrumb = str_replace($current_directory, $home.' > '.$breadcrumb_readable, $current_directory);

}

$directory_contents = recursive_scan($current_directory);

?>
<div class="file-breadcrumb">
    <p><?= $breadcrumb;?></p>
</div>

<?php if (count($directory_contents) > 0) { ?>

    <ul class="data">
    <?php foreach ($directory_contents as $item) { ?>

        <?php $url = str_replace($home_directory, '', $item['path']); ?>

        <?php if ($item['type'] == 'folder') { ?>
        <li class="folders" data-directory="<?= $url;?>">

            <?php if (count($item['items']) > 0) { ?>
                <span class="icon folder full"></span>
            <?php } else { ?>
                <span class="icon folder"></span>
            <?php } ?>
            <span class="name"><?= $item['name'];?></span>
            <span class="details"><?= count($item['items']);?> items</span>
        </li>
        <?php } else { ?>

            <?php
            $file = File::find('sql', "SELECT * FROM files WHERE location='".$current_directory.$item['name']."' AND uploadedby = ".$current_user->id());
            $file = array_shift($file);
            ?>

            <li class="files">
                <span class="icon file"></span>
                <span class="name"><?= $item['name'];?></span>
                <span class="details">
                    <?= convert_bytes($item['size']);?>
                </span>
                <div class="files-controls">
                    <?php if ($file) { ?>
                    <form class="downloadFrm" style="display: inline-block;" action="./" method="post">
                        <input type="hidden" name="download" value="file"/>
                        <input type="hidden" name="fileid" value="<?= $file->id();?>"/>
                        <i data-fileId="<?= $file->id();?>" data-filename="<?= $item['name'];?>" title="Download this file" class="fa fa-download downloadFileBtn"></i>
                    </form>
                    <i data-fileId="<?= $file->id();?>" data-filename="<?= $item['name'];?>" title="Delete this file" class="fa fa-times-circle deleteFileBtn"></i>
                    <?php } else { ?>
                        <i class="fa fa-question-circle"></i> Resolution Error
                    <?php } ?>
                </div>
            </li>
        <?php } ?>

    <?php } ?>
    </ul>
<?php } else { ?>

    <div class="row">
        <div class="col-md-12 text-center">
            <i class="fa fa-times-circle-o stat"></i>
            <p>This directory is empty</p>
        </div>
    </div>

<?php } ?>

<script>
    rootFolderIndex = '<?= $_GET['root'];?>';
    function rescan(object) {
        if (object.attr('data-directory') == 'home') {
            rootFolderIndex = '';
        } else {
            rootFolderIndex = object.attr('data-directory');
        }
        refresh_files(rootFolderIndex);
    }
    $('li.folders').click(function() {
        rescan($(this));
    });
    $('span.folders').click(function() {
        rescan($(this));
    });
    $('.downloadFileBtn').click(function() {
        var form = $(this).parents('form:first');
        form.submit();
    });
    $('.deleteFileBtn').click(function() {
        var fileName = $(this).attr('data-filename');
        $.post('./', {delete: "file-by-name", file: fileName, path: rootFolderIndex}, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Delete File', data);
            }
        });
    });
</script>