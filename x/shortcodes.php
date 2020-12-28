<?php
/**
* class for html tags 
* class para iwas function exist
*/

class sac_shortcodes {

    var $shortcodes_list = array();

    function __construct($shortcodes_list = array()) {

        $this->shortcodes_list = $shortcodes_list;
        $this->loadshortcodes();    
    }
    
    function loadshortcodes(){

        foreach ($this->shortcodes_list as $key => $value) {            
            add_shortcode( $key,  array($this, $value[0]) );
        }
    }

    function scode_bloginfo( $atts ) {
        extract( shortcode_atts( array(
            'field'         => ''
        ), $atts ) );
        
        if($field) {
            return get_bloginfo($field);
        } else {
            return '';
        }
    } //scode_bloginfo

    function acf_option_shortcode( $atts )
    {
        // extract attributs
        extract( shortcode_atts( array(
            'id'         => '',
            'field'      => ''
        ), $atts ) );
        
        
        // get value and return it
        $value = get_field( $field, $id );
        
        
        return $value;
    } //acf_option_shortcode

    /* Recent Post shortcode */
    function displayrecentposts($atts){
        $attr = shortcode_atts( array(
            'ptype' => 'post',
            'total' => 4
        ), $atts );

        $ptype = $attr['ptype'];
        $total = $attr['total'];

            $recentpost = new WP_Query(array(
                "post_type"     => $ptype,
                "posts_per_page"=> $total
            ));
        $html = '<ul>';
        while($recentpost->have_posts()): $recentpost->the_post();
            $html .= '
                    <li>
                        <a href="'.get_permalink().'">'.get_the_title().'</a>
                    </li>';
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';

        return $html;
    } //displayrecentposts
    
    // Add Shortcode
    function twitter_followers( $atts ) {

        // Attributes
        extract( shortcode_atts(
            array(
                'twitter' => '',
            ), $atts )
        );

        // Code
         $twit = file_get_contents("http://twitter.com/users/show/$twitter.xml");
         $begin = '<followers_count>'; $end = '</followers_count>';
         $page = $twit;
         $parts = explode($begin,$page);
         $page = $parts[1];
         $parts = explode($end,$page);
         $tcount = $parts[0];
          
         if($tcount == '') { $tcount = '0'; }
        echo '<b> '.$tcount.' </b> followers.';
    }

    function get_social_account( $atts ) {
        $atts = shortcode_atts( array(
            'class' => ''
        ), $atts );
        ob_start();
        // check if the repeater field has rows of data
        if( have_rows('social_account', 'option') ):
            $icon = array(
                        'google'    => 'c',
                        'linkedin'  => 'j',
                        'youtube'   => 'x',
                        'facebook'  => 'b',
                        'twitter'   => 'a'
                    );     
            $output = '';       
            // loop through the rows of data
            while ( have_rows('social_account', 'option') ) : the_row();
               // display a sub field value
                $output .= '<li><a href="'.get_sub_field('url').'"><span class="fa fa-'.get_sub_field('account').'" aria-hidden="true"></span></a></li>&nbsp;';
                //$output .=  "<a href='".get_sub_field('url')."' target='_blank'><i class='social fa fa-".get_sub_field('account')."'></i></a>";   
                //$output .=  isset($icon[strtolower(get_sub_field('account'))]) ? "<a href='".get_sub_field('url')."' class='socicon' target='_blank'>".$icon[strtolower(get_sub_field('account'))]."</a>" : "";
            endwhile;
            //<div class="socials">';            
            echo    '<ul class="social-media '.$atts['class'].'">'.
                        $output
                    .'</ul>';
        endif;        
        return ob_get_clean();       
        // do shortcode actions here
    }
    function spacer_20(){
        return '<div class="spacer-20"></div>';
    }

} // sac_shortcodes    

/*
key     = shorcode
value   = function name here, description
*/ 

$shortcodes_list = 
array (
    'scode_bloginfo'            => array ('scode_bloginfo', 'Call bloginfo as shortcode [scode_bloginfo field="name"]'),
    'acf_option_shortcode'      => array ('acf_option_shortcode', 'Use this to use ACF shortcode [acf_option_shortcode field="name" id="id OR option"]'),
    'displayrecentposts'        => array ('displayrecentposts', "Display recent Post by type. [displayrecentposts 'ptype' => 'post','total' => 4]" ),
    'twitter_followers'         => array ('twitter_followers', 'Display Twitter Followers'),
    'spacer_20'                 => array ('spacer_20', 'Display space 20 height'),
    'social_account'            => array ('get_social_account', 'Display Social Account')
);

new sac_shortcodes($shortcodes_list);
?>