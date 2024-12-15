<div id="modal" class="fixed hidden inset-0 bg-gray-900/75 z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div id="modal-content" class="absolute top-0 bottom-0 right-0 w-96 bg-white transform translate-x-full transition-transform duration-600 ease-out">
        <!-- Search Section -->
        <div id="search-section" class="flex flex-col w-96 p-2">
            <img class="" src="<?php echo get_template_directory_uri().'/assets/images/default-thumbnail.png';?>" alt="image">
            <p>Search</p>
            <?php echo get_search_form(); ?>
        </div>

        <!-- Menu Section -->
        <div id="menu-section" class="hidden flex-col w-96 p-2">
            <img class="" src="<?php echo get_template_directory_uri().'/assets/images/default-thumbnail.png';?>" alt="image">
            <p>Menu</p>
            <a href="<?php echo get_bloginfo("url").'/actualites';?>">Actualités</a>
        </div>
    </div>
</div>
