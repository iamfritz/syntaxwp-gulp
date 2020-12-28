<?php
 # VIEW
/*$popularpost = new WP_Query( array( 'posts_per_page' => 4, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );
while ( $popularpost->have_posts() ) : $popularpost->the_post();
the_title();
endwhile;*/
// [popularpost total="foo-value"]
function popularpost( $atts ) {
    $a = shortcode_atts( array(
        'total' => 4
    ), $atts );
    $html = '';
    $popularpost = new WP_Query( array( 
        'posts_per_page'  => 4, 
        'meta_key'        => 'wpb_post_views_count', 
        'orderby'         => 'meta_value_num', 
        'order'           => 'DESC'  ) 
    );
    while ( $popularpost->have_posts() ) : $popularpost->the_post();
      $html .= '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
    endwhile;
    wp_reset_postdata();
    
    return '
            <ul class="custom-ul">
              '.$html.'
            </ul>
    ';
}
add_shortcode( 'popularpost', 'popularpost' );