<?php
/**
 * Woocommerce Compare page
 *
 * @author  NasaTheme
 * @package Elessi-theme/WooCommerce
 * @version 3.0.0
 *
 * Available variables:
 *
 * @var $table YITH_WooCompare_Table
 * @var $products array
 * @var $fields array
 * @var $default_fields array
 * @var $fixed bool
 * @var $show_product_info bool
 * @var $stock_icons bool
 * @var $layout string
 * @var $image_size string
 * @var $different array
 */
defined('YITH_WOOCOMPARE') || exit; // Exit if accessed directly.

global $product;
?>

<div id="yith-woocompare" class="woocommerce <?php echo $fixed ? esc_attr('fixed-compare-table') : ''; ?>">

    <?php
    // if (empty($products) || !$show_product_info) {
    //     $table->output_table_heading();
    // }
    ?>

    <?php if (empty($products)) : ?>

        <?php
        /**
         * APPLY_FILTERS: yith_woocompare_empty_compare_message
         *
         * Filters the message shown when the comparison table is emtpy.
         *
         * @param string $message Message.
         *
         * @return string
         */
        ?>

        <p class="text-center padding-top-30 nasa-flex jc"><svg class="nasa-empty-icon" viewBox="0 0 512 512" with="215" height="215" fill="currentColor"><path d="M500.9,240.5c-6.1,0-11.1,5-11.1,11.1c0,62.3-24.3,120.9-68.3,164.9c-44.1,44-102.6,68.3-164.9,68.3 c-55.1,0-107.3-19-149.1-53.9l40.4-8c6-1.2,9.9-7,8.7-13c-1.2-6-7.1-9.9-13-8.7l-61.8,12.3c-0.7,0.1-1.3,0.4-2,0.6c0,0-0.1,0-0.1,0 c0,0,0,0,0,0c-0.9,0.4-1.7,0.9-2.5,1.5c-0.2,0.1-0.4,0.3-0.5,0.5c-0.7,0.6-1.3,1.3-1.9,2.1c0,0,0,0.1-0.1,0.1 c-0.5,0.8-0.9,1.6-1.2,2.5c-0.1,0.2-0.1,0.4-0.2,0.7c-0.3,0.9-0.4,1.9-0.4,2.9c0,0,0,0,0,0v71.6c0,6.1,5,11.1,11.1,11.1 S95,502,95,495.8v-46.6c45.5,37.3,102,57.7,161.7,57.7c68.2,0,132.3-26.6,180.5-74.8S512,319.8,512,251.6 C512,245.5,507,240.5,500.9,240.5z"/><path d="M478.2,178.4c1.5,4.7,5.9,7.6,10.5,7.6c1.1,0,2.3-0.2,3.5-0.6c5.8-1.9,9-8.2,7-14c-2.9-8.7-6.2-17.3-10-25.6 c-2.5-5.6-9.1-8-14.6-5.5c-5.6,2.5-8,9.1-5.5,14.6C472.5,162.6,475.6,170.5,478.2,178.4z"/><path d="M22.1,261.4c0-62.3,24.3-120.9,68.3-164.9c44.1-44,102.6-68.3,164.9-68.3c55.1,0,107.3,19,149.1,53.9 l-40.4,8.1c-6,1.2-9.9,7-8.7,13c1,5.3,5.7,8.9,10.8,8.9c0.7,0,1.4-0.1,2.2-0.2l61.8-12.3c0.7-0.1,1.3-0.4,2-0.6c0,0,0.1,0,0.1,0 c0,0,0,0,0,0c0.9-0.4,1.7-0.9,2.5-1.5c0.2-0.1,0.4-0.3,0.5-0.5c0.7-0.6,1.3-1.3,1.9-2.1c0,0,0-0.1,0.1-0.1c0.5-0.8,0.9-1.6,1.2-2.5 c0.1-0.2,0.1-0.4,0.2-0.7c0.3-0.9,0.4-1.9,0.4-2.9c0,0,0,0,0,0V17.2c0-6.1-5-11.1-11.1-11.1c-6.1,0-11.1,5-11.1,11.1v46.6 C371.5,26.4,315,6.1,255.3,6.1C187.1,6.1,123,32.7,74.8,80.9S0,193.2,0,261.4c0,6.1,5,11.1,11.1,11.1S22.1,267.6,22.1,261.4z"/><path d="M31,325.4c-1.7-5.9-7.8-9.3-13.7-7.6c-5.9,1.7-9.3,7.8-7.6,13.7c3.4,12.1,7.8,24.1,13,35.6 c1.9,4.1,5.9,6.5,10.1,6.5c1.5,0,3.1-0.3,4.6-1c5.6-2.5,8-9.1,5.5-14.6C38.2,347.4,34.2,336.5,31,325.4z"/></svg></p>
        <h5 class="text-center margin-bottom-30 empty woocommerce-compare__empty-message">
            <?php echo wp_kses_post(apply_filters('yith_woocompare_empty_compare_message', __('No product added to compare !', 'elessi-theme'))); ?>
        </h5>
        
        <p class="text-center">
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button nasa-sidebar-return-shop" title="<?php echo esc_attr__('RETURN TO SHOP', 'elessi-theme'); ?>">
                <?php echo esc_html__('RETURN TO SHOP', 'elessi-theme'); ?>
            </a>
        </p>;
    <?php else : ?>
        <?php
        /**
         * DO_ACTION: yith_woocompare_before_main_table
         *
         * Allows to render some content before the comparison table.
         *
         * @param array $products Products to show.
         * @param bool  $fixed    Whether are products to show or not.
         */
        do_action('yith_woocompare_before_main_table', $products, $fixed);
        ?>
        <table id="yith-woocompare-table" class="compare-list has-background<?php echo $stock_icons ? ' with-stock-icons' : ''; ?> <?php echo esc_attr($layout); ?>">
            <thead>
                <tr>
                    <th class="fields"></th>
                    <?php echo str_repeat('<td></td>', count($products)); // phpcs:ignore  ?>
                    <td class="filler"></td>
                </tr>
            </thead>
            
            <tfoot>
                <tr>
                    <th class="fields"></th>
                    <?php echo str_repeat('<td></td>', count($products)); // phpcs:ignore  ?>
                    <td class="filler"></td>
                </tr>
            </tfoot>

            <tbody>
                <?php if (!$show_product_info && !$fixed) : ?>
                    <tr class="remove">
                        <th></th>
                        <?php
                        $index = 0;
                        foreach ($products as $product_id => $product) :
                            $product_class = ($index % 2 ? 'even' : 'odd') . ' product_' . $product_id
                            ?>
                            <td class="<?php echo esc_attr($product_class); ?>">
                                <?php $table->output_remove_anchor($product_id); ?>
                            </td>
                            <?php
                            ++$index;
                        endforeach;
                        ?>
                        <td class="filler"></td>
                    </tr>
                <?php endif; ?>

                <?php if ($show_product_info) : ?>
                    <tr class="product_info ns-no-padding">
                        <th>
                            <?php $table->output_table_heading(); ?>
                        </th>

                        <?php
                        $index = 0;
                        foreach ($products as $product_id => $product) :
                            $product_class = ($index % 2 ? 'even' : 'odd') . ' product_' . $product_id;
                            ?>
                            <td class="<?php echo esc_attr($product_class); ?>">
                                <?php
                                empty($fields['image']) && !$fixed && $table->output_remove_anchor($product_id);

                                if (!empty($fields['image']) || empty($fields['title'])) :
                                    if (!empty($fields['image'])) :
                                        ?>
                                        <div class="image-wrap">
                                            <?php if (!$fixed) : ?>
                                                <div class="image-overlay">
                                                    <?php $table->output_remove_anchor($product_id); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php echo $product->get_image($image_size); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?>
                                        </div>
                                        <?php
                                    endif;

                                    if (!empty($fields['title'])) : ?>
                                        <a class="product-anchor" href="<?php echo esc_attr($product->get_permalink()); ?>">
                                            <h4 class="product_title">
                                                <?php echo esc_html($product->get_title()); ?>
                                            </h4>
                                        </a>
                                        <?php
                                    endif;
                                endif;

                                do_action('yith_woocompare_before_product_info_add_to_cart', $product, $fields);

                                if (!empty($fields['add_to_cart'])) :
                                    $table->output_product_add_to_cart();
                                endif;

                                do_action('yith_woocompare_after_product_info_add_to_cart', $product, $fields);
                                ?>
                            </td>
                            <?php
                            ++$index;
                        endforeach; ?>
                        
                        <td class="filler"></td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($fields as $field => $name) : ?>
                    <?php
                    if (apply_filters('yith_woocompare_table_should_skip_field', false, $field)) :
                        continue;
                    endif;
                    
                    $row_classes = array();

                    if (in_array($field, array('title', 'image', 'add_to_cart'), true)) :
                        continue;
                    endif;

                    if (in_array($field, array('price_2', 'add_to_cart_2'), true)) :
                        $field = str_replace('_2', '', $field);
                        $name = $default_fields[$field];
                        $row_classes[] = 'repeated';
                    endif;

                    if (in_array($field, $different, true)) :
                        $row_classes[] = 'different';
                    endif;

                    $row_classes[] = $field;
                    ?>

                    <tr class="<?php echo esc_attr(implode(' ', $row_classes)); ?>">
                        <th><?php echo esc_html($name); ?></th>

                        <?php
                        $index = 0;
                        foreach ($products as $product_id => $product) :
                            // Set td class.
                            $product_class = ($index % 2 ? 'even' : 'odd') . ' product_' . $product_id;

                            if ('stock' === $field) :
                                $availability = $product->get_availability();
                                $product_class .= ' ' . (empty($availability['class']) ? 'in-stock' : $availability['class']);
                            endif;
                            ?>

                            <td class="<?php echo esc_attr($product_class); ?>">
                                <?php
                                switch ($field) :
                                    case 'add_to_cart':
                                        $table->output_product_add_to_cart();
                                        break;
                                    case 'rating':
                                        $rating = function_exists('wc_get_rating_html') ? wc_get_rating_html($product->get_average_rating()) : $product->get_rating_html();
                                        echo $rating ? '<div class="woocommerce-product-rating">' . wp_kses_post($rating) . '</div>' : '-';
                                        break;
                                    default:
                                        /**
                                         * APPLY_FILTERS: yith_woocompare_value_default_field
                                         *
                                         * Filters the default value for the field in the comparison table.
                                         *
                                         * @param string     $value   Field value.
                                         * @param WC_Product $product Product object.
                                         * @param string     $field   Field id to show.
                                         *
                                         * @return string
                                         */
                                        echo wp_kses_post(apply_filters('yith_woocompare_value_default_field', empty($product->fields[$field]) ? '-' : do_shortcode($product->fields[$field]), $product, $field));
                                        break;
                                endswitch;
                                ?>
                            </td>
                            <?php
                            ++$index;
                        endforeach;
                        ?>
                        <td class="filler"></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    <?php
    /**
     * DO_ACTION: yith_woocompare_after_main_table
     *
     * Allows to render some content after the comparison table.
     *
     * @param array $products Products to show.
     * @param bool  $fixed    Whether are products to show or not.
     */
    do_action('yith_woocompare_after_main_table', $products, $fixed);
    ?>

    <?php endif; ?>
</div>

<?php
wp_enqueue_script('yith-woocompare-main');
