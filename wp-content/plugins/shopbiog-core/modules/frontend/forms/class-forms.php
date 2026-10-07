<?php
/**
 * Custom Form Handler for ShopBiOG Core
 * Replaces Contact Form 7 plugin dependencies with lightweight native forms.
 *
 * @package ShopBiOG\Core\Modules\Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Forms {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Forms|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Forms
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks and shortcodes.
     */
    private function init_hooks() {
        // Register shortcode handlers for Contact Form 7 replacements.
        add_shortcode('contact-form-7', array($this, 'render_form_shortcode'));
        add_shortcode('nasa_cf7', array($this, 'render_form_shortcode'));
        add_shortcode('shopbiog_contact_form', array($this, 'render_contact_form'));
        add_shortcode('shopbiog_newsletter_form', array($this, 'render_newsletter_form'));
        
        // Handle form AJAX submissions.
        add_action('wp_ajax_shopbiog_submit_form', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_shopbiog_submit_form', array($this, 'handle_form_submission'));
    }

    /**
     * Render form by shortcode ID attribute.
     *
     * @param array $atts Shortcode attributes.
     * @return string Form HTML.
     */
    public function render_form_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'id' => '',
            'title' => '',
        ), $atts);

        $form_id = intval($atts['id']);

        if (210 === $form_id) {
            return $this->render_newsletter_form($atts);
        }

        return $this->render_contact_form($atts);
    }

    /**
     * Render Contact Us Form markup.
     *
     * @param array $atts Attributes.
     * @return string HTML.
     */
    public function render_contact_form($atts = array()) {
        ob_start();
        ?>
        <div class="shopbiog-form-container shopbiog-contact-form-wrap">
            <form class="shopbiog-custom-form shopbiog-contact-form" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post">
                <?php wp_nonce_field('shopbiog_form_nonce', 'shopbiog_form_sec'); ?>
                <input type="hidden" name="action" value="shopbiog_submit_form" />
                <input type="hidden" name="form_type" value="contact" />
                <!-- Honeypot anti-spam -->
                <div style="display:none !important;" aria-hidden="true">
                    <input type="text" name="shopbiog_hp_check" value="" tabindex="-1" autocomplete="off" />
                </div>
                <div class="form-row form-row-name">
                    <label for="sb_your_name"><?php esc_html_e('Your Name', 'shopbiog-core'); ?> <span class="required">*</span></label>
                    <input type="text" id="sb_your_name" name="your_name" required="required" class="input-text" placeholder="<?php esc_attr_e('Your Name', 'shopbiog-core'); ?>" />
                </div>
                <div class="form-row form-row-email">
                    <label for="sb_your_email"><?php esc_html_e('Your Email', 'shopbiog-core'); ?> <span class="required">*</span></label>
                    <input type="email" id="sb_your_email" name="your_email" required="required" class="input-text" placeholder="<?php esc_attr_e('Your Email', 'shopbiog-core'); ?>" />
                </div>
                <div class="form-row form-row-subject">
                    <label for="sb_your_subject"><?php esc_html_e('Subject', 'shopbiog-core'); ?></label>
                    <input type="text" id="sb_your_subject" name="your_subject" class="input-text" placeholder="<?php esc_attr_e('Subject', 'shopbiog-core'); ?>" />
                </div>
                <div class="form-row form-row-message">
                    <label for="sb_your_message"><?php esc_html_e('Your Message', 'shopbiog-core'); ?> <span class="required">*</span></label>
                    <textarea id="sb_your_message" name="your_message" required="required" rows="5" class="input-text" placeholder="<?php esc_attr_e('Your Message', 'shopbiog-core'); ?>"></textarea>
                </div>
                <div class="form-row form-row-submit">
                    <button type="submit" class="button submit-button alt"><?php esc_html_e('Send Message', 'shopbiog-core'); ?></button>
                </div>
                <div class="shopbiog-form-response" style="display:none; margin-top:10px;"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Footer Newsletter Form markup.
     *
     * @param array $atts Attributes.
     * @return string HTML.
     */
    public function render_newsletter_form($atts = array()) {
        ob_start();
        ?>
        <div class="shopbiog-form-container shopbiog-newsletter-form-wrap">
            <form class="shopbiog-custom-form shopbiog-newsletter-form" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post">
                <?php wp_nonce_field('shopbiog_form_nonce', 'shopbiog_form_sec'); ?>
                <input type="hidden" name="action" value="shopbiog_submit_form" />
                <input type="hidden" name="form_type" value="newsletter" />
                <div style="display:none !important;" aria-hidden="true">
                    <input type="text" name="shopbiog_hp_check" value="" tabindex="-1" autocomplete="off" />
                </div>
                <div class="newsletter-input-group">
                    <input type="email" name="your_email" required="required" class="input-text email-input" placeholder="<?php esc_attr_e('Your Email Address...', 'shopbiog-core'); ?>" />
                    <button type="submit" class="button newsletter-submit-btn"><?php esc_html_e('Subscribe', 'shopbiog-core'); ?></button>
                </div>
                <div class="shopbiog-form-response" style="display:none; margin-top:5px;"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * AJAX submission handler.
     */
    public function handle_form_submission() {
        check_ajax_referer('shopbiog_form_nonce', 'shopbiog_form_sec');

        // Honeypot spam check
        if (!empty($_POST['shopbiog_hp_check'])) {
            wp_send_json_success(array('message' => __('Thank you for your submission.', 'shopbiog-core')));
        }

        $email = isset($_POST['your_email']) ? sanitize_email($_POST['your_email']) : '';
        if (!$email || !is_email($email)) {
            wp_send_json_error(array('message' => __('Please provide a valid email address.', 'shopbiog-core')));
        }

        $form_type = isset($_POST['form_type']) ? sanitize_text_field($_POST['form_type']) : 'contact';
        $admin_email = get_option('admin_email');

        if ('newsletter' === $form_type) {
            $subject = sprintf(__('New Newsletter Subscription: %s', 'shopbiog-core'), get_bloginfo('name'));
            $body = sprintf(__("New subscriber email: %s\nDate: %s", 'shopbiog-core'), $email, date('Y-m-d H:i:s'));
            wp_mail($admin_email, $subject, $body);
            wp_send_json_success(array('message' => __('Thank you for subscribing!', 'shopbiog-core')));
        } else {
            $name = isset($_POST['your_name']) ? sanitize_text_field($_POST['your_name']) : '';
            $user_subject = isset($_POST['your_subject']) ? sanitize_text_field($_POST['your_subject']) : '';
            $message = isset($_POST['your_message']) ? sanitize_textarea_field($_POST['your_message']) : '';

            $subject = sprintf(__('Contact Form Submission: %s', 'shopbiog-core'), $user_subject ? $user_subject : get_bloginfo('name'));
            $body = sprintf("Name: %s\nEmail: %s\nSubject: %s\n\nMessage:\n%s", $name, $email, $user_subject, $message);
            $headers = array('Reply-To: ' . $name . ' <' . $email . '>');

            wp_mail($admin_email, $subject, $body, $headers);
            wp_send_json_success(array('message' => __('Thank you! Your message has been sent successfully.', 'shopbiog-core')));
        }
    }
}
