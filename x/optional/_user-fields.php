<?php 

function my_custom_userfields( $contactmethods ) {
 
    // ADD CONTACT CUSTOM FIELDS
    $contactmethods['contact_phone_office']     = 'Office Phone';
    $contactmethods['contact_phone_mobile']     = 'Mobile Phone';
    $contactmethods['contact_office_fax']       = 'Office Fax';
     
    return $contactmethods;
}

add_filter('user_contactmethods','my_custom_userfields',10,1);

/*
<php the_author_meta('contact_phone_office'); ?>
2 Method:

1
<php the_author_meta('contact_phone_office', $current_author->ID) ?>
3 Method:

<php $current_author = get_userdata(get_query_var('author')); ?>
<p><a href="<?php echo esc_url($current_author->contact_phone_office);?>" title="office_phone"> Office Phone</a></p>
*/
?>