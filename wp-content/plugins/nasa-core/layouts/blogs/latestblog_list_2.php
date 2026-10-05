<?php
$_delay = 0;
$_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;

$class_info = 'post-content info-wrap large-9 columns rtl-right';
$class_info_reverse = $info_align == 'right' ? ' text-right rtl-text-left' : ' text-left rtl-text-right';
$class_info .= $class_info_reverse;
$class_reverse = $info_align == 'right' ? 'jdr-r' : '';
$class_reverse_au_date = $info_align == 'right' ? 'je' : '';
$count = 0;
$count2 = 0;

?>
<div class="nasa-sc-blogs-list-2">
    <div class="nasa-flex <?php echo esc_attr($class_reverse); ?>">
        <div class="large-8 nasa-flex medium-12 nasa-blog-sc blog-grid blog-grid-style <?php echo esc_attr($class_reverse); ?>">
            <?php
            $k = 0;
            while ($recentPosts->have_posts()) :
                $count++;
                $recentPosts->the_post();
                $title_item = get_the_title();
                $link = get_the_permalink();
                $postId = get_the_ID();
                $categories = ($cats_enable == 'yes') ? get_the_category_list(esc_html__(', ', 'nasa-core')) : '';

                if ($author_enable == 'yes') :
                    $author = get_the_author();
                    $author_id = get_the_author_meta('ID');
                    $link_author = get_author_posts_url($author_id);
                endif;

                if ($date_enable == 'yes') :
                    $day = get_the_date('d', $postId);
                    $month = get_the_date('m', $postId);
                    $year = get_the_date('Y', $postId);
                    $link_date = get_day_link($year, $month, $day);
                    $date_post = get_the_date('d F', $postId);
                endif;

                $class_item = 'small-12 medium-6 large-6 wow fadeInUp nasa-item-blog-grid blog-item nasa-item-blog-grid-3 ';

                $class_item .= $k == 0 ? ' rtl-right' : ' rtl-left';

                echo '<div class="' . $class_item . '" data-wow-duration="1s" data-wow-delay="' . esc_attr($_delay) . 'ms">';

            ?>

                <a class="entry-blog" href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_item); ?>">
                    <div class="blog-image-fullwidth blog-image-attachment">
                        <?php
                        if (has_post_thumbnail()):
                            the_post_thumbnail('nasa-medium', array(
                                'alt' => esc_attr($title_item)
                            ));
                        else:
                            echo '<img src="' . NASA_CORE_PLUGIN_URL . 'assets/images/placeholder.png" alt="' . esc_attr($title_item) . '" />';
                        endif;
                        ?>
                    </div>
                </a>

                <div class="nasa-blog-info nasa-blog-img-top <?php echo esc_attr($class_info_reverse); ?>">
                    <div class="nasa-blog-info-wrap info-wrap">
                        <?php echo ($cats_enable == 'yes') ? '<div class="nasa-post-cats-wrap margin-top-0">' . $categories . '</div>' : ''; ?>
                        <a class="nasa-blog-title nasa-bold-800" href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_item); ?>"><?php echo $title_item; ?></a>
                        <div class="nasa-date-author-wrap nasa-post-date-author-wrap nasa-flex flex-wrap <?php echo esc_attr($class_reverse_au_date); ?>">
                            <?php if ($date_enable == 'yes') : ?>
                                <a href="<?php echo esc_url($link_date); ?>" title="<?php echo esc_html__('Posts at ', 'nasa-core') . esc_attr($date_post); ?>" class="nasa-post-date-author-link nasa-flex">
                                    <span class="nasa-post-date-author nasa-iflex">

                                        <svg class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="20" height="20" viewBox="0 0 25 26" fill="none">
                                            <path d="M7.0625 4.11816H8.29688L8.54688 4.13379L8.57812 4.16504L8.59375 4.32129V4.85254L12.9531 4.86816H16.3594L16.375 4.54004L16.4219 4.14941L16.5 4.11816H17.1406L17.7031 4.13379L17.9062 4.14941L17.9531 4.18066L17.9688 4.24316V4.86816H20.9531L21.0781 4.88379L21.0938 4.91504V21.9775L21.0469 22.0244L21.0156 22.04L5.28125 22.0557H3.9375L3.90625 22.04L3.89062 21.9619L3.875 21.5088V6.52441L3.89062 4.88379L3.90625 4.86816H5.14062L7 4.88379L7.01562 4.22754L7.04688 4.13379L7.0625 4.11816ZM5.46875 6.39941L5.45312 6.97754V7.74316L5.46875 7.97754L5.60938 7.99316L6.3125 8.00879H9L10.7969 7.99316H11.875L16.1406 8.00879H17.5781L18.9688 7.99316L19.4844 7.97754L19.5 7.96191L19.5156 7.32129V6.43066L19.5 6.41504H18.0156L17.9844 6.43066L17.9375 6.91504L17.9219 7.14941L17.7188 7.16504H16.5938L16.3906 7.14941V6.44629L16.375 6.43066L16.0156 6.41504L15.2969 6.39941H13.7656L8.64062 6.43066L8.625 6.44629L8.59375 6.91504V7.14941L8.15625 7.16504L7.17188 7.14941L7.0625 7.13379L7.03125 7.04004L7.01562 6.44629L6.8125 6.43066L5.9375 6.39941H5.46875ZM5.48438 9.55566L5.46875 9.85254L5.45312 13.8682V20.4932H19.3125L19.5156 20.4775V11.9932L19.5 9.55566H5.48438Z" fill="currentColor" />
                                            <path d="M12.5312 11.8838H16.25L16.375 11.8994L16.4062 11.9463V13.415L16.3125 13.4463L14.2969 13.4775L14.0469 13.4932L14.0781 13.5869L14.1875 13.6338L14.7969 13.7432L15.1875 13.8213L15.4844 13.915L15.6719 14.0088L15.8594 14.1807L15.9844 14.3213L16.1406 14.5557L16.2656 14.8057L16.3438 15.0244L16.3906 15.2588L16.4219 15.6182V16.1494L16.375 16.5244L16.3125 16.7588L16.1719 17.0869L16.0625 17.2588L15.9219 17.4463L15.6562 17.7119L15.5 17.8213L15.2812 17.9463L14.9844 18.0557L14.7188 18.1182L14.6094 18.1338H14.2656L13.9531 18.0869L13.6719 17.9932L13.4375 17.8682L13.2656 17.7432L13.1094 17.6025L12.9688 17.4619L12.7812 17.1963L12.6562 16.9463L12.5625 16.7119L12.5 16.4619L12.4844 16.3369L12.4688 15.9775L12.5 15.8213L12.5312 15.7744L13.2344 15.79H13.9219L14.0312 15.8213L14.0469 15.8682L14.0625 16.1807L14.0938 16.3525L14.1875 16.4775L14.2969 16.54H14.5625L14.6875 16.4619L14.7656 16.3525L14.8125 16.1182L14.8281 15.79L14.7969 15.5244L14.75 15.415L14.6719 15.3525L14.4062 15.2744L13.3906 15.0713L12.8281 14.9463L12.5938 14.8994L12.5 14.8369L12.4688 14.7275L12.4531 14.4619V13.4463L12.4844 11.9619L12.5 11.8994L12.5312 11.8838Z" fill="currentColor" />
                                            <path d="M9.34375 11.8838H10.9063L10.9375 11.9307V18.0713L10.9219 18.1025L10.875 18.1182L10.625 18.1338H9.39063L9.35938 18.1025L9.34375 17.915L9.32813 17.3838V16.665L9.34375 14.8369L9.35938 14.0088L9.375 13.8525L9.09375 13.9775L8.84375 14.0869L8.57813 14.1807L8.3125 14.2432L8.25 14.2119L8.23438 14.165V13.4775L8.25 12.8213L8.3125 12.6963L8.45313 12.6182L8.79688 12.4307L8.95313 12.3057L9.0625 12.165L9.15625 11.9932L9.23438 11.915L9.34375 11.8838Z" fill="currentColor" />
                                        </svg>

                                        <?php echo $date_post; ?>
                                    </span>
                                </a>
                            <?php endif; ?>

                            <?php if ($author_enable == 'yes') : ?>
                                <a href="<?php echo esc_url($link_author); ?>" title="<?php echo esc_html__('Posted By ', 'nasa-core') . esc_attr($author); ?>" class="nasa-post-date-author-link nasa-flex">
                                    <span class="nasa-post-date-author nasa-iflex">
                                        <svg class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="18" height="18" viewBox="0 0 32 32" fill="currentColor">
                                            <path d="M16 3.205c-7.067 0-12.795 5.728-12.795 12.795s5.728 12.795 12.795 12.795 12.795-5.728 12.795-12.795c0-7.067-5.728-12.795-12.795-12.795zM16 4.271c6.467 0 11.729 5.261 11.729 11.729 0 2.845-1.019 5.457-2.711 7.49-1.169-0.488-3.93-1.446-5.638-1.951-0.146-0.046-0.169-0.053-0.169-0.66 0-0.501 0.206-1.005 0.407-1.432 0.218-0.464 0.476-1.244 0.569-1.944 0.259-0.301 0.612-0.895 0.839-2.026 0.199-0.997 0.106-1.36-0.026-1.7-0.014-0.036-0.028-0.071-0.039-0.107-0.050-0.234 0.019-1.448 0.189-2.391 0.118-0.647-0.030-2.022-0.921-3.159-0.562-0.719-1.638-1.601-3.603-1.724l-1.078 0.001c-1.932 0.122-3.008 1.004-3.57 1.723-0.89 1.137-1.038 2.513-0.92 3.159 0.172 0.943 0.239 2.157 0.191 2.387-0.010 0.040-0.025 0.075-0.040 0.111-0.131 0.341-0.225 0.703-0.025 1.7 0.226 1.131 0.579 1.725 0.839 2.026 0.092 0.7 0.35 1.48 0.569 1.944 0.159 0.339 0.234 0.801 0.234 1.454 0 0.607-0.023 0.614-0.159 0.657-1.767 0.522-4.579 1.538-5.628 1.997-1.725-2.042-2.768-4.679-2.768-7.555 0-6.467 5.261-11.729 11.729-11.729zM7.811 24.386c1.201-0.49 3.594-1.344 5.167-1.808 0.914-0.288 0.914-1.058 0.914-1.677 0-0.513-0.035-1.269-0.335-1.908-0.206-0.438-0.442-1.189-0.494-1.776-0.011-0.137-0.076-0.265-0.18-0.355-0.151-0.132-0.458-0.616-0.654-1.593-0.155-0.773-0.089-0.942-0.026-1.106 0.027-0.070 0.053-0.139 0.074-0.216 0.128-0.468-0.015-2.005-0.17-2.858-0.068-0.371 0.018-1.424 0.711-2.311 0.622-0.795 1.563-1.238 2.764-1.315l1.011-0.001c1.233 0.078 2.174 0.521 2.797 1.316 0.694 0.887 0.778 1.94 0.71 2.312-0.154 0.852-0.298 2.39-0.17 2.857 0.022 0.078 0.047 0.147 0.074 0.217 0.064 0.163 0.129 0.333-0.025 1.106-0.196 0.977-0.504 1.461-0.655 1.593-0.103 0.091-0.168 0.218-0.18 0.355-0.051 0.588-0.286 1.338-0.492 1.776-0.236 0.502-0.508 1.171-0.508 1.886 0 0.619 0 1.389 0.924 1.68 1.505 0.445 3.91 1.271 5.18 1.77-2.121 2.1-5.035 3.4-8.248 3.4-3.183 0-6.073-1.277-8.188-3.342z" />
                                        </svg>

                                        <?php echo $author; ?>
                                    </span>
                                </a>
                            <?php endif; ?>

                            <?php if ($readmore == 'yes') : ?>
                                <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_html__('Read more', 'nasa-core'); ?>" class="nasa-post-date-author-link nasa-flex nasa-post-read-more">
                                    <span class="nasa-post-date-author nasa-iflex">

                                        <svg class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="20" height="20" viewBox="0 0 25 26" fill="none">
                                            <path d="M14.8281 3.29004L15.125 3.30566L15.25 3.36816L15.5156 3.61816L15.6719 3.75879L18.25 6.33691L18.3438 6.44629L18.4375 6.52441L18.5469 6.64941L18.625 6.71191L18.7031 6.80566L18.8281 6.91504L20.1406 8.22754L20.2813 8.41504L20.3125 8.50879V23.5244L20.2813 23.5713L20.2344 23.5869L15.1719 23.6025L4.79688 23.6182L4.70313 23.5869L4.6875 23.54L4.67188 23.3369L4.65625 22.1025V3.47754L4.67188 3.35254L4.70313 3.32129H6.85938L11.9375 3.30566L14.8281 3.29004ZM7.98438 4.85254L6.26563 4.86816L6.25 5.61816V19.6807L6.23438 21.3994V22.04L6.26563 22.0557L18.4219 22.0713L18.7344 22.0557V21.4307L18.7188 20.5088V9.55566L14.2344 9.54004L14.0938 9.52441L14.0625 9.50879L14.0469 9.46191L14.0313 4.85254H7.98438ZM15.5938 6.00879L15.6094 7.94629L15.625 7.97754H17.4063L17.5469 7.96191L17.5313 7.89941L17.3906 7.74316L15.8594 6.21191L15.6875 6.07129L15.6094 6.00879H15.5938Z" fill="currentColor" />
                                            <path d="M8.57812 17.3525H15.5625L16.3125 17.3682L16.3906 17.3994L16.4062 17.7432V18.6338L16.3906 18.8682L16.3438 18.8994L15.1406 18.915H8.60938L8.57812 18.8994L8.5625 18.79V17.3994L8.57812 17.3525Z" fill="currentColor" />
                                            <path d="M8.57813 14.2432H16.1563L16.3594 14.2588L16.3906 14.29V15.7432L16.3281 15.79L16.2344 15.8057H8.625L8.57813 15.7744L8.5625 15.6025V14.2744L8.57813 14.2432Z" fill="currentColor" />
                                            <path d="M8.59375 11.1025H11.3438L16.1562 11.1182L16.3594 11.1338L16.3906 11.1494L16.4062 11.1963V12.1963L16.3906 12.6025L16.3594 12.6338L16.2969 12.6494L11.7188 12.665H8.59375L8.5625 12.6182V11.1338L8.59375 11.1025Z" fill="currentColor" />
                                        </svg>

                                        <?php echo esc_html__('Read more', 'nasa-core'); ?>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php

                echo '</div>';
                $k++;
                $_delay += $_delay_item;
                if ($count >= 2) break;
            endwhile;

            wp_reset_postdata();
            ?>
        </div>
        <div class="nasa-blog-sc large-4 medium-12 nasa-sc-blogs-list nasa-blog-wrap-all-items padding-top-0">
            <?php while ($recentPosts->have_posts()) :
                $count2++;
                if ($count2 >= 2) :
                    $recentPosts->the_post();
                    $id = get_the_ID();
                    $title = get_the_title();
                    // var_dump($title);
                    $link = get_the_permalink();
                    $postId = get_the_ID();
                    $categories = ($cats_enable == 'yes') ? get_the_category_list(esc_html__(', ', 'nasa-core')) : '';

                    if ($author_enable == 'yes') :
                        $author = get_the_author();
                        $author_id = get_the_author_meta('ID');
                        $link_author = get_author_posts_url($author_id);
                    endif;

                    if ($date_enable == 'yes') :
                        $day = get_the_date('d', $postId);
                        $month = get_the_date('m', $postId);
                        $year = get_the_date('Y', $postId);
                        $link_date = get_day_link($year, $month, $day);
                        $date_post = get_the_date('d F', $postId);
                    endif;
            ?>
                    <div class="blog-item nasa-sc-blogs-row nasa-flex align-start margin-bottom-0 wow fadeInUp <?php echo esc_attr($class_reverse); ?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay); ?>ms">
                        <div class="post-image large-3 small-3 columns rtl-right">
                            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                                <div class="blog-image-attachment">
                                    <?php
                                    if (has_post_thumbnail()):
                                        the_post_thumbnail('medium', array(
                                            'alt' => esc_attr($title)
                                        ));
                                    else:
                                        echo '<img src="' . NASA_CORE_PLUGIN_URL . 'assets/images/placeholder.png" alt="' . esc_attr($title) . '" />';
                                    endif;
                                    ?>
                                </div>
                            </a>
                        </div>

                        <div class="<?php echo esc_attr($class_info); ?>">
                            <?php echo ($cats_enable == 'yes') ? '<div class="nasa-post-cats-wrap">' . $categories . '</div>' : ''; ?>
                            <div class="nasa-post-date-author-wrap nasa-flex flex-wrap <?php echo esc_attr($class_reverse_au_date); ?>">
                                <?php if ($date_enable == 'yes') : ?>
                                    <a href="<?php echo esc_url($link_date); ?>" title="<?php echo esc_html__('Posts at ', 'nasa-core') . esc_attr($date_post); ?>" class="nasa-post-date-author-link nasa-flex">
                                        <span class="nasa-post-date-author nasa-iflex">
                                            <svg class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="20" height="20" viewBox="0 0 25 26" fill="none">
                                                <path d="M7.0625 4.11816H8.29688L8.54688 4.13379L8.57812 4.16504L8.59375 4.32129V4.85254L12.9531 4.86816H16.3594L16.375 4.54004L16.4219 4.14941L16.5 4.11816H17.1406L17.7031 4.13379L17.9062 4.14941L17.9531 4.18066L17.9688 4.24316V4.86816H20.9531L21.0781 4.88379L21.0938 4.91504V21.9775L21.0469 22.0244L21.0156 22.04L5.28125 22.0557H3.9375L3.90625 22.04L3.89062 21.9619L3.875 21.5088V6.52441L3.89062 4.88379L3.90625 4.86816H5.14062L7 4.88379L7.01562 4.22754L7.04688 4.13379L7.0625 4.11816ZM5.46875 6.39941L5.45312 6.97754V7.74316L5.46875 7.97754L5.60938 7.99316L6.3125 8.00879H9L10.7969 7.99316H11.875L16.1406 8.00879H17.5781L18.9688 7.99316L19.4844 7.97754L19.5 7.96191L19.5156 7.32129V6.43066L19.5 6.41504H18.0156L17.9844 6.43066L17.9375 6.91504L17.9219 7.14941L17.7188 7.16504H16.5938L16.3906 7.14941V6.44629L16.375 6.43066L16.0156 6.41504L15.2969 6.39941H13.7656L8.64062 6.43066L8.625 6.44629L8.59375 6.91504V7.14941L8.15625 7.16504L7.17188 7.14941L7.0625 7.13379L7.03125 7.04004L7.01562 6.44629L6.8125 6.43066L5.9375 6.39941H5.46875ZM5.48438 9.55566L5.46875 9.85254L5.45312 13.8682V20.4932H19.3125L19.5156 20.4775V11.9932L19.5 9.55566H5.48438Z" fill="currentColor" />
                                                <path d="M12.5312 11.8838H16.25L16.375 11.8994L16.4062 11.9463V13.415L16.3125 13.4463L14.2969 13.4775L14.0469 13.4932L14.0781 13.5869L14.1875 13.6338L14.7969 13.7432L15.1875 13.8213L15.4844 13.915L15.6719 14.0088L15.8594 14.1807L15.9844 14.3213L16.1406 14.5557L16.2656 14.8057L16.3438 15.0244L16.3906 15.2588L16.4219 15.6182V16.1494L16.375 16.5244L16.3125 16.7588L16.1719 17.0869L16.0625 17.2588L15.9219 17.4463L15.6562 17.7119L15.5 17.8213L15.2812 17.9463L14.9844 18.0557L14.7188 18.1182L14.6094 18.1338H14.2656L13.9531 18.0869L13.6719 17.9932L13.4375 17.8682L13.2656 17.7432L13.1094 17.6025L12.9688 17.4619L12.7812 17.1963L12.6562 16.9463L12.5625 16.7119L12.5 16.4619L12.4844 16.3369L12.4688 15.9775L12.5 15.8213L12.5312 15.7744L13.2344 15.79H13.9219L14.0312 15.8213L14.0469 15.8682L14.0625 16.1807L14.0938 16.3525L14.1875 16.4775L14.2969 16.54H14.5625L14.6875 16.4619L14.7656 16.3525L14.8125 16.1182L14.8281 15.79L14.7969 15.5244L14.75 15.415L14.6719 15.3525L14.4062 15.2744L13.3906 15.0713L12.8281 14.9463L12.5938 14.8994L12.5 14.8369L12.4688 14.7275L12.4531 14.4619V13.4463L12.4844 11.9619L12.5 11.8994L12.5312 11.8838Z" fill="currentColor" />
                                                <path d="M9.34375 11.8838H10.9063L10.9375 11.9307V18.0713L10.9219 18.1025L10.875 18.1182L10.625 18.1338H9.39063L9.35938 18.1025L9.34375 17.915L9.32813 17.3838V16.665L9.34375 14.8369L9.35938 14.0088L9.375 13.8525L9.09375 13.9775L8.84375 14.0869L8.57813 14.1807L8.3125 14.2432L8.25 14.2119L8.23438 14.165V13.4775L8.25 12.8213L8.3125 12.6963L8.45313 12.6182L8.79688 12.4307L8.95313 12.3057L9.0625 12.165L9.15625 11.9932L9.23438 11.915L9.34375 11.8838Z" fill="currentColor" />
                                            </svg>

                                            <?php echo $date_post; ?>
                                        </span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($author_enable == 'yes') : ?>
                                    <a href="<?php echo esc_url($link_author); ?>" title="<?php echo esc_html__('Posted By ', 'nasa-core') . esc_attr($author); ?>" class="nasa-post-date-author-link nasa-flex">
                                        <span class="nasa-post-date-author nasa-iflex">
                                            <svg class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="18" height="18" viewBox="0 0 32 32" fill="currentColor">
                                                <path d="M16 3.205c-7.067 0-12.795 5.728-12.795 12.795s5.728 12.795 12.795 12.795 12.795-5.728 12.795-12.795c0-7.067-5.728-12.795-12.795-12.795zM16 4.271c6.467 0 11.729 5.261 11.729 11.729 0 2.845-1.019 5.457-2.711 7.49-1.169-0.488-3.93-1.446-5.638-1.951-0.146-0.046-0.169-0.053-0.169-0.66 0-0.501 0.206-1.005 0.407-1.432 0.218-0.464 0.476-1.244 0.569-1.944 0.259-0.301 0.612-0.895 0.839-2.026 0.199-0.997 0.106-1.36-0.026-1.7-0.014-0.036-0.028-0.071-0.039-0.107-0.050-0.234 0.019-1.448 0.189-2.391 0.118-0.647-0.030-2.022-0.921-3.159-0.562-0.719-1.638-1.601-3.603-1.724l-1.078 0.001c-1.932 0.122-3.008 1.004-3.57 1.723-0.89 1.137-1.038 2.513-0.92 3.159 0.172 0.943 0.239 2.157 0.191 2.387-0.010 0.040-0.025 0.075-0.040 0.111-0.131 0.341-0.225 0.703-0.025 1.7 0.226 1.131 0.579 1.725 0.839 2.026 0.092 0.7 0.35 1.48 0.569 1.944 0.159 0.339 0.234 0.801 0.234 1.454 0 0.607-0.023 0.614-0.159 0.657-1.767 0.522-4.579 1.538-5.628 1.997-1.725-2.042-2.768-4.679-2.768-7.555 0-6.467 5.261-11.729 11.729-11.729zM7.811 24.386c1.201-0.49 3.594-1.344 5.167-1.808 0.914-0.288 0.914-1.058 0.914-1.677 0-0.513-0.035-1.269-0.335-1.908-0.206-0.438-0.442-1.189-0.494-1.776-0.011-0.137-0.076-0.265-0.18-0.355-0.151-0.132-0.458-0.616-0.654-1.593-0.155-0.773-0.089-0.942-0.026-1.106 0.027-0.070 0.053-0.139 0.074-0.216 0.128-0.468-0.015-2.005-0.17-2.858-0.068-0.371 0.018-1.424 0.711-2.311 0.622-0.795 1.563-1.238 2.764-1.315l1.011-0.001c1.233 0.078 2.174 0.521 2.797 1.316 0.694 0.887 0.778 1.94 0.71 2.312-0.154 0.852-0.298 2.39-0.17 2.857 0.022 0.078 0.047 0.147 0.074 0.217 0.064 0.163 0.129 0.333-0.025 1.106-0.196 0.977-0.504 1.461-0.655 1.593-0.103 0.091-0.168 0.218-0.18 0.355-0.051 0.588-0.286 1.338-0.492 1.776-0.236 0.502-0.508 1.171-0.508 1.886 0 0.619 0 1.389 0.924 1.68 1.505 0.445 3.91 1.271 5.18 1.77-2.121 2.1-5.035 3.4-8.248 3.4-3.183 0-6.073-1.277-8.188-3.342z" />
                                            </svg>

                                            <?php echo $author; ?>
                                        </span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($readmore == 'yes') : ?>
                                    <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_html__('Read more', 'nasa-core'); ?>" class="nasa-post-date-author-link nasa-flex nasa-post-read-more">
                                        <svg class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="20" height="20" viewBox="0 0 25 26" fill="none">
                                            <path d="M14.8281 3.29004L15.125 3.30566L15.25 3.36816L15.5156 3.61816L15.6719 3.75879L18.25 6.33691L18.3438 6.44629L18.4375 6.52441L18.5469 6.64941L18.625 6.71191L18.7031 6.80566L18.8281 6.91504L20.1406 8.22754L20.2813 8.41504L20.3125 8.50879V23.5244L20.2813 23.5713L20.2344 23.5869L15.1719 23.6025L4.79688 23.6182L4.70313 23.5869L4.6875 23.54L4.67188 23.3369L4.65625 22.1025V3.47754L4.67188 3.35254L4.70313 3.32129H6.85938L11.9375 3.30566L14.8281 3.29004ZM7.98438 4.85254L6.26563 4.86816L6.25 5.61816V19.6807L6.23438 21.3994V22.04L6.26563 22.0557L18.4219 22.0713L18.7344 22.0557V21.4307L18.7188 20.5088V9.55566L14.2344 9.54004L14.0938 9.52441L14.0625 9.50879L14.0469 9.46191L14.0313 4.85254H7.98438ZM15.5938 6.00879L15.6094 7.94629L15.625 7.97754H17.4063L17.5469 7.96191L17.5313 7.89941L17.3906 7.74316L15.8594 6.21191L15.6875 6.07129L15.6094 6.00879H15.5938Z" fill="currentColor" />
                                            <path d="M8.57812 17.3525H15.5625L16.3125 17.3682L16.3906 17.3994L16.4062 17.7432V18.6338L16.3906 18.8682L16.3438 18.8994L15.1406 18.915H8.60938L8.57812 18.8994L8.5625 18.79V17.3994L8.57812 17.3525Z" fill="currentColor" />
                                            <path d="M8.57813 14.2432H16.1563L16.3594 14.2588L16.3906 14.29V15.7432L16.3281 15.79L16.2344 15.8057H8.625L8.57813 15.7744L8.5625 15.6025V14.2744L8.57813 14.2432Z" fill="currentColor" />
                                            <path d="M8.59375 11.1025H11.3438L16.1562 11.1182L16.3594 11.1338L16.3906 11.1494L16.4062 11.1963V12.1963L16.3906 12.6025L16.3594 12.6338L16.2969 12.6494L11.7188 12.665H8.59375L8.5625 12.6182V11.1338L8.59375 11.1025Z" fill="currentColor" />
                                        </svg>

                                        <?php echo esc_html__('Read more', 'nasa-core'); ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <a class="nasa-blog-title nasa-bold-700 margin-top-0" href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                                <?php echo $title; ?>
                            </a>
                        </div>
                    </div>
                    <?php $_delay += $_delay_item; ?>
            <?php
                endif;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>

<?php if ($page_blogs == 'yes') : ?>
    <div class="text-center margin-top-30">
        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" title="<?php echo esc_html__('All Blogs', 'nasa-core'); ?>" class="nasa-view-more button">
            <?php echo esc_html__('All Blogs', 'nasa-core'); ?>
        </a>
    </div>
<?php
endif;
