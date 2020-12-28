<?php
/**
* class for html tags 
*/
#Notes need to review it
class sac_template {

    function __construct() {
        add_action('wp_head', array($this, 'fbogmeta_header') );
        require_once("model/shareCount.class.php");
    }

    function breadcrumbs($class="container-fluid")  {

        $delimiter      = '';
        $blogname       = 'Blog';
        $searchlabel    = 'Search Results';
        $name           = __("Home");
        $currentBefore  = '<li class="active"><span>';
        $currentAfter   = '</span></li>';
        $type           = get_post_type();
        $before         = "<li>";
        $after          = "</li>";

        if (!is_front_page() && get_post_type() == $type || is_paged()) {

            $blog_parent = '';
            if($blog = get_page( get_option( 'page_for_posts' ) )) {
                $blogname = $blog->post_title;
                $blog_parent = $before.'<a href="' . get_permalink($blog) . '">'.$blogname.'</a> ' . $delimiter . ''.$after ;                
            }

            echo '<ul class="'.$class.'">';
            global $post;
            $home = get_bloginfo('url');
            echo $before.'<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ''.$after ;

            if ( is_home() ) {
                echo $currentBefore.$blogname.$currentAfter;
            } else  if (is_category()) {
                global $wp_query;
                $cat_obj = $wp_query->get_queried_object();
                $thisCat = $cat_obj->term_id;
                $thisCat = get_category($thisCat);
                $parentCat = get_category($thisCat->parent);
                echo $blog_parent;
                if ($thisCat->parent != 0) {
                    echo $before.(get_category_parents($parentCat, true, '' . $delimiter . '')).$after;
                }
                echo $currentBefore . single_cat_title("", false) . $currentAfter;
            }
            else if (is_day()) {
                echo $blog_parent;
                echo $before.'<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ''.$after;
                echo $before.'<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' '.$after;
                echo $currentBefore . get_the_time('d') . $currentAfter;
            } else if (is_month()) {
                echo $blog_parent;
                echo $before.'<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ''.$after;
                echo $currentBefore . get_the_time('F') . $currentAfter;
            } else if (is_year()) {
                echo $blog_parent;
                echo $currentBefore . get_the_time('Y') . $currentAfter;
            } else if (is_attachment()) {
                echo $blog_parent;
                echo $currentBefore;
                the_title();
                echo $currentAfter;
            } else if (is_single() && get_post_type() != 'post'){

                #history display
                $HTTP_REFERER   = wp_get_referer(); 
                $referral       = parse_url($HTTP_REFERER);
                $referring_page_title = '';
                $referring_page_url   = '';

                $is_search = false;
                #check if valid referral
                if($_SERVER['HTTP_HOST'] == $referral['host']){
                    #check if category
                    if(strripos($HTTP_REFERER, '/town/') !== false) {
                        $cat_url = substr($HTTP_REFERER, strripos($HTTP_REFERER, '/town/') + 1);
                        $cat_url = explode('/', $cat_url);
                        $term_slug = $cat_url[1];
                        $term = get_term_by( 'slug', $term_slug, 'group' );
                        if($term) {
                            $referring_page_title = $term->name;
                            $referring_page_url   = wp_get_referer();
                        }                     
                    }

                    if(isset($referral['query'])) {
                        $query = $referral['query'];
                        parse_str($query, $get);
                        //search page
                        if(isset($get['s']) || count($get) > 1) {
                            $is_search = true;
                            $referring_page_title = $searchlabel;
                            $referring_page_url   = wp_get_referer();
                        }
                    } 

                    
                    
                }

                $post_type = get_post_type_object(get_post_type());
                $slug      = $post_type->rewrite;            

                echo $before;
                if($referring_page_title) {
                    echo '<a href="'.$referring_page_url.'">'.$referring_page_title.'</a>';
                } else {
                    echo '<a href="' .  home_url() . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ';
                }
                echo $after;

                echo $currentBefore;
                the_title();
                echo $currentAfter;
            } else if (is_single() && get_post_type() == $type ){
                $cat = get_the_category();
                $cat = isset($cat[0]) ? $cat[0] : '';
                echo $blog_parent;
                if ($cat) {
                    echo $before.get_category_parents($cat, true, ' ' . $delimiter . '');
                }
                    echo $currentBefore;
                    the_title();
                    echo $currentAfter;        
            } else if (is_page() && !$post->post_parent) {
                echo $currentBefore;
                the_title();
                echo $currentAfter;
            } else if (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = $before.'<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>'.$after;
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                foreach($breadcrumbs as $crumb)
                echo $crumb . ' ' . $delimiter . ' '.$after;
                echo $currentBefore;
                the_title();
                echo $currentAfter;
            } else if (is_search()) {
                echo $currentBefore . __($searchlabel,'sac') . ' '  . $currentAfter;
            } else if (is_tag()) {
                echo $blog_parent;
                echo $currentBefore . single_tag_title() . $currentAfter;
            } else if (is_author()) {
                global $author;
                echo $blog_parent;
                $userdata = get_userdata($author);
                echo $currentBefore . $userdata->display_name . $currentAfter;
            } else if (is_404()) {
                echo $currentBefore . '404 Not Found' . $currentAfter;
            } else if(is_archive()) { 
                    
                    /*if(is_tax( 'business' )) {
                        echo $currentBefore .'<a href="' . get_permalink() . '">' .get_the_title( ).'</a>'. $currentAfter;
                        echo $currentBefore .single_cat_title('', false). $currentAfter;
                    } else {
                        echo $currentBefore .get_the_title( ). $currentAfter;
                    }*/

                    echo $currentBefore .get_queried_object()->name. $currentAfter;
            } else {
                echo $currentBefore;
                the_title();
                echo $currentAfter;            
            }
            /*if (get_query_var('paged')) {
                if (is_home() || is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                    echo  $currentBefore . ' ';
                }
                echo __('Page','sac') . ' ' . get_query_var('paged');
                if (is_home() || is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                    echo $currentAfter;
                }
            }*/
            echo '</ul>';
        }

    } //sac_breadcrumbs

