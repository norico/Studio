<article <?php post_class(); ?> >
	<?php
	the_post_image();
	if ( is_single() ) :
	the_title( '<h1 class="entry-title">', '</h1>' );
	else :
		printf('<p><a href="%s"><h1 class="entry-title">%s</h1></a></p>',  get_the_permalink(), get_the_title());
	endif;

    the_content();

	?>
</article>
