<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active" ><a href="?id=invest"><?php _e('Ratting','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content col-md-offset-2">

        <h3><?php echo __('Project Ratting','themeum'); ?></h3>
        <form name="ratting-submit-form" action="<?php echo esc_url(admin_url("admin-ajax.php")); ?>" method="post" class="wow fadeIn form-horizontal" id="ratting-submit-form">
            <input type="hidden" name="ratting-post-id" value="<?php echo esc_attr($_GET['project_id']); ?>">
            <input type="hidden" id="redirect_url_ratting" name="uri" value="<?php echo get_the_permalink(); ?>">
            <input type="hidden" name="action" value="ratting_update">
            <input type="radio" name="project_ratting" value="5" checked> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i><br>
            <input type="radio" name="project_ratting" value="4"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i><br>
            <input type="radio" name="project_ratting" value="3"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i><br>
            <input type="radio" name="project_ratting" value="2"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i><br>
            <input type="radio" name="project_ratting" value="1"> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i><br>
            <button class="btn btn-primary btn-success" type="submit"><?php echo __('Rate it!','themeum'); ?></button>
        </form>

    </div>
</div>