    // Limits the number of characters
    function sac_trunctext($content , $count=250, $beforecontent ="", $aftercontent="", $readlink ="", $linkclass="", $beforelink ="", $afterlink=""){

        // Sanitizes the the text so only pure texts will be displayed
        $sanitized = preg_replace('/<img[^>]+./','', $content);
        $sanitized = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $sanitized );
        $sanitized = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $sanitized );

        // Remove all HTML tags within the text
        $untagged = strip_tags($sanitized);

        // Text is now shortened based on the first argument
        $shortened = substr($untagged, 0, $count);

        // Concatenating the read more link
        $new_excerpt = $beforecontent . $shortened . $aftercontent;

        if($linkclass == ''):
            $classed_link = NULL;
        else:
            $classed_link = 'class="' . $linkclass . '"';
        endif;

        if($readlink != ''): 
            $new_excerpt .= $beforelink . ' <a ' . $classed_link . ' href="' . get_permalink() . '">' . $readlink . '</a>' . $afterlink;
        endif;

        return $new_excerpt;

    } //sac_trunctext

    function sac_displayfeat( $featured_post_ID, $image_size = 'full' ){

        $ancestor_array = array_reverse(get_post_ancestors( $featured_post_ID ));
        $page_ancestor = $ancestor_array[0]; 

        if ( has_post_thumbnail( $featured_post_ID ) ) :
            $featured_image  = wp_get_attachment_image_src( get_post_thumbnail_id( $featured_post_ID ), $image_size );
            $display_image = '<img src="' . $featured_image[0] . '" alt="' . esc_attr(get_the_title()) . '" width="' . $featured_image[1] . '" height="' . $featured_image[2] . '" />';

        elseif ( has_post_thumbnail( $post->post_parent ) ) :
            $featured_image  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->post_parent ), $image_size );
            $display_image = '<img src="' . $featured_image[0] . '" alt="' . esc_attr(get_the_title()) . '" width="' . $featured_image[1] . '" height="' . $featured_image[2] . '" />';

        elseif ( has_post_thumbnail( $page_ancestor ) ) :
            $featured_image  = wp_get_attachment_image_src( get_post_thumbnail_id( $page_ancestor ), $image_size );
            $display_image = '<img src="' . $featured_image[0] . '" alt="' . esc_attr(get_the_title()) . '" width="' . $featured_image[1] . '" height="' . $featured_image[2] . '" />';

        else :
            $default_banner = get_field('image','option');
            $display_image = '<img src="'.$default_banner['url'].'" alt="">';
        endif;

        return $display_image;

    } //sac_displayfeat

    function sac_pagination() {

        global $wp_query;
        $big = 999999999; // need an unlikely integer

        $pages = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var('paged') ),
            'total'     => $wp_query->max_num_pages,
            'type'      => 'array',
        ) );

        if( is_array( $pages ) ) {
            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
            
            echo '<ul>';
            foreach ( $pages as $page ) {
                echo "<li>$page</li>";
            }
            echo '</ul>';
        }
    } //sac_pagination

    // Numeric Page Navi
    function page_navi($before = '', $after = '') {
        global $wpdb, $wp_query;
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
        if ( $numposts <= $posts_per_page ) { return; }
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
        $pages_to_show = 7;
        $pages_to_show_minus_1 = $pages_to_show-1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
        if($start_page <= 0) {
            $start_page = 1;
        }
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
            
        echo $before.'<div class="pagination"><ul class="clearfix">'."";
        if ($paged > 1) {
            $first_page_text = "Â«";
            echo '<li class="prev"><a href="'.get_pagenum_link().'" title="First">'.$first_page_text.'</a></li>';
        }
            
        $prevposts = get_previous_posts_link('â�� Previous');
        if($prevposts) { echo '<li>' . $prevposts  . '</li>'; }
        else { echo '<li class="disabled"><a href="#">â�� Previous</a></li>'; }
        
        for($i = $start_page; $i  <= $end_page; $i++) {
            if($i == $paged) {
                echo '<li class="active"><a href="#">'.$i.'</a></li>';
            } else {
                echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
            }
        }
        echo '<li class="">';
        next_posts_link('Next â��');
        echo '</li>';
        if ($end_page < $max_page) {
            $last_page_text = "Â»";
            echo '<li class="next"><a href="'.get_pagenum_link($max_page).'" title="Last">'.$last_page_text.'</a></li>';
        }
        echo '</ul></div>'.$after."";
    } //page_navi
    
    function socialsharing($url = ''){
        $url = $url ? $url : get_the_permalink();

        $obj = new shareCount($url);  //Use your website or URL
        
        #add settings
        $tweets = $obj->get_tweets(); //to get tweets
        $feeds  = $obj->get_fb(); //to get facebook total count (likes+shares+comments)
        $linked = $obj->get_linkedin(); //to get linkedin shares
        $plus   = $obj->get_plusones(); //to get google plusones
        #echo "<br>Delicious: ".$obj->get_delicious(); //to get delicious bookmarks  count
        #echo "<br>StumbleUpon: ".$obj->get_stumble(); //to get Stumbleupon views
        #echo "<br>Pinterest: ".$obj->get_pinterest(); //to get pinterest pins
        
        ob_start();            
        ?>  
        <div class="social">
            <span><?php echo $tweets;?> <a class="socicon twitter" onClick="shareit('http://twitter.com/share?url=<?php the_permalink() ?>&amp;text=<?php the_title() ?>');">a</a></span>
            <span><?php echo $linked;?> <a class="socicon linkedin" onClick="shareit('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>');">j</a></span>
            <span><?php echo $feeds;?> <a class="socicon facebook" onClick="shareit('http://www.facebook.com/sharer.php?u=<?php the_permalink() ?>');">b</a></span>
            <span><?php echo $plus;?> <a class="socicon googleplus" onClick="shareit('https://plus.google.com/share?url=<?php the_permalink() ?>');">c</a></span> 
        </div>
        <?php
        return ob_get_clean();  
    } //socialsharing

    function socialsharing_icon($social = array('twitter', 'linkedin', 'facebook', 'googleplus'), $url = ''){
        $url = $url ? $url : get_the_permalink();
                
        $share                  = array();
        $share['twitter']       = '<li><a onClick="shareit(\'http://twitter.com/share?url='.$url.'&amp;text='.get_the_title().'\');"><span class="fa fa-twitter" aria-hidden="true"></span></a>&nbsp;</li>';
        $share['facebook']      = '<li><a onClick="shareit(\'http://www.facebook.com/sharer.php?u='.$url.'\');"><span class="fa fa-facebook" aria-hidden="true"></span></a>&nbsp;</li>';       
        $share['linkedin']      = '<li><a onClick="shareit(\'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$url.'\');"><span class="fa fa-linkedin" aria-hidden="true"></span></a>&nbsp;</li>';       
        $share['googleplus']    = '<li><a onClick="shareit(\'https://plus.google.com/share?url='.$url.'\');"><span class="fa fa-google" aria-hidden="true"></span></a>&nbsp;</li>';       
        
        $html = '';
        foreach ($social as $key => $row) {
            if (isset($share[$row])) {
                $html .= $share[$row];
            }
        }
        $html = '
            <ul class="social-media">
                '.$html.'
            </ul>';
        echo  $html;  
    } //socialsharing_icon

    function before_to_content( $content ) {    
        if( is_singular() ) {         
            $content .= socialsharing();
        }

        return $content;
    }
    #add_filter( 'the_content', 'before_to_content' );

    function post_meta_data() {

        # For posts & pages #
        #echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
        # For comments #
        #echo human_time_diff(get_comment_time('U'), current_time('timestamp')) . ' ago';     
        if ( 'post' === get_post_type() ) {
            $author_avatar_size = apply_filters( 'twentysixteen_author_avatar_size', 24 );        
            echo "Posted by ".get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size )." <a href='".esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )."'>".get_the_author()."</a>";#the_author_posts_link();
            echo  " ".human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; 

            post_meta_tags();
        }
        #echo "on ";the_time('d F Y');
    } //post_meta_data

    function post_meta_tags(){

        $categories = get_the_category();
        $separator = ' ';
        $output = '';
        if ( ! empty( $categories ) ) {
            foreach( $categories as $category ) {
                $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
            }
        }
        
        echo $output ? "<p>Categories: ". trim( $output, $separator ) ."</p>": '';

        $posttags = get_the_tags();
        $html = '';
        if ($posttags) {
          foreach($posttags as $tag) {
            $html .= '<a class="badge" href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a>&nbsp;';
          }
        }

        echo $posttags ? "<p>Tags: $html</p>" : "";        
    }// post_meta_tags

    // Set your Open Graph Meta Tags
    function fbogmeta_header() {
      if (is_single()) {
        ?>
        <!-- Open Graph Meta Tags for Facebook and LinkedIn Sharing !-->
        <meta property="og:title" content="<?php the_title(); ?>"/>
        <meta property="og:description" content="<?php echo strip_tags(get_the_excerpt($post->ID)); ?>" />
        <meta property="og:url" content="<?php the_permalink(); ?>"/>
        <?php $fb_image = wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ), 'thumbnail'); ?>
        <?php if ($fb_image) : ?>
            <meta property="og:image" content="<?php echo $fb_image[0]; ?>" />
            <?php endif; ?>
        <meta property="og:type" content="<?php
            if (is_single() || is_page()) { echo "article"; } else { echo "website";} ?>"
        />
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
        <!-- End Open Graph Meta Tags !-->
     
        <?php
      }
    } //fbogmeta_header    

    function sac_comments( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch( $comment->comment_type ) :
            case 'pingback' :
            case 'trackback' : ?>
                <div <?php comment_class(); ?> id="comment<?php comment_ID(); ?>">
                    <div class="back-link">< ?php comment_author_link(); ?></div>
                </div>
            <?php break;
            default : ?>

                <div class="comment-content lj-author" id="comment-<?php comment_ID(); ?>">
                    <div class="row">
                        <div class="col-md-2 text-left padding0">
                            <div class="box-circle">
                                <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h5>Written by <?php comment_author_link(); ?></h5>
                            <div class="row comment-footer">
                                <div class="col-lg-6 text-left paddingleft0">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;&nbsp;
                                    <time <?php comment_time( 'c' ); ?> class="comment-time">
                                        <span class="date"><?php comment_date(); ?></span>
                                        <span class="time"><?php comment_time(); ?></span>
                                    </time>
                                </div>

                                <div class="col-lg-6 reply text-right">
                                <?php 
                                    comment_reply_link( array_merge( $args, array( 
                                    'reply_text' => 'Reply',
                                    'after' => ' <span></span>', 
                                    'depth' => $depth,
                                    'max_depth' => $args['max_depth'] 
                                    ) ) ); ?>
                                </div><!-- .reply -->
                            </div><!-- .comment-footer -->                       
                            <p><?php echo $comment->comment_content;?></p>
                        </div>   

                    </div>
                

            <?php // End the default styling of comment
            break;
        endswitch;
    } //sac_comments

} // sac_template    

$sac_template = new sac_template();

?>