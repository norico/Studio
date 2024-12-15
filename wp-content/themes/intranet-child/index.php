<?php get_header(); ?>

	<?php

	if( is_main_site() && is_multisite() ) {
		echo 'Posts Multisites';
	}
	else {
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content-post', get_post_format() );
			endwhile;
		endif;
	}
	?>

<?php get_footer(); ?>