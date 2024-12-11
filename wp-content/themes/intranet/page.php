<?php get_header(); ?>
	<div id="page" class="container mx-auto">
        <?php

        echo '<h1 class="titre">'.get_the_title().'</h1>';
        the_content();
        echo '</div>';

        ?>
	</div>
<?php get_footer(); ?>