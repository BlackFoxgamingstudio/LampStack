<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['objectid'])) {
    echo 'Improper Access'; exit;
}
$objectid       = mysqli_real_escape_string($con->gate, trim($_GET['objectid']));
$object_type    = $_GET['type'];
if ($object_type == 'project') {
    $object = Project::find('id', $objectid);
} else {
    $object = Group::find('id', $objectid);
}

if (!$object) {
    echo 'Improper Access'; exit;
}
if(isset($_GET['reply'])) {
    $reply = true;
    if (!isset($_GET['post'])) {
        echo 'Unable to find original post'; exit;
    }
    $postid = mysqli_real_escape_string($con->gate, trim($_GET['post']));
    if ($object_type == 'project') {
        $post = ProjectForumPost::find('id', $postid);
    } else {
        $post = GroupForumPost::find('id', $postid);
    }

    if (!$post) {
        echo 'Unable to find original post'; exit;
    }
} else {
    $reply = false;
    $post = false;
}
?>

<form id="createPost" action="./" method="post">
    <input type="hidden" name="create" value="<?= $object_type;?>-forum-post"/>
    <?php if ($reply) { ?>
    <input type="hidden" name="reply" value="1"/>
    <input type="hidden" name="post" value="<?=$post->id();?>"/>
    <?php } ?>
    <input type="hidden" name="<?= $object_type;?>" value="<?= $objectid;?>"/>

    <div class="row">
        <div class="col-md-12">
            <h2>Forum Post <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 paper paper-curve">
            <label for="postsubject">Subject:</label>
            <input type="text" name="postsubject" id="postsubject"/>
            <label for="postbody">Post Message:</label>
            <textarea name="postbody" id="postbody" rows="5"></textarea>
            <?php if (!$reply) { ?>
            <p>Make sticky? <input type="radio" name="sticky" value="1"/> Yes <input type="radio" name="sticky" value="0" checked/> No</p>
            <?php } ?>
            <p class="push-vertical"><button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Post it!</button></p>
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
    $('#createPost').on('submit', function(e) {
        e.preventDefault();
        var createPost = $('#createPost').serialize();
        $.post('./', createPost, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Post was successful!');
                document.getElementById('createPost').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Post', data);
            }
        });
    });
</script>
