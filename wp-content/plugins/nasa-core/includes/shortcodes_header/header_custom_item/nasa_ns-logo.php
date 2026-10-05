<?php
defined('ABSPATH') || exit;

$img_data   = json_decode($data['site_bg_image'], true);
$site_title = esc_attr(get_bloginfo('name', 'display'));
$url        = !empty($data['site_logo_url']) ? esc_url($data['site_logo_url']) : home_url('/');

$image_src = wp_get_attachment_image_src($img_data['id'], 'full');

$img_attrs = [
    'src="' . esc_url($image_src[0]) . '"',
    'alt="' . $site_title . '"',
];

?>

<a class="nasa_ns-logo <?php esc_attr_e($ex_class)?>"
   href="<?php echo esc_url($url); ?>"
   title="<?php echo ($site_title . ' - ' . esc_attr(get_bloginfo('description', 'display'))); ?>"
   rel="<?php echo esc_attr__('Home', 'nasa-core'); ?>">
   <img <?php echo implode(' ', $img_attrs); ?> />
</a>
