<?php get_header(); ?>
<div class="container mx-auto">
<?php
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content-post', get_post_format() );
		endwhile;
	endif;
?>
</div>
<?php get_footer(); ?>