<?php

// Initializes display actions 
npd_add_actions();
function npd_add_actions(){
    $settings = get_option( 'npd_settings' );
    if (isset($settings['npd_setting_show_on_single'])) {
        add_action('woocommerce_before_add_to_cart_form', 'npd_display_on_single');
    }

    // NOT USED
    if (isset($settings['npd_setting_show_in_loop'])) {
        add_action('woocommerce_before_add_to_cart_button', 'npd_display_in_loop');
    }
    if (isset($settings['npd_setting_show_in_cart'])) {
        add_action('woocommerce_cart_totals_before_shipping', 'npd_display_in_cart');
    }

    // TODO: CHECKOUT
}

// Sets up balance shortcode
add_shortcode('npd_balance', 'npd_balance_shortcode');
function npd_balance_shortcode() {
    $value = npd_get_current_user_balance(true);
    $text = npd_get_setting('balance_text');
    $shortcode_text = sprintf($text, $value);
    echo $shortcode_text;
}

// Displays bonus amount on single pages
function npd_display_on_single() {
    $text = npd_get_setting('product_text');
    

    global $post;
    $product = wc_get_product($post->ID);
    $discount_price = npd_get_discount($product, true);

    $user_balance = npd_get_current_user_balance(true);
    

    $discount_text = sprintf($text, $discount_price);
    echo '<div class="npd-single-discount-text">' . $discount_text . '</div>';

}





