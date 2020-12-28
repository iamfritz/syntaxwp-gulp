<?php
    get_header();
?>
   
    <?php get_template_part('templates/section-page-banner');?>
    
    <?php get_template_part('templates/section-breadcrumbs');?>
    
    <?php wp_reset_postdata(); ?>
    
    <div class="container">

      <div class="row">

        <div class="col-sm-8 blog-main">    
            <?php // Blog post query
            
            if (have_posts()) : while (have_posts()) : the_post(); ?>

              <article class="blog-post">
                <h2 class="blog-post-title"><?php the_title();?></h2>
                             
				<?php the_content();?>	

									
              </article><!-- /.blog-post -->
            <?php // end of blog post loop.
            endwhile; 
            endif; ?>          
        </div>

        <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
            <?php get_sidebar();?>
        </div>

<?php get_footer();?>