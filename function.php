<?php


// Calculates order bonus amount and adds it to order meta
add_action( 'woocommerce_checkout_create_order', 'npd_add_bonus_order_meta');
function npd_add_bonus_order_meta($order) {

    $order_total = $order->get_total();
    $discount = npd_get_discount_from_price($order_total);
    foreach ($order->get_items() as $item_id => $order_item) {
        $product = $order_item->get_product();
        if ($product->is_on_sale() && npd_get_setting('disable_on_sale')) {
            $price = $order_item->get_total();
            $discount -= npd_get_discount_from_price($price);
        }
    }

    // Subtract shipping cost bonus if setting says so
    if (!npd_get_setting('include_shipping')) {
        $shipping_cost = $order->get_total_shipping();
        $discount -= npd_get_discount_from_price($shipping_cost);
    }
    

    // Security function, so the bonus never goes negative
    if ($discount < 0) {
        $discount = 0;
    }

    $order->update_meta_data( 'npd_order_bonus', $discount );
    

}


// Calculates bonus for order
function npd_get_order_bonus($order, $formatted = false) {
    $bonus = $order->get_meta('npd_order_bonus');
    if ($formatted) {
        $bonus = wc_price($bonus);
    }
    return $bonus;
}

// Returns bonus amount for one product (optionally formatted). If product is on sale and 'disable_on_sale' setting is on, bonus is 0
function npd_get_discount($product, $formatted = false) {
    
    if (npd_get_setting('disable_on_sale') && $product->is_on_sale()) {
        $discount = 0;
    } else {
        $price = $product->get_price();
        $discount = npd_get_discount_from_price($price);
    }
    if ($formatted) {
        $discount = wc_price($discount);
    }
    return $discount;
}

// Calculates bonus based on price
function npd_get_discount_from_price($price) {
    $settings = get_option( 'npd_settings' );
    $discount_percent = $settings['npd_setting_discount'];
    $discount = round($price * $discount_percent / 100);
    return $discount;
}

// Gets current user balance
function npd_get_current_user_balance($formatted = false) {
    $user_id = get_current_user_id();
    $user_balance = npd_get_user_balance($user_id, $formatted);
    return $user_balance;
    
}

// Gets balance by user id
function npd_get_user_balance($user_id, $formatted = false) {
    $user_balance = get_user_meta($user_id, 'npd_balance', true);
    if (!isset($user_balance)) {
        $user_balance = 0;
    }
    if ($formatted) {
        $user_balance = wc_price($user_balance);
    }
    return $user_balance;
}

// Increases user balance after purchase
add_action( 'woocommerce_order_status_completed', 'npd_increase_balance_after_purchase', 15, 1 );
function npd_increase_balance_after_purchase($order_id) {
    $order = wc_get_order( $order_id );
    $user_id = $order->get_user_id();
    if ($user_id) {
        
        $increase = npd_get_order_bonus($order);
        $current_balance = npd_get_user_balance($user_id);
        $balance = $current_balance + $increase;
        update_user_meta($user_id, 'npd_balance', $balance);
    }
    
}

// Subtracts used balance from user
add_action('woocommerce_checkout_order_processed','npd_decrease_balance_after_purchase');
function npd_decrease_balance_after_purchase($order_id) {
    $order = wc_get_order( $order_id );
    $user_id = $order->get_user_id();
    if ($user_id) {
        $decrease = get_user_meta($user_id, 'npd_used_discount', true );
        $current_balance = npd_get_user_balance($user_id);
        $balance = $current_balance - $decrease;
        update_user_meta($user_id, 'npd_balance', $balance);
        update_user_meta($user_id, 'npd_used_discount', 0 );

    }
}

// Subtracts user balance from order total
add_action('woocommerce_cart_calculate_fees','npd_subtract_balance_from_purchase');
function npd_subtract_balance_from_purchase() {
    if (!get_current_user_id()) return;
    $user_id = get_current_user_id();
    global $woocommerce;
    $balance = npd_get_user_balance($user_id);
    $discount = -$balance;
    $subtotal_text = npd_get_setting('subtotal_text');
    $woocommerce->cart->add_fee($subtotal_text, $discount);
    update_user_meta( $user_id, 'npd_used_discount', $balance );
}





// Helper function for getting settings
function npd_get_setting($setting_name) {
    $setting_prefix = 'npd_setting_';
    $setting_name = $setting_prefix . $setting_name;
    $settings = get_option( 'npd_settings' );
    $setting_value = $settings[$setting_name];
    return $setting_value;
}



