<?php
if (is_object($term) && $term) {
    $primary_color = get_term_meta($term->term_id, $this->_cat_primary_color, true);
    if (!isset($primary_color)) {
        $primary_color = add_term_meta($term->term_id, $this->_cat_primary_color, '', true);
    }

    $btn_text_color = get_term_meta($term->term_id, $this->_cat_btn_text_color, true);
    if (!isset($btn_text_color)) {
        $btn_text_color = add_term_meta($term->term_id, $this->_cat_btn_text_color, '', true);
    }

    ?>
    <tr class="form-field nasa-term-root nasa-term-primary_color hidden-tag ns-advance-field">
        <th scope="row" valign="top">
            <label for="<?php echo $this->_cat_primary_color; ?>"><?php _e('Primary Color', 'nasa-core'); ?></label>
        </th>
        <td>
            <div class="nasa_p_color">
                <input type="text" class="widefat nasa-color-field" id="<?php echo $this->_cat_primary_color; ?>" name="<?php echo $this->_cat_primary_color; ?>" value="<?php echo isset($primary_color) ? esc_attr($primary_color) : ''; ?>" />
            </div>
       </td>
    </tr>

    <tr class="form-field nasa-term-root nasa-term-btn_text_color hidden-tag ns-advance-field">
        <th scope="row" valign="top">
            <label for="<?php echo $this->_cat_btn_text_color; ?>"><?php _e('Button Text Color', 'nasa-core'); ?></label>
        </th>
        <td>
            <div class="nasa_p_color">
                <input type="text" class="widefat nasa-color-field" id="<?php echo $this->_cat_btn_text_color; ?>" name="<?php echo $this->_cat_btn_text_color; ?>" value="<?php echo isset($btn_text_color) ? esc_attr($btn_text_color) : ''; ?>" />
            </div>
       </td>
    </tr>
    
<?php } else { ?>
    <div class="form-field nasa-term-root nasa-term-primary_color hidden-tag ns-advance-field">
        <label for="<?php echo $this->_cat_primary_color; ?>"><?php _e('Primary Color', 'nasa-core'); ?></label>
        <div class="nasa_p_color">
            <input type="text" class="widefat nasa-color-field" id="<?php echo $this->_cat_primary_color; ?>" name="<?php echo $this->_cat_primary_color; ?>" value="" />
        </div>
    </div>
    <div class="form-field nasa-term-root nasa-term-btn_text_color hidden-tag ns-advance-field">
        <label for="<?php echo $this->_cat_btn_text_color; ?>"><?php _e('Button Color', 'nasa-core'); ?></label>
        <div class="nasa_p_color">
            <input type="text" class="widefat nasa-color-field" id="<?php echo $this->_cat_btn_text_color; ?>" name="<?php echo $this->_cat_btn_text_color; ?>" value="" />
        </div>
    </div>
<?php
}
