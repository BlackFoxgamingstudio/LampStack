<?php
/*
 * Template Name: Page Project Form
 */
get_header(); ?>


<div id="welcome-msg" class="modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <i class="fa fa-close close" data-dismiss="modal" id="form-submit-close"></i>
             </div>
             <div class="modal-body text-center">
                 <h3><?php _e('Welcome','themeum') ?></h3>
                 <p><?php _e('Your Project is submitted for review.','themeum') ?></p>
             </div>
         </div>
    </div> 
</div>

<div id="error-msg" class="modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <i class="fa fa-close close" data-dismiss="modal"></i>
             </div>
             <div class="modal-body text-center">
                 <h3><?php _e('Error','themeum') ?></h3>
                 <p><?php _e('Please fill Project Title and Project Budget.','themeum') ?></p>
             </div>
         </div>
    </div> 
</div>

<section id="main" class="clearfix">
   <?php get_template_part('lib/sub-header')?>
    <div id="content" class="site-content container" role="main">


        <?php while ( have_posts() ): the_post(); ?>
        

        <!-- start project form -->
        <section id="project-form">
            <div class="container">
                <div class="row">
                <?php if( get_current_user_id()!= 0 ): ?>
                               
                    <div class="clearfix"></div>
                    <!-- form progressbar -->
                    <div id="progressbar" class="col-sm-12">
                       <?php the_content(); ?>
                    </div> <!-- end progressbar -->

                    <!-- input form -->
                    <div class="col-sm-8 input-form">
                       

                        <form name="project-submit-form" action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="post" class="wow fadeIn" id="project-submit-form">
                        
                        <!--div class="project-submit-form form-show wow fadeIn" data-wow-delay=".2s"-->

                            <div class="form-group has-feedback">
                                <label for="project-title"><?php _e('Project Title','themeum') ?></label>
                                <input type="hidden" name="new-project" id="new-project" value="new">
                                <input type="hidden" name="action" value="new_project_add">
                                <input type="hidden" name="url_red" id="redirect_url_add" value="<?php echo esc_url(site_url()); ?>">
                                <input type="text" name="project-title" class="form-control" placeholder="Project Title" id="project-title">
                                <span class="glyphicon glyphicon-ok form-control-feedback title-color"></span>
                            </div>

                            <div class="form-group has-feedback form-upload-image">
                                <label for="project-image"><?php _e('Project Image','themeum') ?></label>
                                <input type="text" name="project-image" class="form-control upload_image_url" id="project-image">
                                <input type="hidden" name="project-image-id" class="form-control upload_image_id" id="project-image">
                                <input type="button" id="cc-image-upload-file-button" class="custom-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <div class="form-group has-feedback form-upload-image">
                                <label for="banner-image"><?php _e('Banner Image','themeum') ?></label>
                                <input type="text" name="banner-image" class="form-control banner_image_url" id="banner-image">
                                <input type="hidden" name="banner-image-id" class="form-control banner_image_id" id="banner-image">
                                <input type="button" id="cc-image-upload-file-button" class="banner-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <div class="form-group has-feedback">
                                <label for="project-category"><?php _e('Project Category','themeum') ?></label>
                                <select name="project-category" id="project-category">
                                    <?php 
                                    $all_cat = get_terms('project_category'); 
                                    foreach ($all_cat as $value) {
                                        echo '<option value="'.$value->slug.'">'.$value->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="project-tag"><?php _e('Project Tag','themeum') ?></label>
                                <input type="text" name="project-tag" class="form-control" placeholder="ex: food, arts" id="project-tag">
                                <span class="glyphicon glyphicon-ok form-control-feedback tag-color"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="project-category"><?php _e('Project Type','themeum') ?></label>
                                <select name="project-type">
                                    <option value="Profitable">Profitable</option>
                                    <option value="Non Profitable">Non Profitable</option>
                                </select>
                            </div>

                            <div class="form-group project-duration">
                                <label for="duration"><?php _e('Project Duration','themeum') ?></label>
                                <input type="text" name="start-date" class="form-control datepicker" value="03/04/2015"><i class="divider fa fa-minus"></i>
                                <input type="text" name="end-date" class="form-control datepicker" value="05/14/2015"> 
                            </div>

                            <div class="form-group has-feedback">
                                <label for="location"><?php _e('location','themeum') ?></label>
                                <input type="text" name="location" class="form-control" id="location">
                                <span class="glyphicon glyphicon-ok form-control-feedback location-color"></span>
                            </div>

                            <div class="form-group">
                                <label for="duration"><?php _e('Funding goal','themeum') ?></label>
                                <div class="input-group currency-change">
                                    <input type="text" name="investment-amount" id="investment-amount" class="form-control" >
                                    <div class="input-group-addon fund-goal">
                                        <span><?php echo  themeum_get_currency_code(); ?></span>
                                    </div>
                                </div>
                            </div>
                         
                        <!--/div-->

                        <!--div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s"-->

                            <div class="form-group has-feedback">
                                <label for="project-video"><?php _e('Video URL','themeum') ?></label>
                                <input type="text" name="video-url" class="form-control" placeholder="Youtube Or Vimeo URL" id="project-video">
                                <span class="glyphicon glyphicon-ok form-control-feedback video-color"></span>
                            </div>

                            <div class="form-group">
                                <label><?php _e('Project Description','themeum') ?></label>
                                <div>
                                    <?php
                                    $editor_id = 'description';
                                    $content = '';
                                    wp_editor( $content, $editor_id );
                                    ?>   
                                </div>
                            </div>


                            <div class="form-group">
                                <label><?php _e('Reward','themeum'); ?></label>
                                <div id="clone-form">   
                                    <div class="auto-field">
                                        <label><?php _e('Range:','themeum'); ?></label>
                                        <input type="text" name="min1" placeholder="<?php _e('Minimum','themeum'); ?>" class="form-control min" ><i class="spce fa fa-minus"></i>
                                        <input type="text" name="max1" placeholder="<?php _e('Maximum','themeum'); ?>" class="form-control max" >
                                        <label class="reward-level"><?php _e('Reward1','themeum') ?></label>
                                        <textarea name="reward1" class="form-control"></textarea><hr/>
                                    </div>
                                </div>
                                <span id="add-more"><?php _e('Add More','themeum'); ?></span>
                            </div>


                        <!--/div-->

                        <!--div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s"-->

                            <div class="form-group has-feedback">
                                <label for="project-about"><?php _e('Message to Admin','themeum') ?></label>
                                <textarea id="project-about" name="about"></textarea>
                                <span class="glyphicon glyphicon-ok form-control-feedback about-color"></span>
                            </div>

                        <!--/div-->
                        <button id="project-submit" class="btn btn-primary pull-right" type="submit"><?php _e('Submit Project','themeum') ?></button>

                        </form>

                    </div> <!-- end input form -->

                    <!-- startup sample -->
                    <div id="popular-ideas" class="col-sm-4">
                        <div class="ideas-item wow fadeIn">
                            <div class="image">
                                <figure>
                                    <img src="<?php echo  get_template_directory_uri(); ?>/images/preview.jpg" class="img-responsive image-view" alt="">
                                    <figcaption>
                                        <p>0%</p>
                                        <p class="pull-left"><?php _e('Rise Funded','themeum'); ?></p>
                                        <ul class="list-unstyled list-inline rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                        </ul>
                                    </figcaption>
                                </figure>
                            </div> <!-- end image -->

                            <div class="clearfix"></div>

                            <div class="details">
                                <div class="country-name" id="auto-location"><?php _e('Destination, Country','themeum'); ?></div>
                                <h4 id="auto-title"><?php _e('Sample Title','themeum'); ?></h4>
                                <div class="entry-meta">
                                    <i class="fa fa-tags"></i> <span class="entry-food" id="auto-tag" > <?php _e('food','themeum'); ?></span>
                                    <span class="entry-money"><i class="fa fa-money"></i> <?php _e('Total investment:','themeum'); ?> <strong id="auto-investment"><?php echo themeum_get_currency_symbol(); ?><?php _e('9800.00','themeum'); ?></strong></span>
                                </div>
                                <div class="info" id="auto-description">
                                    <p>
                                        <?php _e('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, repudiandae nisi in ea eaque ut dolorem obcaecati sit error quo facilis, officiis officia. Dignissimos fugiat, voluptatem, ipsa adipisci neque modi.','themeum'); ?>
                                    </p>
                                    <p>
                                        <?php _e('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, repudiandae nisi in ea eaque ut dolorem obcaecati sit error quo facilis, officiis officia. Dignissimos fugiat, voluptatem, ipsa adipisci neque modi.','themeum'); ?>
                                    </p>
                                </div>
                            </div> 
                        </div>
                    </div> <!-- end startup sample -->
                <?php else: ?>
                    <div class="col-sm-4 col-sm-offset-4 text-center">
                        <div class="alert alert-danger text-center" role="alert"><?php _e('Login to project submit','themeum') ?></div>
                    </div>
                    <?php
                        if ( shortcode_exists( 'custom_login' ) ) {
                          echo do_shortcode( '[custom_login]' );
                        }
                    ?>
                <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- end project form -->
                     
        <?php endwhile; ?>

    </div> <!--/#content-->
</section> <!--/#main-->
<?php get_footer();
