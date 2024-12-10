<?php get_header(); ?>

	<div class="container mx-auto">
		<?php get_template_part( 'template-parts/content-post', get_post_format() );?>
	</div>

<?php get_footer(); ?>