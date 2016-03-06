<?php
$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'billing_fields';
$active = $current_tab == $tab_key ? 'nav-tab-active' : '';

# Update for billing fields
if ( 
    ! isset( $_POST['request_wcf_billing_nonce_field'] ) 
    || ! wp_verify_nonce( $_POST['request_wcf_billing_nonce_field'], 'request_wcf_billing_action' )
) {/*do nothing*/} else {
	# Save extra fields
	#echo $_POST['billing_options'];
	$this->updateFields('billing_',$_POST['billing_key'], $_POST['billing_type'], $_POST['billing_label'], 
		$_POST['billing_class'], $_POST['billing_placeholder'], $_POST['billing_clear'], $_POST['billing_required'], 
		$_POST['billing_options'], $_POST['billing_admin'], $_POST['billing_email'], $_POST['billing_native']);
	# Reload Fields
	$countries = new WC_Countries();
	$billingfields = $this->countries->get_address_fields( $countries->get_base_country(),'billing_');
	$shippingfields = $this->countries->get_address_fields( $countries->get_base_country(),'shipping_');
	# This plugin managed fields
	$getBillingFields = get_option('billing_wcf_fields');
	$getShippingFields = get_option('shipping_wcf_fields');
	# Core fields and plugin fields
	$shipping_core_fields = $this->shipping_core_fields;
	$billing_core_fields = $this->billing_core_fields;
}

# Update for shipping Fields
if ( 
    ! isset( $_POST['request_wcf_shipping_nonce_field'] ) 
    || ! wp_verify_nonce( $_POST['request_wcf_shipping_nonce_field'], 'request_wcf_shipping_action' )
) {/*do nothing*/} else {
	$this->updateFields('shipping_',$_POST['shipping_key'], $_POST['shipping_type'], $_POST['shipping_label'], 
		$_POST['shipping_class'], $_POST['shipping_placeholder'], $_POST['shipping_clear'], $_POST['shipping_required'], 
		$_POST['shipping_options'], $_POST['shipping_admin'], $_POST['shipping_email'], $_POST['shipping_native']);
	# Reload Fields
	$countries = new WC_Countries();
	$billingfields = $this->countries->get_address_fields( $countries->get_base_country(),'billing_');
	$shippingfields = $this->countries->get_address_fields( $countries->get_base_country(),'shipping_');
	# This plugin managed fields
	$getBillingFields = get_option('billing_wcf_fields');
	$getShippingFields = get_option('shipping_wcf_fields');
	# Core fields and plugin fields
	$shipping_core_fields = $this->shipping_core_fields;
	$billing_core_fields = $this->billing_core_fields;
}
?>

<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php echo ($current_tab == 'billing_fields') ? 'nav-tab-active': ''; ?>" href="?page=custom_checkout_render_page&tab=billing_fields">Campos da Cobrança</a>
		<a class="nav-tab <?php echo ($current_tab == 'shipping_fields') ? 'nav-tab-active' : ''; ?>" href="?page=custom_checkout_render_page&tab=shipping_fields">Campos da Entrega</a>
	</h2>
	<?php 

	switch ($_GET['tab']) {
		case 'billing_fields':
			$prefix = 'billing_';
			include 'billing.php';
			break;

		case 'shipping_fields':
			$prefix = 'shipping_';
			include 'shipping.php';
			break;
		
		default:
			$prefix = 'billing_';
			include 'billing.php';
			break;
	}
	?>
</div>