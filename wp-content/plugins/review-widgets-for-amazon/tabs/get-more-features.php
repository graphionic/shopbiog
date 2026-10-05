<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<h1 class="ti-header-title"><?php echo esc_html(__('Get more Features', 'review-widgets-for-amazon')); ?></h1>
<div class="ti-box">
<div class="ti-box-header"><?php echo esc_html(__('Skyrocket Your Sales with Customer Reviews', 'review-widgets-for-amazon')); ?></div>
<p class="ti-bold">
<?php
/* translators: %s: user number (900.000) */
echo esc_html(sprintf(__('%s+ WordPress websites use Trustindex to embed reviews fast and easily.', 'review-widgets-for-amazon'), '900.000'));
?><br />
<?php echo esc_html(__('Increase SEO, trust and sales using customer reviews.', 'review-widgets-for-amazon')); ?>
</p>
<div class="ti-section-title"><?php echo esc_html(__('Top Features', 'review-widgets-for-amazon')); ?></div>
<ul class="ti-check-list">
<li><?php echo esc_html(__('Display unlimited number of reviews', 'review-widgets-for-amazon')); ?></li>
<li><?php echo esc_html(__('Create unlimited number of widgets', 'review-widgets-for-amazon')); ?></li>
<li><?php echo esc_html(__('Display reviews with photos', 'review-widgets-for-amazon')); ?></li>
<li><?php
/* translators: %d: platform number */
echo esc_html(sprintf(__('%d review platforms', 'review-widgets-for-amazon'), 137));
?></li>
<li><?php echo esc_html(__('Mix reviews from different platforms', 'review-widgets-for-amazon')); ?></li>
<li><?php echo esc_html(__('Get more reviews', 'review-widgets-for-amazon')); ?></li>
<li><?php echo esc_html(__('Manage all reviews in one place', 'review-widgets-for-amazon')); ?></li>
<li><?php echo esc_html(__('Automatically update with NEW reviews', 'review-widgets-for-amazon')); ?></li>
</ul>
<?php echo wp_kses_post($pluginManagerInstance->getProFeatureButton('wp-amazon-3')); ?>
<div class="ti-special-offer">
<img src="<?php echo esc_url($pluginManagerInstance->get_plugin_file_url('static/img/special_30.jpg')); ?>">
<p><?php echo esc_html(str_replace('%%', '%', __('Now we offer you a 30%% discount off for your first subscription! Create your free account and benefit from the onboarding discount now!', 'review-widgets-for-amazon'))); ?></p>
<div class="clear"></div>
</div>
</div>
