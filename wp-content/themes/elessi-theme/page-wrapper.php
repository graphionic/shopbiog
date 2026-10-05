<?php
/*
  Template name: Page Wrapper - No Fullwidth
 */

get_header();

/* Hook Display popup window */
do_action('nasa_before_page_wrapper');

echo '<div class="row"><div id="content" class="large-12 columns">';

while (have_posts()) :
    the_post();
    the_content();
endwhile;
wp_reset_postdata();

echo '</div></div>';

do_action('nasa_after_page_wrapper');

get_footer();
