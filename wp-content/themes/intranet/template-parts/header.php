<div id="header" class="bg-slate-200">
    <div class="container mx-auto">
        <div class="flex space-x-4">
            <?php
            if ( has_custom_logo() ):
                printf('<div id="headerimg"><a href="%s" rel="home">%s</a></div>', esc_url(get_bloginfo('url')), wp_get_attachment_image( get_theme_mod( 'custom_logo' ) ) );
            endif;
            ?>
            <div class="flex flex-col justify-center">
                <h1 class="text-2xl"><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
                <span class="description">
                    <?php bloginfo('description'); ?>
                </span>
            </div>
        </div>
    </div>
</div>