<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
/**
 * @var String $type // edd or woo
 */
$visitModel = isset($_GET['data_model']) ? sanitize_text_field($_GET['data_model']) : PYS()->getOption('visit_data_model');
$activeFilter = isset($_GET['active_filter']) ? sanitize_key($_GET['active_filter']) : "traffic_source";
$filterId = isset($_GET['filter_id']) ? absint($_GET['filter_id']) : -1;
$time = isset($_GET['time']) ? sanitize_text_field($_GET['time']) : "30";
$title = isset($_GET['title']) ? sanitize_text_field($_GET['title']) : "";
$timeStart = isset($_GET['time_start']) ? sanitize_text_field($_GET['time_start']) : "";
$timeEnd = isset($_GET['time_end']) ? sanitize_text_field($_GET['time_end']) : "";
$perPage = isset($_GET['per_page']) ? absint($_GET['per_page']) : "25";

$filters = [
    "traffic_source" => "Traffic source",
    "traffic_landing" => "Landing page",
    "utm_source" => "utm_source",
    "utm_medium" => "utm_medium",
    "utm_campaign" => "utm_campaign",
    "utm_content" => "utm_content",
    "utm_term" => "utm_term",
];
$statistic = $type == "woo" ? PysStatistic()->wooStatistic : PysStatistic()->eddStatistic;
$status = $statistic->getSyncStatus();
?>
<div class="cards-wrapper cards-wrapper-style2 gap-24 statistic-wrapper">
    <!-- WooCommerce Reports -->
    <div class="card card-style6 card-static">
        <div class="card-header card-header-style2 d-flex justify-content-between align-items-center">
            <h4 class="secondary_heading_type2">
                <?php
                    if($type == "woo") {
                        esc_html_e( 'WooCommerce Reports (beta)', 'pys' );
                    } else {
                        esc_html_e( 'EDD Reports (beta)', 'pys' );
                    }
                ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="gap-24">

            <input type="hidden" id="html_report_wpnonce" value="<?=esc_attr(wp_create_nonce("html_report_wpnonce"))?>">
            <?php
            if($status == OrderStatistics::$SYNC_STATUS_START) :
                include 'html-report-loading.php';
            else: ?>
                <div class="card-filter">
                        <ul class="pys_stats_filters">
                            <?php
                                $active = ["data_model","time","time_start","time_end","per_page"];
                                $params="";

                                foreach ($_GET as $key => $val) {
                                    if(in_array($key,$active)) {
                                        $params .= "&" . urlencode(sanitize_key($key)) . "=" . urlencode(sanitize_text_field($val));
                                    }
                                }
                            ?>
                            <?php foreach ($filters as $filter => $name) : ?>
                                <li class="filter <?=esc_attr($activeFilter == $filter ? 'active': '') ?>" data-type="<?=esc_attr($filter)?>">
                                    <a href="<?=esc_url(admin_url("admin.php?page=pixelyoursite_".$type."_reports&active_filter=".urlencode($filter).$params))?>"><?=esc_html($name)?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                </div>

                <?php
                if($_GET['page'] == 'pixelyoursite_woo_reports'){
                    ?>
                    <div class="COG_custom_report_button_block">
                        <div class="button-block">
                            <button type="button" class="btn button_default button <?php if(!isset($_COOKIE['stat_cog']) || $_COOKIE['stat_cog']=='default'){echo ' btn-primary';}?>" data-value="default">Default</button>

                            <button type="button" class="btn button_cog button <?php if(isset($_COOKIE['stat_cog']) && $_COOKIE['stat_cog']=='cog'){echo ' btn-primary';}?>" data-value="cog">Cost and Profit</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php

                if($filterId > 0) {
                    include 'html-report-data-single.php';
                } else {
                    include 'html-report-data-global.php';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="flex-width-50 settings-block">
        <?php
                include 'html-report-settings.php';

                include 'html-report-delete.php';
            endif;
        ?>
    </div>
</div>