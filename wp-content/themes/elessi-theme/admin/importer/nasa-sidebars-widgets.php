<?php
function elessi_sidebars_widgets_import() {
    $results = array(
        'sidebars_widgets' => array(
            'wp_inactive_widgets' => array(),
            
            'blog-sidebar' => array(
                0 => 'search-2',
                1 => 'recent-posts-2',
                2 => 'categories-2',
                3 => 'archives-2',
                4 => 'meta-2'
            ),
            
            'shop-sidebar' => array(
                0 => 'nasa_product_categories-2',
                1 => 'nasa_woocommerce_filter_variations-2',
                2 => 'nasa_woocommerce_filter_variations-3',
                3 => 'nasa_woocommerce_price_filter-2',
                4 => 'nasa_woocommerce_status_filter-2',
                5 => 'nasa_woocommerce_reset_filter-2',
            ),
            
            'product-sidebar' => array(
                0 => 'nasa_product_categories-3',
                1 => 'nasa_tag_cloud-2'
            )
        ),
        
        'widgets' => array(
            'widget_search' => array(
                2 => array(
                    'title' => 'Search'
                ),
                '_multiwidget' => 1
            ),
            
            'widget_recent-posts' => array(
                2 => array(
                    'title' => 'Recent'
                ),
                '_multiwidget' => 1
            ),
            
            'widget_categories' => array(
                2 => array(
                    'title' => 'Categories',
                    'count' => 0,
                    'hierarchical' => 0,
                    'dropdown' => 0,
                ),
                '_multiwidget' => 1
            ),
            
            'widget_archives' => array(
                2 => array(
                    'title' => 'Archives',
                    'count' => 0,
                    'dropdown' => 0,
                ),
                '_multiwidget' => 1
            ),
            
            'widget_meta' => array(
                2 => array(
                    'title' => 'Meta',
                ),
                '_multiwidget' => 1
            ),
            
            'widget_nasa_product_categories' => array(
                2 => array(
                    'title' => 'Categories',
                    'orderby' => 'order',
                    'count' => 0,
                    'hierarchical' => 1,
                    'show_children_only' => 0,
                    'accordion' => 1,
                    'show_items' => 'All',
                    'toggle' => '',
                ),
                3 => array(
                    'title' => 'Categories',
                    'orderby' => 'order',
                    'count' => 0,
                    'hierarchical' => 1,
                    'show_children_only' => 0,
                    'accordion' => 1,
                    'show_items' => 'All',
                    'toggle' => '',
                ),
                '_multiwidget' => 1
            ),
            
            'widget_nasa_woocommerce_filter_variations' => array(
                2 => array(
                    'title' => 'Color',
                    'attribute' => 'color',
                    'query_type' => 'or',
                    'show_items' => 'All',
                    'effect' => 'slide',
                    'hide_empty' => 0,
                    'count' => 0,
                ),
                3 => array(
                    'title' => 'Size',
                    'attribute' => 'size',
                    'query_type' => 'or',
                    'show_items' => 'All',
                    'effect' => 'slide',
                    'hide_empty' => 0,
                    'count' => 0,
                ),
                '_multiwidget' => 1
            ),
            
            'widget_nasa_woocommerce_price_filter' => array(
                2 => array(
                    'title' => 'Price',
                    'btn_filter' => 0,
                ),
                '_multiwidget' => 1
            ),
            
            'widget_nasa_woocommerce_status_filter' => array(
                2 => array(
                    'title' => 'Status',
                    'filter_onsale' => 1,
                    'filter_featured' => 1,
                    'filter_instock' => 1,
                    'filter_onbackorder' => 1
                ),
                '_multiwidget' => 1
            ),
            
            'widget_nasa_woocommerce_reset_filter' => array(
                2 => array(
                    'title' => 'Clear Filters'
                ),
                '_multiwidget' => 1
            ),
            
            'widget_nasa_tag_cloud' => array(
                2 => array(
                    'title' => 'Tags',
                    'taxonomy' => 'product_tag',
                    'show_items' => 'All',
                ),
                '_multiwidget' => 1
            ),
        )
    );
    
    $results['sidebars_widgets']['array_version'] = 3;
    
    return $results;
}
