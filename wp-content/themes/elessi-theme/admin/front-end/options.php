<?php
global $nasa_opt;
?>
<div class="wrap" id="of_container">

    <div id="of-popup-save" class="of-save-popup">
        <div class="of-save-save nasa-flex jc">
            <svg class="ns-check-svg" width="24" height="24" viewBox="0 0 32 32"><path d="M16 2.672c-7.361 0-13.328 5.967-13.328 13.328s5.968 13.328 13.328 13.328c7.361 0 13.328-5.967 13.328-13.328s-5.967-13.328-13.328-13.328zM16 28.262c-6.761 0-12.262-5.501-12.262-12.262s5.5-12.262 12.262-12.262c6.761 0 12.262 5.501 12.262 12.262s-5.5 12.262-12.262 12.262z" fill="currentColor"></path><path d="M22.667 11.241l-8.559 8.299-2.998-2.998c-0.312-0.312-0.818-0.312-1.131 0s-0.312 0.818 0 1.131l3.555 3.555c0.156 0.156 0.361 0.234 0.565 0.234 0.2 0 0.401-0.075 0.556-0.225l9.124-8.848c0.317-0.308 0.325-0.814 0.018-1.131-0.309-0.318-0.814-0.325-1.131-0.018z" fill="currentColor"></path></svg>
            <?php echo esc_html__("Options Updated", 'elessi-theme'); ?>
        </div>
    </div>

    <div id="of-popup-reset" class="of-save-popup">
        <div class="of-save-reset nasa-flex jc">
            <svg class="ns-check-svg" width="24" height="24" viewBox="0 0 32 32"><path d="M16 2.672c-7.361 0-13.328 5.967-13.328 13.328s5.968 13.328 13.328 13.328c7.361 0 13.328-5.967 13.328-13.328s-5.967-13.328-13.328-13.328zM16 28.262c-6.761 0-12.262-5.501-12.262-12.262s5.5-12.262 12.262-12.262c6.761 0 12.262 5.501 12.262 12.262s-5.5 12.262-12.262 12.262z" fill="currentColor"></path><path d="M22.667 11.241l-8.559 8.299-2.998-2.998c-0.312-0.312-0.818-0.312-1.131 0s-0.312 0.818 0 1.131l3.555 3.555c0.156 0.156 0.361 0.234 0.565 0.234 0.2 0 0.401-0.075 0.556-0.225l9.124-8.848c0.317-0.308 0.325-0.814 0.018-1.131-0.309-0.318-0.814-0.325-1.131-0.018z" fill="currentColor"></path></svg>
            <?php echo esc_html__("Options Reset", 'elessi-theme'); ?>
        </div>
    </div>

    <div id="of-popup-fail" class="of-save-popup">
        <div class="of-save-fail nasa-flex jc">
            <svg fill="currentColor" viewBox="0 0 512 512" height="24" width="24"><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"></path></svg>
            <?php echo esc_html__("Error!", 'elessi-theme'); ?>
        </div>
    </div>

    <span style="display: none;" id="hooks"><?php echo json_encode(of_get_header_classes_array()); ?></span>
    <input type="hidden" id="reset" value="<?php echo isset($_REQUEST['reset']) ? esc_attr($_REQUEST['reset']) : ''; ?>" />
    <input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />

    <form id="of_form" method="post" action="" enctype="multipart/form-data">
        <h2 style="display: none;"><?php esc_html_e('Theme Options', 'elessi-theme'); ?></h2>

        <div class="running-import">
            <div class="bg-success"></div>
            <span class="result-import"><span class="success-import">0</span>%</span>
        </div>

        <div class="mess-reponse-import"></div>

        <div class="updated error importer-notice importer-notice-1" style="display: none;"><p><strong><?php echo sprintf(esc_html__('Seems like an error has occured. Please double check the imported data. If incorrect, please use %s and try again', 'elessi-theme'), '<a href="' . admin_url('plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=830&amp;height=472') . '" class="thickbox" title="' . esc_attr__('Reset WordPress plugin', 'elessi-theme') . '">' . esc_html__('Reset WordPress plugin', 'elessi-theme') . '</a>'); ?> </strong></p></div>

        <div class="updated importer-notice importer-notice-2" style="display: none;"><p><strong><?php echo sprintf(esc_html__('Demo data successfully imported. Please refresh your site and Click Save All Changes NasaTheme options.', 'elessi-theme')); ?></strong></p></div>

        <div class="updated error importer-notice importer-notice-3" style="display: none;"><p><strong><?php esc_html_e('Sorry but your import failed. Most likely, it cannot work with your webhost. You will have to ask your webhost to increase your PHP max_execution_time (or any other webserver timeout to at least 300 secs) and memory_limit (to at least 196M) temporarily.', 'elessi-theme'); ?></strong></p></div>

        <div id="header">
            <div class="logo-options">
                <h2 class="heading-t-options">
                    <?php esc_html_e('Theme Options', 'elessi-theme'); ?>
                    
                    <span class="op-sys-btns<?php echo (isset($nasa_opt['white_lbl']) && $nasa_opt['white_lbl']) ? ' hidden-tag' : ''; ?>">
                        <a href="<?php echo esc_url(ELESSI_ADMIN_DOCS); ?>" target="_blank" class="btn" title="Online Documentation">
                            <svg width="17" height="17" viewBox="0 -3 64 64" fill="currentColor">
