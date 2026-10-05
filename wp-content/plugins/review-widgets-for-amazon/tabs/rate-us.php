<?php
defined('ABSPATH') or die('No script kiddies please!');
wp_enqueue_script('trustindex-js', 'https://cdn.trustindex.io/loader.js', [], true, true);
?>
<h1 class="ti-header-title"><?php echo esc_html(__('Rate Us', 'review-widgets-for-amazon')); ?></h1>
<div class="ti-box">
<div class="ti-box-header"><?php echo esc_html(__('Do you like our free plugin?', 'review-widgets-for-amazon')); ?></div>
<p>
<?php echo esc_html(__("We've spent a lot of time developing this software. If you use the free version, you can still support us by leaving a review!", 'review-widgets-for-amazon')); ?><br />
<?php echo esc_html(__('Thank you in advance!', 'review-widgets-for-amazon')); ?>
</p>
<a class="ti-btn" href="https://wordpress.org/support/plugin/<?php echo esc_attr($pluginManagerInstance->get_plugin_slug()); ?>/reviews/?rate=5#new-post" target="_blank"><?php echo esc_html(__('Click here to rate us!', 'review-widgets-for-amazon')); ?></a>
<div class="ti-box-footer">
<div data-src='https://cdn.trustindex.io/loader.js?<?php echo '6409cec8814a389dc75a3d1ece'; ?>'></div>
</div>
</div>
