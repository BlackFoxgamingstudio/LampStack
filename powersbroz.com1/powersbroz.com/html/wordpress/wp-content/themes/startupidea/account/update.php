<div class="col-md-12">
    <ul class="text-left submenu">
        <li><a href="?id=project"><?php _e('My Project List','themeum'); ?></a></li>
        <li class="active" ><a href="?id=update&project_id=<?php if(isset($_GET['project_id'])){ echo $_GET['project_id']; } ?>"><?php _e('Update','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">
        <?php
        if(isset( $_GET['project_id'] )){

        $post_id = $_GET['project_id'];
        $output = '';
        global $wpdb;
        $result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = '%d' AND meta_key = '%s'", $_GET['project_id'], 'project_update'));
        ?>
        <section class="update-page">
            <div class="col-md-12">
                <form name="update-submit-form" action="<?php echo esc_url(admin_url("admin-ajax.php")); ?>" method="post" class="wow fadeIn form-horizontal" id="update-submit-form">
                    
                    <input type="hidden" name="update-project" value="true">
                    <input type="hidden" name="action" value="update_form">
                    <input type="hidden" name="update-post-id" value="<?php echo esc_attr($post_id); ?>">
                    <input type="hidden" id="redirect_url" name="uri" value="<?php echo get_the_permalink(); ?>">

                    <?php
                    $i=1;
                    foreach ($result as $value) {
                       list($title, $content) = explode("*###*", $value->meta_value);
                       ?>
                       <label class="update-version"><?php _e('Project Update','themeum'); ?> <?php echo $i; ?></label>
                        <div class="update-inout-form">
                            <label><?php _e('Title','themeum'); ?></label>
                            <input type="text" class="form-control"  name="update_title_<?php echo $i; ?>" value="<?php echo esc_attr($title); ?>">
                            <input type="hidden" name="update_id_<?php echo $i; ?>" value="<?php echo $value->meta_id; ?>">
                            <label><?php _e('Message','themeum'); ?></label>
                            <textarea  class="form-control" name="update_content_<?php echo $i; ?>"><?php echo esc_textarea($content); ?></textarea>
                        </div><hr>
                        <?php
                        $i++; } 
                        ?>                    
                        <label class="update-version"><?php _e('Add Project Update Information','themeum'); ?></label>
                        <div class="update-inout-form">
                            <label><?php _e('Title','themeum'); ?></label>
                            <input class="form-control"  type="text" name="update_title_<?php echo $i; ?>" >
                            <label><?php _e('Message','themeum'); ?></label>
                            <textarea class="form-control"  name="update_content_<?php echo $i; ?>"></textarea>
                        </div>

                        <button id="update-submit-forms" class="btn btn-primary btn-success" type="submit"><?php _e('Update Projet','themeum'); ?></button>
                </form>
            </div>
        </section>
        <?php } ?>
    </div>
</div>