<path d="M60,52V4c0-2.211-1.789-4-4-4H14v51v3h42v8H10c-2.209,0-4-1.791-4-4s1.791-4,4-4h2v-3V0H8 C5.789,0,4,1.789,4,4v54c0,3.313,2.687,6,6,6h49c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1v-8C59.104,54,60,53.104,60,52z M23,14h12 c0.553,0,1,0.447,1,1s-0.447,1-1,1H23c-0.553,0-1-0.447-1-1S22.447,14,23,14z M42,28H23c-0.553,0-1-0.447-1-1s0.447-1,1-1h19  c0.553,0,1,0.447,1,1S42.553,28,42,28z M49,22H23c-0.553,0-1-0.447-1-1s0.447-1,1-1h26c0.553,0,1,0.447,1,1S49.553,22,49,22z"/>
</svg> Online Documentation
                        </a>
                        
                        <a href="<?php echo esc_url(ELESSI_ADMIN_INSTALL); ?>" target="_blank" class="btn" title="Theme Installation Service">
                            <svg height="18" width="18" viewBox="0 0 26 26" fill="currentColor"><path d="M22.934,11.19V8.705l-2.955-0.482c-0.176-0.699-0.45-1.357-0.813-1.961l1.723-2.456l-1.756-1.755 l-2.424,1.743c-0.604-0.366-1.267-0.646-1.971-0.82l-0.516-2.941h-2.484L11.26,2.965c-0.706,0.173-1.371,0.448-1.977,0.812 L6.866,2.051L5.11,3.806l1.7,2.431C6.442,6.846,6.163,7.51,5.985,8.22L3.066,8.705v2.484l2.916,0.516 c0.176,0.71,0.456,1.375,0.824,1.985l-1.723,2.409l1.756,1.757l2.434-1.704c0.608,0.365,1.271,0.642,1.977,0.815l0.488,2.934 h2.484l0.521-2.941c0.701-0.178,1.363-0.457,1.967-0.824l2.451,1.721l1.755-1.757l-1.749-2.429 c0.362-0.604,0.637-1.263,0.811-1.964L22.934,11.19z M13,13.431c-1.913,0-3.464-1.55-3.464-3.464c0-1.912,1.551-3.463,3.464-3.463 s3.463,1.551,3.463,3.463C16.464,11.881,14.913,13.431,13,13.431z"/><path d="M24,18.967v4c0,0.551-0.448,1-1,1H3c-0.552,0-1-0.449-1-1v-4H0v4c0,1.656,1.344,3,3,3h20 c1.656,0,3-1.344,3-3v-4H24z"/>
</svg> Theme Installation Service
                        </a>
                        
                        <a href="<?php echo esc_url(ELESSI_ADMIN_CTSV); ?>" target="_blank" class="btn" title="Customization Service">
                            <svg width="22" height="22" viewBox="0 0 20 20" fill="currentColor">
<path d="M18 16V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v12c0 .55.45 1 1 1h13c.55 0 1-.45 1-1zM8 11h1c.55 0 1 .45 1 1s-.45 1-1 1H8v1.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5V13H6c-.55 0-1-.45-1-1s.45-1 1-1h1V5.5c0-.28.22-.5.5-.5s.5.22.5.5V11zm5-2h-1c-.55 0-1-.45-1-1s.45-1 1-1h1V5.5c0-.28.22-.5.5-.5s.5.22.5.5V7h1c.55 0 1 .45 1 1s-.45 1-1 1h-1v5.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5V9z"/>
</svg> Customization Service
                        </a>
                    </span>
                    
                </h2>
            </div>
            
            <div id="js-warning"><?php echo esc_html__("Warning- This options panel will not work properly without javascript!", 'elessi-theme'); ?></div>
            <div class="icon-option"></div>
            <div class="clear"></div>
        </div>

        <div id="info_bar">
            <a href="javascript:void(0);" id="expand_options" class="expand"></a>

            <input type="text" id="search_otp" placeholder="<?php esc_html_e('Search Options', 'elessi-theme'); ?>" />

            <img style="display:none" src="<?php echo ELESSI_ADMIN_DIR_URI; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="<?php echo esc_attr__("Working...", 'elessi-theme'); ?>" />

            <button type="button" class="button-primary nasa-of_save">
                <?php esc_html_e('Save All Changes', 'elessi-theme'); ?>
            </button>

        </div><!--.info_bar--> 	

        <div id="main">

            <div id="of-nav">
                <ul>
                    <?php echo $options_machine->Menu; ?>
                </ul>
            </div>

            <div id="content">
                <?php echo $options_machine->Inputs; /* Settings */ ?>
            </div>

            <div class="clear"></div>

        </div>

        <div class="save_bar">
            <img style="display:none" src="<?php echo ELESSI_ADMIN_DIR_URI; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="<?php esc_attr_e('Working...', 'elessi-theme'); ?>" />
            <button type="button" class="button-primary nasa-of_save"><?php esc_html_e('Save All Changes', 'elessi-theme'); ?></button>			
            <button id ="of_reset" type="button" class="button submit-button reset-button"><?php esc_html_e('Options Reset', 'elessi-theme'); ?></button>
            <img style="display:none" src="<?php echo ELESSI_ADMIN_DIR_URI; ?>assets/images/loading-bottom.gif" class="ajax-reset-loading-img ajax-loading-img-bottom" alt="<?php esc_attr_e('Working...', 'elessi-theme'); ?>" />

        </div><!--.save_bar--> 

    </form>

</div><!--wrap-->
