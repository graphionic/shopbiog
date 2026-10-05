<?php

/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $post, $nasa_opt;

$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);
$enable_readmore = isset($nasa_opt['enable_read_more_short_description']) && $nasa_opt['enable_read_more_short_description'];
$height = isset($nasa_opt['height_rm_short_desc']) && (int) $nasa_opt['height_rm_short_desc'] ? (int) $nasa_opt['height_rm_short_desc'] : 50;

if (! $short_description) {
	return;
}

?>
<div class="woocommerce-product-details__short-description">

	<?php if ($enable_readmore) : ?>
		<div class="ns-read-more-short-description-wrap" data-height="<?php esc_attr_e($height); ?>">
			<div class="ns-short-description-wrap">
				<?php echo $short_description; ?>
			</div>
		</div>
		<a href="javascript:void(0);" class="ns-btn-read-more-sdesc" data-show-more="<?php esc_html_e('Read more', 'elesi-theme')?>" data-show-less="<?php esc_html_e('Show less', 'elesi-theme')?>" rel="nofollow">
			<span class="ns-btn-read-more-text nasa-flex">
				<?php esc_html_e('Read more', 'elesi-theme') ?>
			</span>
			<svg width="30" height="30" viewBox="0 0 32 32" fill="currentColor">
				<path d="M15.233 19.175l0.754 0.754 6.035-6.035-0.754-0.754-5.281 5.281-5.256-5.256-0.754 0.754 3.013 3.013z"></path>
			</svg>
		</a>
	<?php
	else:
		echo $short_description;
	endif;
	?>
</div>

<?php
