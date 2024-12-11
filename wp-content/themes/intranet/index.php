<?php get_header(); ?>
<div id="index" class="container mx-auto">
<?php
    if( is_main_site() && is_multisite() ) {

	    echo 'Posts Multisites';
    }
    else {

	    echo 'Posts single site';

        /*
	    if ( have_posts() ) :
		    while ( have_posts() ) : the_post();
			    get_template_part( 'template-parts/content-post', get_post_format() );
		    endwhile;
	    endif;
        */
    }

?>
</div>
<?php get_footer(); ?>