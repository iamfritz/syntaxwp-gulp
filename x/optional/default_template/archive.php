<?php
    get_header();
?>

    <?php
    $post_object = get_option( 'page_for_posts' );

    if( $post_object ) {    

        $post = $post_object;
        setup_postdata( $post );
        $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
    }
    ?>    
    <?php get_template_part('templates/section-page-banner');?>
    
    <?php get_template_part('templates/section-breadcrumbs');?>
    
    <?php wp_reset_postdata(); ?>
    
    <div class="container">

      <div class="row">

        <div class="col-sm-8 blog-main">    
            <?php // Blog post query
            /*$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            query_posts(array('post_type' => 'post', 'paged' => $paged, 'showposts' => 0));*/

            if (have_posts()) : while (have_posts()) : the_post(); ?>

              <article class="blog-post">
                <h2 class="blog-post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                <div class="blog-post-meta"><?php $sac_template->post_meta_data(); ?></div>
                <?php echo $sac_template->socialsharing();?>
                <?php if ( has_post_thumbnail() ) { ?>
                <div class="embed-responsive">
                    <?php the_post_thumbnail('blog_thumbnail'); ?>
                </div>
                <?php } ?>                
                <p><?php the_excerpt();?> </p>
              </article><!-- /.blog-post -->
            <?php // end of blog post loop.
            endwhile; 

            get_template_part('templates/section-pagination');

            endif; ?>          
        </div>

        <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
            <?php get_sidebar();?>
        </div>

<?php get_footer();?>