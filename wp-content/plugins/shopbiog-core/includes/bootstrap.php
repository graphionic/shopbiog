<?php
/**
 * Main Plugin Bootstrap Class
 *
 * @package ShopBiOG\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Bootstrap {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Bootstrap|null
     */
    private static $instance = null;

    /**
     * Module loader instance.
     *
     * @var ShopBiOG_Module_Loader|null
     */
    public $module_loader = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Bootstrap
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
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load core dependency files.
     */
    private function load_dependencies() {
        require_once SHOPBIOG_CORE_PATH . 'includes/helpers.php';
        require_once SHOPBIOG_CORE_PATH . 'includes/class-module-loader.php';
    }

    /**
     * Initialize WordPress hooks.
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'), 10);
        add_action('init', array($this, 'on_init'), 10);
    }

    /**
     * Callback for plugins_loaded hook.
     */
    public function on_plugins_loaded() {
        // Instantiate and boot module loader.
        $this->module_loader = ShopBiOG_Module_Loader::get_instance();
        $this->module_loader->init();
    }

    /**
     * Callback for init hook.
     */
    public function on_init() {
        // Reserved for future global init tasks.
    }
}
