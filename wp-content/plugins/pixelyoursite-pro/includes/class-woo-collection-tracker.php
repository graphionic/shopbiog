<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WooCollectionTracker {
    public static $current_context = null;

    /**
     * Determines the list type and ID based on block settings and the current page.
     */
    public static function set_context( $block_attrs ) {
        $has_ga  = function_exists( 'PixelYourSite\GA' );
        $has_gtm = function_exists( 'PixelYourSite\GTM' );

        // Bail out unless GA or GTM has the woo view item list event enabled.
        $ga_enabled  = $has_ga  && GA()->getOption( 'woo_view_item_list_enabled' );
        $gtm_enabled = $has_gtm && GTM()->getOption( 'woo_view_item_list_enabled' );

        if ( ! $ga_enabled && ! $gtm_enabled ) {
            return;
        }

        $track_name = ( $has_ga  && GA()->getOption( 'woo_view_item_list_track_name' ) )
                   || ( $has_gtm && GTM()->getOption( 'woo_view_item_list_track_name' ) );

        // 1. Get information about the current page
        $prod_obj   = get_queried_object();
        $page_title = '';
        $page_slug  = '';

        if ( $prod_obj ) {
            if ( $prod_obj instanceof \WP_Post ) {
                $page_title = $prod_obj->post_title;
                // urldecode is needed when the slug contains cyrillic characters
                $page_slug  = urldecode( $prod_obj->post_name );
            } elseif ( $prod_obj instanceof \WP_Term ) {
                $page_title = $prod_obj->name;
                $page_slug  = urldecode( $prod_obj->slug );
            }
        }

        // Default values
        $base_type = 'General Collection';
        $base_id   = 'general_collection';
        $recipe    = '';
        $query     = isset( $block_attrs['query'] ) && is_array( $block_attrs['query'] ) ? $block_attrs['query'] : [];

        $no_add_page_title = [
            'upsells',
            'cross-sells',
            'category',
            'tag',
            'category_tag',
            'hand-picked',
            'related',
        ];

        // 2. Match against known recipe presets
        if ( ! empty( $block_attrs['collection'] ) ) {
            $recipe = str_replace( 'woocommerce/product-collection/', '', $block_attrs['collection'] );
            $recipes_map = [
                    'best-sellers' => 'Best Sellers',
                    'top-rated'    => 'Top Rated',
                    'new-arrivals' => 'New Arrivals',
                    'on-sale'      => 'On Sale',
                    'featured'     => 'Featured Products',
                    'hand-picked'  => 'Hand-Picked Products',
                    'related'      => 'Related Products',
                    'upsells'      => 'Upsells',
                    'cross-sells'  => 'Cross-sells',
            ];

            if ( isset( $recipes_map[ $recipe ] ) ) {
                $base_type = $recipes_map[ $recipe ];
                $base_id   = $recipe; // Raw preset ID, normalized at the end
            }
        }

        // 3. Inspect manual query settings (when no preset matched)
        if ( $base_type === 'General Collection' && ! empty( $query ) ) {
            // Check taxonomies (Categories / Tags)
            if ( ! empty( $query['taxQuery'] ) && is_array( $query['taxQuery'] ) ) {
                $category_names = [];
                $category_slugs = [];
                $tag_names      = [];
                $tag_slugs      = [];

                foreach ( $query['taxQuery'] as $taxonomy => $term_ids ) {
                    if ( ! is_array( $term_ids ) ) continue;
                    $is_tag = ( $taxonomy === 'product_tag' );

                    foreach ( $term_ids as $id ) {
                        $term = get_term( $id, $taxonomy );
                        if ( $term && ! is_wp_error( $term ) ) {
                            if ( $is_tag ) {
                                $tag_names[] = $term->name;
                                $tag_slugs[] = urldecode( $term->slug );
                            } else {
                                $category_names[] = $term->name;
                                $category_slugs[] = urldecode( $term->slug );
                            }
                        }
                    }
                }

                $has_cat = ! empty( $category_names );
                $has_tag = ! empty( $tag_names );

                if ( $has_cat || $has_tag ) {
                    if ( $has_cat && $has_tag ) {
                        $type_label = 'Category & Tag';
                        $recipe     = 'category_tag';
                        $all_names  = array_merge( $category_names, $tag_names );
                        $all_slugs  = array_merge( $category_slugs, $tag_slugs );
                    } elseif ( $has_cat ) {
                        $type_label = 'Category';
                        $recipe     = 'category';
                        $all_names  = $category_names;
                        $all_slugs  = $category_slugs;
                    } else {
                        $type_label = 'Tag';
                        $recipe     = 'tag';
                        $all_names  = $tag_names;
                        $all_slugs  = $tag_slugs;
                    }

                    if ( $track_name ) {
                        $base_type = $type_label . ' - ' . implode( ', ', $all_names );
                        $base_id   = $type_label . '_' . implode( '_', $all_slugs );
                    } else {
                        $base_type = $type_label;
                        $base_id   = $type_label;
                    }
                }
            }

            // Check Featured
            if ( ! empty( $query['featured'] ) && $base_type === 'General Collection' ) {
                $base_type = 'Featured Products';
                $base_id   = 'featured_products';
                $recipe    = 'featured_products';
            }

            // Check On Sale
            if ( ! empty( $query['woocommerceOnSale'] ) && $base_type === 'General Collection' ) {
                $base_type = 'On Sale Products';
                $base_id   = 'on_sale_products';
                $recipe    = 'on_sale_products';
            }
        }

        // Upsells / Cross-sells: append referenced product details
        if ( ( $base_type === 'Upsells' || $base_type === 'Cross-sells' ) && ! empty( $query['productReference'] ) ) {
            $product_reference = wc_get_product( $query['productReference'] );
            if ( $product_reference && $track_name ) {
                $reference_slug = $product_reference->get_slug() ?? get_post_field( 'post_name', $product_reference->get_id() );
                $base_type = $base_type . ' - ' . $product_reference->get_title();
                $base_id   = $base_id . '_' . $reference_slug;
            }
        }

        // 4. Append current page info
        if ( ! in_array( $recipe, $no_add_page_title, true ) && ! empty( $page_title ) && $track_name ) {
            $listtype   = $base_type . ' - ' . $page_title;
            $listtypeid = $base_id . '_' . $page_slug;
        } else {
            $listtype   = $base_type;
            $listtypeid = $base_id;
        }

        // 5. Final ID normalization
        // Lower-case (with cyrillic/unicode support)
        $listtypeid = mb_strtolower( $listtypeid, 'UTF-8' );
        // Replace spaces and dashes with underscores
        $listtypeid = str_replace( [' ', '-'], '_', $listtypeid );
        // Collapse repeated underscores
        $listtypeid = preg_replace( '/_+/', '_', $listtypeid );
        // Trim underscores from edges
        $listtypeid = trim( $listtypeid, '_' );

        // 6. Save the current context
        self::$current_context = [
                'listtype'   => $listtype,
                'listtypeid' => $listtypeid
        ];
    }

    /**
     * Resets the current context after a collection block has finished rendering,
     * so stale data cannot leak into unrelated downstream blocks.
     */
    public static function clear_context() {
        self::$current_context = null;
    }
}
