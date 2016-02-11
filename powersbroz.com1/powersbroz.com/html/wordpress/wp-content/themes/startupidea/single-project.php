<?php get_header(); 
global $themeum_options;
?>

        <?php if ( have_posts() ) :  ?> 

            <?php while ( have_posts() ) : the_post(); ?>
                
                <?php
                    $location = esc_attr(rwmb_meta("thm_location"));
                    $project_type = esc_attr(rwmb_meta("thm_type"));
                    $funding_goal = esc_attr(rwmb_meta("thm_funding_goal"));
                    $video_source = esc_url(rwmb_meta( 'thm_video_url' )); 
                    $themeum_reward = rwmb_meta( 'themeum_reward' ); 

                     if ( isset($video_source) && $video_source) {
                       
                      $video = parse_url($video_source);
                      switch($video['host']) {
                        case 'youtu.be':
                          $id = trim($video['path'],'/');
                          $src = '//www.youtube.com/embed/' . $id;
                        break;
                        
                        case 'www.youtube.com':
                        case 'youtube.com':
                          parse_str($video['query'], $query);
                          $id = $query['v'];
                          $src = '//www.youtube.com/embed/' . $id;
                        break;
                        
                        case 'vimeo.com':
                        case 'www.vimeo.com':
                          $id = trim($video['path'],'/');
                          $src = "//player.vimeo.com/video/{$id}";
                      }   

                    }             
                                

                    $output = '';
                    $image_attached = esc_attr(get_post_meta( $post->ID , 'thm_subtitle_images', true ));
                      if(!empty($image_attached)){
                      $sub_img = wp_get_attachment_image_src( $image_attached , 'blog-full'); 
                      $output = 'style="background-image:url('.esc_url($sub_img[0]).');background-size: cover;background-position: 50% 50%;padding: 100px 0;"';
                      if(empty($sub_img[0])){
                        $output = 'style="background-color:'.esc_attr(rwmb_meta("thm_subtitle_color")).';padding: 100px 0;"';
                        if(rwmb_meta("thm_subtitle_color") == ''){
                          $output = thmtheme_call_sub_header();
                        }
                      }
                    }else{
                      if(rwmb_meta("thm_subtitle_color") != "" ){
                        $output = 'style="background-color:'.esc_attr(rwmb_meta("thm_subtitle_color")).';padding: 100px 0;"';
                      }
                    } ?>
                  
                  <!-- start breadcrumbs -->
                  <section class="project-breadcrumbs" <?php echo $output; ?>>
                     <div class="container">
                         <div class="row">
                             <div class="col-sm-12">

                                 <?php themeum_breadcrumbs(); ?>
                                 <h4 class="wow fadeInDown" data-wow-delay=".5s"><?php echo esc_attr($location); ?></h4>
                                 <h2 class="wow fadeInDown" data-wow-delay=".2s"><?php echo get_the_title(); ?></h2>
                                 <div class="entry-meta wow fadeInDown" data-wow-delay=".1s">
                                      <span class="entry-food"><?php echo get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ); ?></span>
                                      <span class="entry-money"><i class="fa fa-money"></i> <?php echo esc_attr($project_type); ?></span>
                                      <span class="entry-rate"> <?php echo themeum_get_ratting_data(get_the_ID()); ?> </span>
                                  </div>
                                  <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=".get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Invest To This Project','themeum'); ?></a>
                                  <a href="<?php echo get_permalink(get_option('user_profile_page_id')); ?>?user_id=<?php the_author_meta( 'ID' ); ?>" data-wow-delay=".1s" class="btn btn-primary btn-profile wow fadeInDown"><?php _e('View Profile','themeum'); ?></a>
                             </div>
                         </div>
                     </div>
                  </section>
                  <!-- end breadcrumbs -->

                  <!-- start About this project -->
                  <div id="about-project">
                      <div class="container">
                          <div class="row">
                              <div class="col-md-8 project-info">

                                  <?php if ( isset($video_source) && $video_source) { ?>
                                    <div class="row">
                                      <div class="col-sm-12  wow fadeIn project-video">
                                      <div class="embed-responsive embed-responsive-16by9">
                                         <iframe class="embed-responsive-item" src="<?php echo esc_url($src) ; ?>"></iframe>
                                      </div>
                                      </div> 
                                    </div>
                                  <?php }?>
                                  

                                  <?php if ( is_single() ) { 
                                      the_content();
                                  } ?>

                                  <?php 
                                    $result = get_post_meta(get_the_ID(),'project_update'); 
                                    if(count($result)>0):
                                  ?>
                                      <div class="row project-updates">
                                          <h2 class="main-title col-sm-12"><?php _e('Project Updates','themeum'); ?> (<?php echo count($result); ?>)</h2>
                                          <?php
                                            $i=1;
                                            foreach ($result as $value){
                                                list($title, $content) = explode("*###*", $value);
                                          ?>
                                                <div class="col-sm-12">
                                                    <div class="media each-update wow fadeIn">
                                                        <div class="media-left">
                                                            <div class="update-number"><?php echo esc_attr($i); ?></div>
                                                        </div>
                                                        <div class="media-body">
                                                            <h3><?php echo esc_attr($title); ?></h3>
                                                            <h4><?php _e('Update','themeum'); ?> #<?php echo esc_attr($i); ?> </h4>
                                                            <p><?php echo esc_attr($content); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                          <?php 
                                              $i++;
                                            }
                                          ?>
                                      </div>
                                  <?php endif; ?>


                              </div>
                              <div class="col-md-4 project-status">
                                <div class="row">
                                  <div class="col-md-12 col-sm-4 col-xs-12 wow fadeInRight" data-wow-delay=".1s">
                                      <h2><?php echo themeum_get_project_info(get_the_ID(),'investor'); ?></h2>
                                      <h3><?php _e('Person Invest','themeum'); ?></h3>
                                  </div>
                                  <div class="col-md-12 col-sm-4 col-xs-12 wow fadeInRight" data-wow-delay=".2s">
                                      <h2><?php echo themeum_get_currency_symbol(); ?><?php echo themeum_get_project_info(get_the_ID(),'budget'); ?></h2>
                                      <h3><?php _e('Total Investment','themeum'); ?></h3>
                                  </div>
                                  <div class="col-md-12 col-sm-4 col-xs-12 wow fadeInRight" data-wow-delay=".3s">
                                      <h2><?php echo themeum_get_project_info(get_the_ID(),'percent'); ?>%</h2>
                                      <h3><?php _e('Rise Funded','themeum'); ?></h3>
                                  </div>
                                  <div class="col-sm-12 social-icon wow fadeInRight" data-wow-delay=".4s">

                                    <?php
                                    $permalink = get_permalink(get_the_ID());
                                    $titleget = get_the_title();
                                    ?>
                                    <div class="social-button">
                                      <ul class="list-unstyled list-inline">
                                          <li>
                                              <a class="facebook" onClick="window.open('http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink);?>','Facebook','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>"><i class="fa fa-facebook"></i></a>
                                          </li>
                                          <li>
                                              <a class="twitter" onClick="window.open('http://twitter.com/share?url=<?php echo esc_url($permalink); ?>&amp;text=<?php echo str_replace(" ", "%20", $titleget); ?>','Twitter share','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="http://twitter.com/share?url=<?php echo esc_url($permalink); ?>&amp;text=<?php echo str_replace(" ", "%20", $titleget); ?>"><i class="fa fa-twitter"></i></a>
                                          </li>
                                          <li>
                                              <a class="g-puls" onClick="window.open('https://plus.google.com/share?url=<?php echo esc_url($permalink); ?>','Google plus','width=585,height=666,left='+(screen.availWidth/2-292)+',top='+(screen.availHeight/2-333)+''); return false;" href="https://plus.google.com/share?url=<?php echo esc_url($permalink); ?>"><i class="fa fa-google-plus"></i></a>
                                          </li>
                                          <li>
                                              <a class="linkedin" onClick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url($permalink); ?>','Linkedin','width=863,height=500,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url($permalink); ?>"><i class="fa fa-linkedin"></i></a>
                                          </li>
                                          <li>
                                            <a class="pinterest" href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><i class="fa fa-pinterest"></i></a>
                                          </li>
                                          
                                      </ul>
                                    </div>
                                  </div>

                                  <div class="col-md-12 startup-reward">
                                    <div class="reward-title"><?php _e( 'Project Rewards','themeum' ); ?></div>
                                    <?php if(is_array( $themeum_reward )): 
                                      foreach ( $themeum_reward as $value) {
                                    ?>
                                        <div class="reward-child wow fadeInRight" data-wow-delay=".4s">
                                          <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=".get_the_ID(); ?><?php echo "&reward=".$value['themeum_min']; ?>">
                                            <div class="reward-overlay">
                                              <div class="reward-renge"><?php echo themeum_get_currency_symbol(); ?><?php echo $value['themeum_min']; ?> - <?php echo themeum_get_currency_symbol(); ?><?php echo $value['themeum_max']; ?></div>
                                              <div class="reward-details">
                                                  <?php echo $value['themeum_reward_data']; ?>
                                              </div>
                                            </div>
                                          </a>
                                        </div>
                                    <?php } endif; ?>
                                  </div>

                              </div>
                              </div>
                              
                          </div>
                      </div>
                  </div> <!-- end About this project -->

                  <div class="container project-comment"> 
                      <?php get_template_part( 'post-format/user-profile' ); ?> 
                      <?php
                          if ( comments_open() || get_comments_number() ) {
                              if ( isset($themeum_options['blog-single-comment-en']) && $themeum_options['blog-single-comment-en'] ) {
                                 comments_template();
                              }
                          }
                      ?>
                  </div>

                  <!-- Other Related Project -->
                  <div class="container">
                      <div class="row">
                        <div id="popular-ideas" class="col-sm-12 related-project-wrap">
                        <div class ="related-project-title"><?php _e('Other Project','themeum'); ?></div>
                          <div class="row">
                            <?php
                            // The Query
                            query_posts( array( 
                                                'post_type'         => 'project',
                                                'posts_per_page'    =>  3
                                                ) );
                            while ( have_posts() ) : the_post(); 
                            ?>
                                  <div class="related-project col-sm-4 item wow fadeInUp" data-wow-duration="800ms" data-wow-delay="200ms">
                                    <div class="ideas-item">
                                      <div class="image">
                                          <div class="fund-progress"><div class="bar" style="width:<?php echo themeum_get_project_info(get_the_ID(),'percent'); ?>%"></div></div>
                                           <a href="<?php echo get_the_permalink(); ?>">
                                          <figure>
                                              <?php
                                              if ( has_post_thumbnail() && ! post_password_required() ) {
                                                  echo get_the_post_thumbnail( get_the_ID(), 'project-thumb', array('class' => 'img-responsive'));
                                              }else { ?>
                                                  <div class="no-image"></div>
                                              <?php } ?>
                                              <figcaption>
                                                  <p><?php echo themeum_get_project_info(get_the_ID(),'percent'); ?>%</p>
                                                  <p class="pull-left"><?php _e("Rise Funded","themeum"); ?></p>
                                                  <?php echo themeum_get_ratting_data(get_the_ID()); ?>
                                              </figcaption>
                                          </figure>
                                          </a>
                                      </div>
                                  
                                      <div class="details">
                                          <div class="country-name"><?php echo esc_attr($location); ?></div>
                                          <h4><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
                                          <div class="entry-meta">
                                              <span class="entry-food"><?php echo get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ); ?></span>
                                              <span class="entry-money"><i class="fa fa-money"></i> <?php _e('Investment:','themeum'); ?> <strong> <?php echo themeum_get_currency_symbol().esc_attr($funding_goal); ?></strong></span>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                           <?php     
                            endwhile;
                            wp_reset_query();
                            ?>
                            </div>
                        </div>
                      </div>
                  </div>

            <?php endwhile; ?>
 
        <?php else: ?>
            <?php get_template_part( 'post-format/content', 'none' ); ?>
        <?php endif; ?>

        <div class="clearfix"></div>
        
<?php get_footer();