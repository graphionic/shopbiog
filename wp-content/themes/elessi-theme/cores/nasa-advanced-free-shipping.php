<?php
defined('ABSPATH') or die(); // Exit if accessed directly
/**
 * Nasa Advanced Free Shipping
 */
class Nasa_Advanced_Free_Shipping {
    public $render = true;
    
    public $value = 0;
    
    protected $_subtotal_type = 'subtotal';

    protected $conditions = array();

    /**
     * Constructor
     */
    public function __construct($conditions = array()) {
        $this->conditions = $conditions;
        $this->check_condition();
    }
    
    protected function check_condition() {
        /**
         * Check rule is subtotal
         */
        foreach ($this->conditions as $condition) {
            /**
             * Check subtotal
             */
            if (
                in_array($condition['condition'], array('subtotal', 'subtotal_ex_tax')) &&
                $condition['operator'] == '>=' &&
                $condition['value'] && 
                (!$this->value || $this->value > $condition['value'])
            ) {
                $this->_subtotal_type = $condition['condition'];
                $this->value = $condition['value'];
                $this->render = true;
            }

            /**
             * Check country
             */
            if (
                $condition['condition'] == 'country' &&
                $condition['value']
            ) {
                if (!$this->_check_country($condition['value'], $condition['operator'])) {
                    $this->value = 0;
                    $this->render = false;
                    continue;
                }
            }
            
            /**
             * Check State
             */
            if (
                $condition['condition'] == 'state' &&
                $condition['value']
            ) {
                if (!$this->_check_state($condition['value'], $condition['operator'])) {
                    $this->value = 0;
                    $this->render = false;
                    continue;
                }
            }
            
            /**
             * Check City
             */
            if (
                $condition['condition'] == 'city' &&
                $condition['value']
            ) {
                if (!$this->_check_city($condition['value'], $condition['operator'])) {
                    $this->value = 0;
                    $this->render = false;
                    continue;
                }
            }
            
            /**
             * Check Zipcode
             */
            if (
                $condition['condition'] == 'zipcode' &&
                $condition['value']
            ) {
                if (!$this->_check_zipcode($condition['value'], $condition['operator'])) {
                    $this->value = 0;
                    $this->render = false;
                    continue;
                }
            }
            
            /**
             * Check weight
             */
            if (
                $condition['condition'] == 'weight' &&
                $condition['value']
            ) {
                if (!$this->_check_weight($condition['value'], $condition['operator'])) {
                    $this->value = 0;
                    $this->render = false;
                    continue;
                }
            }
        }
        
        if (!$this->value) {
            $this->render = false;
        }
    }
    
    /**
     * Check Customer Contry
     * 
     * @param type $country
     * @param type $operator
     * @return bool
     */
    protected function _check_country($country = '', $operator = '==') {
        $user_country = WC()->customer->get_shipping_country();
        
        $match = false;
        if ('==' == $operator) :
            $match = ($user_country == $country);
        elseif ('!=' == $operator) :
            $match = ($user_country != $country);
        endif;
        
        return $match;
    }
    
    /**
     * Check Customer State
     * 
     * @param type $state
     * @param type $operator
     * @return bool
     */
    protected function _check_state($state = '', $operator = '==') {
        $user_state = WC()->customer->get_shipping_state();
        
        $match = false;
        if ('==' == $operator) :
            $match = ($user_state == $state);
        elseif ('!=' == $operator) :
            $match = ($user_state != $state);
        endif;
        
        return $match;
    }
    
    /**
     * Check Customer City
     * 
     * @param type $city
     * @param type $operator
     * @return bool
     */
    protected function _check_city($city = '', $operator = '==') {
        $user_city = WC()->customer->get_shipping_city();
        
        $match = false;
        if ('==' == $operator) :
            $match = ($user_city == $city);
        elseif ('!=' == $operator) :
            $match = ($user_city != $city);
        endif;
        
        return $match;
    }
    
    /**
     * Check Customer zipcode / postcode
     * 
     * @param type $zipcode
     * @param type $operator
     * @return bool
     */
    protected function _check_zipcode($zipcode = '', $operator = '==') {
        $user_zipcode = WC()->customer->get_shipping_postcode();
        
        $match = false;
        if ('==' == $operator) :
            $match = ($user_zipcode == $zipcode);
        elseif ('!=' == $operator) :
            $match = ($user_zipcode != $zipcode);
        endif;
        
        return $match;
    }
    
