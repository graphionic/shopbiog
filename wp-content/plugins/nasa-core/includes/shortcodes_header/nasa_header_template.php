<?php

defined('ABSPATH') || exit;
if (empty($meta_value)) return;

$has_top_left        = !empty($meta_value['ns-header-topbar-left']);
$has_top_middle      = !empty($meta_value['ns-header-topbar-middle']);
$has_top_right       = !empty($meta_value['ns-header-topbar-right']);

$has_main_left       = !empty($meta_value['ns-header-main-left']);
$has_main_middle     = !empty($meta_value['ns-header-main-middle']);
$has_main_right      = !empty($meta_value['ns-header-main-right']);

$has_bottom_left    = !empty($meta_value['ns-header-bottom-left']);
$has_bottom_middle  = !empty($meta_value['ns-header-bottom-middle']);
$has_bottom_right   = !empty($meta_value['ns-header-bottom-right']);

do_action('nasa_header_custom_before', $meta_value);
?>

<div class="header-wrapper main-home-fix nasa-header-sticky nasa-flex flex-nowrap flex-column nasa-header-wrap-event-search">

   <?php if ($has_top_left || $has_top_middle || $has_top_right): ?>
      <div class="ns-row-wrap">
         <div class="row">
            <div class="nasa-flex columns ns-header-topbar ns-header-custom-wrap <?php echo esc_attr($this->nasa_header_custom_class($meta_value['ns-header-topbar']['settings'], '', 'wrap')); ?>">

               <?php if ($has_top_left):
                  echo '<div  class="ns-header-topbar-left nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-topbar-left']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-topbar-left']['items']);
                  echo '</div>';
               endif;

               if ($has_top_middle):
                  echo '<div class="ns-header-topbar-middle nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-topbar-middle']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-topbar-middle']['items']);
                  echo '</div>';
               endif;

               if ($has_top_right):
                  echo '<div class="ns-header-topbar-right nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-topbar-right']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-topbar-right']['items']);
                  echo '</div>';
               endif; ?>

            </div>
         </div>
      </div>
   <?php endif; ?>

   <div class="nasa-cat-filter-place hidden-tag"></div>

   <?php if ($has_main_left || $has_main_middle || $has_main_right): ?>
      <div class="ns-row-wrap">
         <div class="row">
            <div class="nasa-flex columns sticky-wrapper ns-header-main ns-header-custom-wrap <?php echo esc_attr($this->nasa_header_custom_class($meta_value['ns-header-main']['settings'], '', 'wrap')); ?>">

               <?php if ($has_main_left):
                  echo '<div class="ns-header-main-left nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-main-left']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-main-left']['items']);
                  echo '</div>';
               endif;

               if ($has_main_middle):
                  echo '<div class="ns-header-main-middle nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-main-middle']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-main-middle']['items']);
                  echo '</div>';
               endif;

               if ($has_main_right):
                  echo '<div class="ns-header-main-right nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-main-right']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-main-right']['items']);
                  echo '</div>';
               endif; ?>

            </div>
         </div>
      </div>
   <?php endif; ?>


   <?php if ($has_bottom_left || $has_bottom_middle || $has_bottom_right): ?>
      <div class="ns-row-wrap">
         <div class="row">
            <div class="nasa-flex columns ns-header-bottom ns-header-custom-wrap <?php echo esc_attr($this->nasa_header_custom_class($meta_value['ns-header-bottom']['settings'], '', 'wrap')); ?>">

               <?php if ($has_bottom_left):
                  echo '<div class="ns-header-bottom-left nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-bottom-left']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-bottom-left']['items']);
                  echo '</div>';
               endif;

               if ($has_bottom_middle):
                  echo '<div class="ns-header-bottom-middle nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-bottom-middle']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-bottom-middle']['items']);
                  echo '</div>';
               endif;

               if ($has_bottom_right):
                  echo '<div class="ns-header-bottom-right nasa-flex flex-wrap ns-header-custom-section ' . esc_attr($this->nasa_header_custom_class($meta_value['ns-header-bottom-right']['settings'], '', 'section')) . '">';
                  $this->render_header_section($meta_value['ns-header-bottom-right']['items']);
                  echo '</div>';
               endif;  ?>

            </div>   
         </div>
      </div>
   <?php endif; ?>


   <?php if ($this->has_cat_fillter) {
      $html_fillter = '<script type="text/template" id="nasa-cat-filter-template">';
      $html_fillter .= '<div class="nasa-cat-filter-wrap ">';
      $html_fillter .= '<div class="nasa-top-cat-filter-wrap">';
      $html_fillter .= elessi_get_all_categories(false, true);
      $html_fillter .= '<a href="javascript:void(0);" title="' . esc_attr('Close', 'nasa-core') . '" class="nasa-close-filter-cat nasa-stclose nasa-transition" rel="nofollow"></a>';
      $html_fillter .= '</div>';
      $html_fillter .= '</div>';
      $html_fillter .= '</script>';

      echo $html_fillter;
   }

   do_action('nasa_header_custom_after', $meta_value);
   ?>
</div>