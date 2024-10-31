<?php

// Hooks menu and setting fields
add_action( 'admin_menu', 'npd_add_admin_menu' );
add_action( 'admin_init', 'npd_settings_init' );


// Adds admin menu
function npd_add_admin_menu(  ) { 

	add_menu_page( 'Next Purchase Discount', 'Next Purchase Discount', 'edit_posts', 'next_purchase_discount', 'npd_options_page' );

}

// Adds settings fields and render callbacks
function npd_settings_init(  ) { 

	register_setting( 'pluginPage', 'npd_settings' );

	add_settings_section(
		'npd_pluginPage_section', 
		__( '', 'npd' ), 
		'npd_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'npd_setting_discount', 
		__( 'Discount amount', 'npd' ), 
		'npd_setting_discount_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_product_text', 
		__( 'Product text', 'npd' ), 
		'npd_setting_product_text_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_subtotal_text', 
		__( 'Subtotal text', 'npd' ), 
		'npd_setting_subtotal_text_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_balance_text', 
		__( 'Balance text', 'npd' ), 
		'npd_setting_balance_text_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_disable_on_sale', 
		__( 'Do not give discount for products on sale', 'npd' ), 
		'npd_setting_disable_on_sale_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_include_shipping', 
		__( 'Include shipping cost into the bonus', 'npd' ), 
		'npd_setting_include_shipping_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_show_on_single', 
		__( 'Show on single page', 'npd' ), 
		'npd_setting_show_on_single_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	// NOT USED YET
	/*
	add_settings_field( 
		'npd_setting_show_in_loop', 
		__( 'Show in products loop', 'npd' ), 
		'npd_setting_show_in_loop_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);

	add_settings_field( 
		'npd_setting_show_in_cart', 
		__( 'Show in cart', 'npd' ), 
		'npd_setting_show_in_cart_render', 
		'pluginPage', 
		'npd_pluginPage_section' 
	);
	*/


}

// Rendering of setting fields and page
function npd_setting_discount_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='number' name='npd_settings[npd_setting_discount]' value='<?php echo $options['npd_setting_discount']; ?>'>%
	<?php

}


function npd_setting_product_text_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='text' name='npd_settings[npd_setting_product_text]' value='<?php echo $options['npd_setting_product_text']; ?>'>
	<?php

}


function npd_setting_subtotal_text_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='text' name='npd_settings[npd_setting_subtotal_text]' value='<?php echo $options['npd_setting_subtotal_text']; ?>'>
	<?php

}

function npd_setting_balance_text_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='text' name='npd_settings[npd_setting_balance_text]' value='<?php echo $options['npd_setting_balance_text']; ?>'>
	<?php

}

function npd_setting_disable_on_sale_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='checkbox' name='npd_settings[npd_setting_disable_on_sale]' <?php checked( $options['npd_setting_disable_on_sale'], 1 ); ?> value='1'>
	<?php

}


function npd_setting_show_on_single_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='checkbox' name='npd_settings[npd_setting_show_on_single]' <?php checked( $options['npd_setting_show_on_single'], 1 ); ?> value='1'>
	<?php

}

function npd_setting_include_shipping_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='checkbox' name='npd_settings[npd_setting_include_shipping]' <?php checked( $options['npd_setting_include_shipping'], 1 ); ?> value='1'>
	<?php

}


function npd_setting_show_in_loop_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='checkbox' name='npd_settings[npd_setting_show_in_loop]' <?php checked( $options['npd_setting_show_in_loop'], 1 ); ?> value='1'>
	<?php

}


function npd_setting_show_in_cart_render(  ) { 

	$options = get_option( 'npd_settings' );
	?>
	<input type='checkbox' name='npd_settings[npd_setting_show_in_cart]' <?php checked( $options['npd_setting_show_in_cart'], 1 ); ?> value='1'>
	<?php

}


function npd_settings_section_callback(  ) { 

	echo __( '', 'npd' );

}


function npd_options_page(  ) { 

		?>
		<form action='options.php' method='post'>

			<h2>Next Purchase Discount</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

}
