<?php
/*
  Template name: Full Width (100%)
 */

get_header();

/* Hook Display popup window */
do_action('nasa_before_page_wrapper');

if (has_excerpt()) : ?>
    <div class="page-header">
        <?php the_excerpt(); ?>
    </div>
<?php
endif;

while (have_posts()) :
    the_post();
    the_content();
endwhile;
wp_reset_postdata();

do_action('nasa_after_page_wrapper');

get_footer();
