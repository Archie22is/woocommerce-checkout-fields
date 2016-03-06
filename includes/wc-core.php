<?php
class WC_Core{

	public $billing_core_fields = [
		'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1',
		'billing_address_2', 'billing_city', 'billing_postcode', 'billing_country',
		'billing_state', 'billing_email', 'billing_phone',
	];

	public $shipping_core_fields = [
		'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2',
		'shipping_city', 'shipping_postcode', 'shipping_country', 'shipping_state',
	];

	public function __construct(){
		#hook to new fields in checkout
		add_filter( 'woocommerce_checkout_fields' , array($this, 'hookFields' ), 11 );
		# Hook to save extra fields
		#add_action( 'woocommerce_checkout_update_order_meta', array($this, 'saveHookFields' ) );
		# Hook in admin order page
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( &$this, 'showHookFields' ), 10, 1 );
		# Register submenu
		add_action( 'admin_menu', array( &$this, 'addAdminMenu' ) , 100);
		# Send extra data to email
		add_action('woocommerce_email_order_meta', array( &$this, 'emailSendData' ));
		# Show fields in customer view
		add_action('woocommerce_wcf_customer_order_view', array( &$this, 'showHookFields' ));
		add_action('wcdn_after_info', array( &$this, 'showHookFieldsToPrint' ));

		
		# Include scripts only if is own plugin page
		if(isset($_GET['page']) && $_GET['page'] == 'custom_checkout_render_page' || is_checkout()){
			add_action( 'admin_enqueue_scripts', array(&$this, 'addScripts'));
		}
		add_action( 'wp_enqueue_scripts', array(&$this, 'addScripts'));
	}

	function emailSendData( $order, $sent_to_admin, $plain_text ) {

		# billing fields
		$getBillingFields = get_option('billing_wcf_fields');
	    $send1 = 0;
	    if($getBillingFields):
		    $result = '<h4>Dados da Cobrança</h4>';
		    foreach ($getBillingFields['fields'] as $field) {
				if($field['billing_email'] == 1){
					$send1 = 1;
					$result .= $field['billing_label']. ': ' . get_post_meta( $order->id, '_'.$field['billing_key'], true ).'<br>';
				}
			}
			if($send1 == 1){
				echo $result;
			}
		endif;

		# shipping fields
		$getShippingFields = get_option('shipping_wcf_fields');
		$send2 = 0;
		if($getShippingFields):
			$result = '<h4>Dados da Entrega</h4>';
			foreach ($getShippingFields['fields'] as $field) {
				if($field['shipping_email'] == 1){
					$send2 = 1;
					$result .= $field['shipping_label']. ': ' . get_post_meta( $order->id, '_'.$field['shipping_key'], true ).'<br>';
				}
			}
			if($send2 == 1){
				echo $result;
			}
		endif;
		
	}

	/**
	 * Show page to edit fields in admin page
	 * 
	 * @return void
	 */
	public function custom_checkout_render_page(){

		$this->resetOptions();

		$this->countries = new WC_Countries();

	   	$billingfields = $this->countries->get_address_fields( $this->countries->get_base_country(),'billing_');
		$shippingfields = $this->countries->get_address_fields( $this->countries->get_base_country(),'shipping_');
		# This plugin managed fields
	   	$getBillingFields = get_option('billing_wcf_fields');
	   	$getShippingFields = get_option('shipping_wcf_fields');
	   	# Core fields and plugin fields
		$shipping_core_fields = $this->shipping_core_fields;
		$billing_core_fields = $this->billing_core_fields;

		require 'views/index.php';
	}

	public function resetOptions(){
		#  Reset billing options
		if ( 
		    ! isset( $_POST['request_wcf_billing_reset_nonce_field'] ) 
		    || ! wp_verify_nonce( $_POST['request_wcf_billing_reset_nonce_field'], 'request_wcf_billing_reset_action' )
		) {/*do nothing*/} else {
			delete_option('billing_wcf_fields');

		}
		# Reset shipping options
		if ( 
		    ! isset( $_POST['request_wcf_shipping_reset_nonce_field'] ) 
		    || ! wp_verify_nonce( $_POST['request_wcf_shipping_reset_nonce_field'], 'request_wcf_shipping_reset_action' )
		) {/*do nothing*/} else {
			delete_option('shipping_wcf_fields');

		}
	}

	/**
	 * Save extra fields in postmeta table
	 *
	 * @param $order_id
	 * @return void
	 */
	public function saveHookFields($order_id){
		// Implement if neededs
	}

	/**
	 * Show extra fields in admin order page
	 *
	 * @param $order
	 * @return void
	 */
	public function showHookFields($order){

		$getBillingFields = get_option('billing_wcf_fields');
		$getShippingFields = get_option('shipping_wcf_fields');

		print '<h4>Informação Extra(Cobrança)</h4>';
		foreach ($getBillingFields['fields'] as $field) {
			if($field['billing_native'] == 0){
				if($field['billing_admin'] == 1)
					echo $field['billing_label']. ': ' . get_post_meta( $order->id, '_'.$field['billing_key'], true ).'<br>';
			}
		}
		print '<h4>Informação Extra(Entrega)</h4>';
		foreach ($getShippingFields['fields'] as $field) {
			if($field['shipping_native'] == 0){
				if($field['shipping_admin'] == 1)
					echo $field['shipping_label']. ': ' . get_post_meta( $order->id, '_'.$field['shipping_key'], true ).'<br>';
			}
		}
	}

	public function showHookFieldsToPrint($order){
		$getBillingFields = get_option('billing_wcf_fields');
		$getShippingFields = get_option('shipping_wcf_fields');

		$list = '<br><h2>Informação Extra</h2><ul class="info-list">';
		foreach ($getBillingFields['fields'] as $field) {
			if($field['billing_native'] == 0){
				if($field['billing_admin'] == 1)
					$list .= '<li>'. $field['billing_label']. ': ' . get_post_meta( $order->id, '_'.$field['billing_key'], true ).'</li>';
			}
		}
		foreach ($getShippingFields['fields'] as $field) {
			if($field['shipping_native'] == 0){
				if($field['shipping_admin'] == 1)
					$list .= '<li><strong>'. $field['shipping_label']. '</strong> ';
					$list .= '<span>'. get_post_meta( $order->id, '_'.$field['shipping_key'], true ).'</span></li>';
			}
		}

		echo $list;
	}

	/**
	 * Show in checkout page all fields and extra fields
	 * 
	 * @param $fields
	 * @return mixed $fields
	 */
	public function hookFields($fields){
		$getBillingFields = get_option('billing_wcf_fields');
		$getShippingFields = get_option('shipping_wcf_fields');
		
		
		if($getBillingFields){
			unset($fields['billing']);
			foreach ($getBillingFields['fields'] as $customField) {
				#echo '<pre>', print_r($customField);

				$strtolowerBillingType = strtolower($customField['billing_type']);
				if($strtolowerBillingType == 'datepicker'){
					$type = 'text';
				}
				else if( !empty($strtolowerBillingType)) {
					$type = $strtolowerBillingType;
				}
				else {
					$type = 'text';
				}

				$class1 = explode(' ', $customField['billing_class']);
				if($strtolowerBillingType == 'datepicker'){
					if(is_array($class1)){
						$class = $class1;
						$class[] = 'datepicker';
					} else {
						$class = 'datepicker';
					}
				} 
				else{
					$class = (is_array($class1)) ? $class1 : $customField['billing_class'];
				}

				$fields['billing'][$customField['billing_key']] = array(
					'type'		=> $type,
			        'label'     => $customField['billing_label'],
				    'placeholder'=> $customField['billing_placeholder'],
				    'required'  => ($customField['billing_required'] == 1) ? true : false,
				    'class'     => $class,
				    'clear'     => ($customField['billing_clear'] == 1 ) ? true : false,
				    'options' =>  @$customField['billing_options']
				);
			}
		}

		if($getShippingFields){
			unset($fields['shipping']);
			foreach ($getShippingFields['fields'] as $customField) {

				$strtolowerShippingType = strtolower($customField['shipping_type']);
				if($strtolowerShippingType == 'datepicker'){
					$type = 'text';
				}
				else if( !empty($strtolowerShippingType)) {
					$type = $strtolowerShippingType;
				}
				else {
					$type = 'text';
				}

				$class1 = explode(' ', $customField['shipping_class']);
				if($strtolowerShippingType == 'datepicker'){
					if(is_array($class1)){
						$class = $class1;
						$class[] = 'datepicker';
					} else {
						$class = 'datepicker';
					}
				} 
				else{
					$class = (is_array($class1)) ? $class1 : $customField['shipping_class'];
				}

				$fields['shipping'][$customField['shipping_key']] = array(
					'type'		=> $type,
			        'label'     => $customField['shipping_label'],
				    'placeholder'=> $customField['shipping_placeholder'],
				    'required'  => ($customField['shipping_required'] == 1) ? true : false,
				    'class'     => $class,
				    'clear'     => ($customField['shipping_clear'] == 1 ) ? true : false,
				    'options' =>  $customField['shipping_options']
				);
			}
		}
		return $fields;
	}

	/**
	 * Adds Admin Menu
	 *
	 * @return void
	 */
	public function addAdminMenu() {
	    add_submenu_page(
	    	'woocommerce', 
	    	'Checkout Fields', 
	    	'Checkout Fields', 
	    	'manage_woocommerce', 
	    	'custom_checkout_render_page', 
	    	array($this, 'custom_checkout_render_page'));
	}

	/**
	 *  Adds needed scripts
	 *
	 * @return void
	 */
	public function addScripts(){
		$ASSETS = PLUGIN_URL .'includes/assets/';

		wp_enqueue_script('jquery', 	 		$ASSETS .'js/jquery.1.12.0.js');
		wp_enqueue_style( 'jquery-ui-main-css', $ASSETS .'jquery-ui-1.11.4/jquery-ui.min.css' );
		wp_enqueue_style( 'jquery-ui-theme-css',$ASSETS .'jquery-ui-1.11.4/jquery-ui.theme.css' );
		wp_enqueue_script( 'jquery-ui-main-js', $ASSETS .'jquery-ui-1.11.4/jquery-ui.js' );
		wp_enqueue_script( 'jquery-ui-datepicker-js', $ASSETS .'jquery-ui-1.11.4/datepicker.js' );

		wp_enqueue_script('wcf_main', 	$ASSETS .'js/main.js' );
	}

	public function updateFields($prefix, $key, $type, $label, $class, $placeholder, 
		$clear, $required, $options, $admin, $email, $native){
		#echo '<pre>', print_r($options);
		$meta = get_option($prefix.'wcf_fields');
		if($meta){
			$positional = $meta['positional'];
		} else {
			$positional = 1000;
		}
		$count = count($type);
		$x = 0;

		$fields = array();
		while ($x < $count) {
			$search = array_search($key[$x], $this->billing_core_fields);
			#Core Field = 1
			if(strlen($search) == 0 && empty($key[$x])){
				$fields[$x]['core_field'] = 0;
				$positional++;
			} else{
				$fields[$x]['core_field'] = 1;
			}

			if(empty($key[$x])){
				$fields[$x][$prefix.'key'] = $prefix.'field_'. $positional;
			}
			else{
				$fields[$x][$prefix.'key'] = $key[$x];
			}

			$fields[$x][$prefix.'type'] = $type[$x];
			$fields[$x][$prefix.'label'] = ucfirst($label[$x]);
			$fields[$x][$prefix.'class'] = ($class[$x] == '') ? 'form-row-wide' : $class[$x];
			$fields[$x][$prefix.'placeholder'] = ucfirst($placeholder[$x]);
			$fields[$x][$prefix.'clear'] = $clear[$x];
			$fields[$x][$prefix.'required'] = $required[$x];
			$fields[$x][$prefix.'admin'] = $admin[$x];
			$fields[$x][$prefix.'email'] = $email[$x];
			
			if(isset($native[$x])){
				$fields[$x][$prefix.'native'] = $native[$x];
			}
			else {
				$fields[$x][$prefix.'native'] = 0;
			}
			
			$optionsValues = array(); $optionsInputs = array();
			if($type[$x] == 'select' || $type[$x] == 'radio'){
				// loop for options
				$optionsValues = explode(';', $options[$x]);
				foreach ($optionsValues as $value) {
					$vals = explode('=', $value);
					if($vals[0] != ''){
						$optionsInputs[$vals[0]] = $vals[1];						
					}
				}
				$fields[$x][$prefix.'options'] = $optionsInputs;
			}
			$x++;
		}
		$data['positional'] = $positional; 
		$data['fields'] = $fields;
		$config = serialize($data);
		if($meta){
			update_option($prefix.'wcf_fields', $data);
		} else {
			add_option($prefix.'wcf_fields', $data);
		}
	}
}

# Initialize class
new WC_Core();