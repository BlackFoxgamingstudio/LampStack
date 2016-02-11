<div class="message-board">

    <div class="message-board-head">
        <h3><?= $object->name();?> Forum</h3>
    </div>

    <div class="message-board-body">

        <?php if ($posts) { ?>

            <?php for ($p = 0; $p < count($posts);$p++) { ?>
                <?php if ($posts[$p]->is_sticky()) { ?>
                    <div class="row message-board-post sticky-post">
                        <div class="col-md-2 text-center">
                            <img class="img img-responsive img-thumbnail" src="<?= $posts[$p]->author()->img();?>" alt="<?= $posts[$p]->author()->name();?> avatar" width="97"/>
                            <p class="subdued"><?= $posts[$p]->created()->format('F d, M');?><br/><i class="fa fa-clock-o"></i> <?= readable_time($posts[$p]->created());?></p>
                            <p><span class="label label-info">Sticky</span></p>
                        </div>
                        <div class="col-md-8">
                            <h4 class="media-heading"><span class="label label-primary"><i class="fa fa-user"></i> <?= $posts[$p]->author()->name();?></span> <?= $posts[$p]->subject();?></h4>
                            <p><?= $posts[$p]->body();?></p>
                        </div>
                    </div>
                <?php } // End if sticky IF Block ?>
            <?php } // End posts FOR Block ?>

            <?php for ($p = 0;$p < count($posts);$p++) { ?>
                <?php if (!$posts[$p]->is_sticky() && !$posts[$p]->is_reply()) { ?>
                    <div class="row message-board-post">
                        <div class="col-md-2 text-center">
                            <img class="img img-responsive img-thumbnail" src="<?= $posts[$p]->author()->img();?>" alt="<?= $posts[$p]->author()->name();?> avatar" width="97"/>
                            <p class="subdued"><?= $posts[$p]->created()->format('F d, M');?><br/><i class="fa fa-clock-o"></i> <?= readable_time($posts[$p]->created());?></p>
                        </div>
                        <div class="col-md-10">
                            <h4 class="media-heading"><span class="label label-primary"><i class="fa fa-user"></i> <?= $posts[$p]->author()->name();?></span> <?= $posts[$p]->subject();?></h4>
                            <p><?= $posts[$p]->body();?></p>

                            <?php if ($posts[$p]->has_replies()) { ?>
                                <?php $replies = $posts[$p]->get_replies();?>

                                <?php for ($r = 0; $r < $replies['count'];$r++) { ?>
                                    <?php
                                    if ($object_type == 'project') {
                                        $reply = new ProjectForumPost($replies['rows'][$r]);
                                    } else {
                                        $reply = new GroupForumPost($replies['rows'][$r]);
                                    }
                                    ?>
                                    <div class="message-board-post-replies">
                                        <h5><span class="label label-primary"><i class="fa fa-user"></i> <?= $reply->author()->name();?></span> <?= $reply->subject();?> <img src="<?= $reply->author()->img();?>" width="32" class="img img-circle pull-right"/></h5>
                                        <p><?= $reply->body();?></p>
                                    </div>
                                <?php } // End replies FOR Block ?>

                            <?php } // End if has replies IF Block ?>
                            <hr/>
                            <p><span class="btn btn-success createReplyBtn" post="<?= $posts[$p]->id();?>"><i class="fa fa-plus"></i> Reply to this thread</span></p>

                        </div>
                    </div>
                <?php } // End if not sticky IF Block ?>
            <?php } // End posts FOR Block ?>

        <?php } else { ?>
        <div class="nothing-full">
            <h3>There are no posts to display</h3>
            <p>Would you like to create one?</p>
        </div>
        <?php } // End if posts IF Block ?>
        <p class="push-vertical">
            <span class="btn btn-success createPostBtn"><i class="fa fa-plus"></i> Post New Message</span>
        </p>
    </div>

</div>

<script>
    $('.createPostBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/new.forum.post.form.php', {type: '<?= $object_type;?>', objectid: "<?= $object->id();?>"});
    });
    $('.createReplyBtn').click(function() {
        var selectedPost = $(this).attr('post');
        openForm('<?= BASE_URL;?>app/views/forms/new.forum.post.form.php', {type: '<?= $object_type;?>', objectid: "<?= $object->id();?>", reply: 1, post: selectedPost});
    });
</script>