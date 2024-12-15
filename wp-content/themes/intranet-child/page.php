<?php get_header(); ?>
        <?php
        echo '<h1 class="titre">'.get_the_title().'</h1>';
        the_content();
        echo '</div>';
        ?>
<?php get_footer(); ?>