    /**
     * Check total weight
     * 
     * @param type $weight
     * @param type $operator
     * @return bool
     */
    protected function _check_weight($weight = '', $operator = '<=') {
        $tt_weight = WC()->cart->get_cart_contents_weight();
        
        $match = false;
        
        if ('==' == $operator) :
            $match = ($tt_weight == $weight);
        elseif ('!=' == $operator) :
            $match = ($tt_weight != $weight);
        elseif ('>=' == $operator) :
            $match = ($tt_weight >= $weight);
        elseif ('<=' == $operator) :
            $match = ($tt_weight <= $weight);
        endif;
        
        return $match;
    }

    /**
     * Output HTML
     * 
     * @return string
     */
    public function output_html() {
        $content = '';
        $style = '';
        $cookieName = 'nasa_curent_per_shipping';

        if(isset($_COOKIE[$cookieName])) {
            $cookieValue = $_COOKIE[$cookieName];
            $style .= ' style="width: ' . $cookieValue. '%;"';
        }
        
        if ($this->value && $this->render) {
            if ($this->_subtotal_type == 'subtotal_ex_tax') {
                $subtotal_cart = method_exists(WC()->cart, 'get_subtotal') ? WC()->cart->get_subtotal() : WC()->cart->subtotal_ex_tax;
            } else {
                $subtotal_cart = WC()->cart->subtotal;
            }
            
            $spend = 0;

            $content_cond = '';
            $content_desc = '';

            /**
             * Check free shipping
             */
            if ($subtotal_cart < $this->value) {
                $spend = $this->value - $subtotal_cart;
                $per = intval(($subtotal_cart/$this->value)*100);
                
                $allowed_html = array(
                    'strong' => array(),
                    'a' => array(
                        'class' => array(),
                        'href' => array(),
                        'title' => array()
                    ),
                    'span' => array(
                        'class' => array()
                    ),
                    'br' => array()
                );

                $content_desc .= '<div class="nasa-total-condition-desc text-center">' .
                sprintf(
                    wp_kses(__('Spend %s more to reach <strong>FREE SHIPPING!</strong> <a class="continue-cart hide-in-cart-sidebar" href="%s" title="Continue Shopping">Continue Shopping</a>', 'elessi-theme'), $allowed_html),
                    wc_price($spend),
                    esc_url(get_permalink(wc_get_page_id('shop')))
                ) . 
                '</div>';
            }
            /**
             * Congratulations! You've got free shipping!
             */
            else {
                $per = 100;
                $content_desc .= '<div class="nasa-total-condition-desc nasa-flex jc text-center">';
                $content_desc .= '<svg class="ns-check-svg text-success margin-right-5 rtl-margin-right-0 rtl-margin-left-5" width="20" height="20" viewBox="0 0 32 32"><path d="M16 2.672c-7.361 0-13.328 5.967-13.328 13.328s5.968 13.328 13.328 13.328c7.361 0 13.328-5.967 13.328-13.328s-5.967-13.328-13.328-13.328zM16 28.262c-6.761 0-12.262-5.501-12.262-12.262s5.5-12.262 12.262-12.262c6.761 0 12.262 5.501 12.262 12.262s-5.5 12.262-12.262 12.262z" fill="currentColor" /><path d="M22.667 11.241l-8.559 8.299-2.998-2.998c-0.312-0.312-0.818-0.312-1.131 0s-0.312 0.818 0 1.131l3.555 3.555c0.156 0.156 0.361 0.234 0.565 0.234 0.2 0 0.401-0.075 0.556-0.225l9.124-8.848c0.317-0.308 0.325-0.814 0.018-1.131-0.309-0.318-0.814-0.325-1.131-0.018z" fill="currentColor" /></svg>';
                $content_desc .= esc_html__("Congratulations! You've got free shipping.", 'elessi-theme');
                $content_desc .= '</div>';
            }

            $class_cond = 'nasa-total-condition-wrap';

            $content_cond .= '<div class="nasa-total-condition" data-per="' . $per . '">' .
                '<div class="nasa-subtotal-condition primary-bg nasa-relative"' . $style. '>' .
                    '<span class="nasa-total-number primary-border text-center nasa-flex jc">' . $per . '%</span>' .
                '</div>' .
            '</div>';

            $content .= '<div class="' . $class_cond . '">';
            $content .= $content_cond;
            $content .= '</div>';
            $content .= $content_desc;
        }
        
        return $content;
    }

}
