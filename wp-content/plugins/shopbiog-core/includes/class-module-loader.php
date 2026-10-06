<?php
/**
 * Module Loader Class for ShopBiOG Core
 *
 * @package ShopBiOG\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Module_Loader {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Module_Loader|null
     */
    private static $instance = null;

    /**
     * Registered modules list.
     *
     * @var array
     */
    private $modules = array(
        'performance',
        'woocommerce',
        'frontend',
        'integrations',
        'admin',
    );

    /**
     * Loaded modules list.
     *
     * @var array
     */
    private $loaded_modules = array();

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Module_Loader
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
    private function __construct() {}

    /**
     * Initialize registered modules.
     */
    public function init() {
        $modules_dir = SHOPBIOG_CORE_PATH . 'modules/';

        foreach ($this->modules as $module) {
            $module_file = $modules_dir . $module . '/module.php';
            if (file_exists($module_file)) {
                require_once $module_file;
                $this->loaded_modules[$module] = true;
            }
        }
    }

    /**
     * Check if a module is active and loaded.
     *
     * @param string $module_name Module name.
     * @return bool
     */
    public function is_active($module_name) {
        return isset($this->loaded_modules[$module_name]) && true === $this->loaded_modules[$module_name];
    }

    /**
     * Get list of loaded modules.
     *
     * @return array
     */
    public function get_loaded_modules() {
        return array_keys($this->loaded_modules);
    }
}
