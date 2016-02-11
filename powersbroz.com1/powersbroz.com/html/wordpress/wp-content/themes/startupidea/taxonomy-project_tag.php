<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

get_header();
?>
<section id="main" class="clearfix">

  <?php get_template_part('lib/sub-header')?>

  <div id="page" class="container">
    <div id="popular-ideas" class="row">

         <?php  if ( have_posts() ) {
            while(have_posts()) { the_post(); 
            $location = esc_attr(rwmb_meta("thm_location"));
            $funding_goal = esc_attr(rwmb_meta("thm_funding_goal"));
            ?>
        <div id="content" class="site-content" role="main">
          <div class="col-sm-4">
              <div class="ideas-item">
                  <div class="image">
                      <a href="<?php echo get_permalink($id);?>">
                        <figure>
                        <?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
                            <?php echo get_the_post_thumbnail( get_the_ID(), 'project-thumb', array('class' => 'img-responsive')); ?>
                            <?php } else { ?>

                            <div class="no-image"></div>

                            <?php }?>
                            <figcaption>
                                <p><?php echo themeum_get_project_info(get_the_ID(),'percent'); ?>%</p>
                                <p class="pull-left"><?php esc_attr_e("Rise Funded","themeum"); ?></p>
                                <?php echo themeum_get_ratting_data(get_the_ID()); ?>
                            </figcaption>
                        </figure>
                      </a>
                  </div> <!--/.image--> 
              
                  <div class="clearfix"></div>
              
                  <div class="details">
                      <div class="country-name"> <?php echo esc_attr($location); ?></div>
                      <h4><a href="<?php echo get_permalink($id);?>"><?php the_title();?></a></h4>
                      <div class="entry-meta">
                          <span class="entry-food"><?php echo get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ); ?></span>
                          <span class="entry-money"><i class="fa fa-money"></i> <?php esc_attr_e('Investment:', 'themeum')?> <strong><?php echo themeum_get_currency_symbol().esc_attr($funding_goal); ?></strong></span>
                      </div>
                  </div>
              </div> <!--/.ideas-item-->
          </div> <!--/.col-sm-4-->
        </div> <!--/.col-sm-4-->

      <?php 
        }
      }
      ?>

    </div> <!-- /.row-->
  </div> <!-- /.container-->
</section> 
<?php
get_footer();