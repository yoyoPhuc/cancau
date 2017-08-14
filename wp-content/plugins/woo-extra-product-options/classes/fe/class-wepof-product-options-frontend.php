<?php

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WEPOF_Product_Options_Frontend')):
class WEPOF_Product_Options_Frontend extends WEPOF_Product_Options_Utils {
	public $options_extra = array();
	
	public function __construct(){
		if( !session_id()) session_start();
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		add_action( 'woocommerce_before_single_product', array($this, 'woo_before_single_product') );	
			
		add_action( 'woocommerce_before_add_to_cart_button', array($this, 'woo_before_add_to_cart_button'), 10, 3 );
		add_action( 'woocommerce_after_add_to_cart_button', array($this, 'woo_after_add_to_cart_button'), 10, 3 );	
			
		add_filter( 'woocommerce_add_to_cart_validation', array($this, 'woo_add_to_cart_validation'), 99, 3 );
		add_filter( 'woocommerce_add_cart_item_data', array($this, 'woo_add_cart_item_data'), 10, 2 );
		add_action( 'woocommerce_add_to_cart', array($this, 'woo_add_to_cart'), 1, 5 );
		add_filter( 'woocommerce_get_item_data', array($this, 'woo_get_item_data'), 10, 2 );
		
		add_action( 'woocommerce_add_order_item_meta', array($this, 'woo_add_order_item_meta'), 1, 3 );
		add_filter( 'woocommerce_order_items_meta_get_formatted', array($this, 'woo_order_items_meta_get_formatted'), 10, 2 );
		add_filter( 'woocommerce_order_item_get_formatted_meta_data', array($this, 'woo_order_item_get_formatted_meta_data'), 10, 2);
	}

	public function enqueue_scripts(){			
		global $wp_scripts;
		if(is_product()){
			wp_enqueue_style('thwepof-frontend-style', TH_WEPOF_ASSETS_URL.'css/thwepof-field-editor-frontend.css', TH_WEPOF_VERSION);
		}
	}
   
   /******************************************************
	**** SAVE AND GET OPTIONS FROM WP SESSION - START ****
	******************************************************/
	private function set_fields_hook_map_in_wp_session_using_product_id($product_id, $fields_hook_map){
		WC()->session->__unset($product_id.'_thwepof_fields_hook_map_pid');
		if(is_array($fields_hook_map) && !empty($fields_hook_map)){
			WC()->session->set($product_id.'_thwepof_fields_hook_map_pid', $fields_hook_map);
		}		
	}
	private function get_fields_hook_map_from_wp_session_by_product_id($product_id){
		$fields_hook_map = false;
		if( WC()->session->__isset( $product_id.'_thwepof_fields_hook_map_pid' ) ){
			$fields_hook_map = WC()->session->get($product_id.'_thwepof_fields_hook_map_pid');	
			$fields_hook_map = ($fields_hook_map && is_array($fields_hook_map)) ? $fields_hook_map : false;
		}
		return $fields_hook_map;
	}
	
	private function set_extra_options_in_wp_session_using_product_id($product_id, $extra_options){
		$SESSION_KEY = $product_id.'_thwepof_extra_options_pid';
		
		unset($_SESSION[$SESSION_KEY]);
		if(is_array($extra_options) && !empty($extra_options)){
			$_SESSION[$SESSION_KEY] = serialize($extra_options);
		}
	
		/*WC()->session->__unset($SESSION_KEY);
		if(is_array($extra_options) && !empty($extra_options)){
			WC()->session->set($SESSION_KEY, $extra_options);
		}*/	
	}
	private function get_extra_options_from_wp_session_by_product_id($product_id){
		$SESSION_KEY = $product_id.'_thwepof_extra_options_pid';
		$extra_options = false;
		
		if(isset($_SESSION[$SESSION_KEY])){
			$extra_options = unserialize($_SESSION[$SESSION_KEY]);	
			$extra_options = ($extra_options && is_array($extra_options)) ? $extra_options : false;
		}
		/*if( WC()->session->__isset( $SESSION_KEY ) ){
			$extra_options = WC()->session->get($SESSION_KEY);	
			$extra_options = ($extra_options && is_array($extra_options)) ? $extra_options : false;
		}*/
		return $extra_options;
	}
	
	private function clear_extra_options_from_wp_session_by_product_id($product_id){
		$SESSION_KEY = $product_id.'_thwepof_extra_options_pid';
		unset($_SESSION[$SESSION_KEY]);
	}
   /******************************************************
	**** SAVE AND GET OPTIONS FROM WP SESSION - END ******
	******************************************************/
	
   /******************************************************
	**** SAVE AND GET OPTIONS FROM WOO SESSION - START ***
	******************************************************/
	private function set_extra_options_in_woo_session_using_product_id($product_id, $extra_options){
		WC()->session->__unset($product_id.'_thwepof_extra_options_pid');
		if(is_array($extra_options) && !empty($extra_options)){
			WC()->session->set($product_id.'_thwepof_extra_options_pid', $extra_options);
		}		
	}
	private function get_extra_options_from_woo_session_by_product_id($product_id){
		$extra_options = false;
		if( WC()->session->__isset( $product_id.'_thwepof_extra_options_pid' ) ){
			$extra_options = WC()->session->get($product_id.'_thwepof_extra_options_pid');	
			$extra_options = ($extra_options && is_array($extra_options)) ? $extra_options : false;
		}
		return $extra_options;
	}
	
	private function set_extra_options_in_woo_session_using_cart_item_key($cart_item_key, $extra_options){
		WC()->session->__unset($cart_item_key.'_thwepof_extra_options_cik');
		if(is_array($extra_options) && !empty($extra_options)){
			WC()->session->set($cart_item_key.'_thwepof_extra_options_cik', $extra_options);
		}
	}
	private function get_extra_options_from_woo_session_by_cart_item_key($cart_item_key){
		$extra_options = false;
		if( WC()->session->__isset( $cart_item_key.'_thwepof_extra_options_cik' ) ){
			$extra_options = WC()->session->get($cart_item_key.'_thwepof_extra_options_cik');
			$extra_options = ($extra_options && is_array($extra_options)) ? $extra_options : false;
		}	
		return $extra_options;
	}
   /******************************************************
	**** SAVE AND GET OPTIONS FROM WOO SESSION - END *****
	******************************************************/

   /***************************************************
	**** PREPARE CUSTOM SECTIONS & OPTIONS - START ****
	***************************************************/
	public function woo_before_single_product(){
		global $product;
		$categories = array();
		$assigned_categories = wp_get_post_terms($product->get_id(), 'product_cat');
		foreach($assigned_categories as $category){
			$parent_categories = get_ancestors( $category->term_id, 'product_cat' ); 
			if(is_array($parent_categories)){
				foreach($parent_categories as $pcat_id){
					$pcat = get_term( $pcat_id, 'product_cat' );
					$categories[] = $pcat->slug;
				}
			}
			
			$categories[] = $category->slug;
		}
					
		$this->options_extra = array();
		$fields_hook_map = $this->get_product_custom_fields_hook_map();	
		
		if($fields_hook_map){
			foreach($fields_hook_map as $hook_name => $fields){
				if($fields){
					foreach($fields as $field_name => $field){
						if( $this->is_valid_field($field) && $field->is_enabled() && $field->show_field($product->get_id(), $categories) ){
							$this->options_extra[$field_name] = $field;
						}else{
							unset($fields[$field_name]);
						}
					}
					$fields_hook_map[$hook_name] = $fields;
				}	
			}
		}
		
		//$this->set_fields_hook_map_in_session_using_product_id($product->get_id(), $fields_hook_map);
		//$this->set_extra_options_in_session_using_product_id($product->get_id(), $this->options_extra);
		$this->set_fields_hook_map_in_wp_session_using_product_id($product->get_id(), $fields_hook_map);
		$this->set_extra_options_in_wp_session_using_product_id($product->get_id(), $this->options_extra);
	}
   	
	private function get_product_options_extra_by_hook_name($hook_name){
		global $product;
		//$fields_hook_map = $this->get_fields_hook_map_from_session_by_product_id($product->get_id());
		$fields_hook_map = $this->get_fields_hook_map_from_wp_session_by_product_id($product->get_id());
		
		if($fields_hook_map && array_key_exists($hook_name, $fields_hook_map)) {
			$hooked_fields = $fields_hook_map[$hook_name];
			return (is_array($hooked_fields) && !empty($hooked_fields)) ? $hooked_fields : false;
		}
		return false;
	}
	
	private function get_product_options_extra(){
		return $this->options_extra;
	}
   /***************************************************
	**** PREPARE CUSTOM SECTIONS & OPTIONS - END ******
	***************************************************/
	
   /***********************************************
	**** DISPLAY CUSTOM PRODUCT FIELDS - START ****
	***********************************************/	
	private function render_fields($hook_name){	
		$fields = $this->get_product_options_extra_by_hook_name($hook_name);
		if($fields){						
			foreach($fields as $name => $field){
				$field->render_field();
			}
		}
	}

	public function woo_before_add_to_cart_button(){
		$this->render_fields('woo_before_add_to_cart_button');
	}

	public function woo_after_add_to_cart_button(){
		$this->render_fields('woo_after_add_to_cart_button');
	}
   /***********************************************
	**** DISPLAY CUSTOM PRODUCT FIELDS - END ******
	***********************************************/
	

   /***************************************************
	**** CUSTOM PRODUCT OPTIONS VALIDATION - START ****
	***************************************************/
	public function woo_add_to_cart_validation($valid, $product_id, $quantity) { 
		$extra_options = $this->get_extra_options_from_wp_session_by_product_id($product_id);
		
		if($extra_options){
			foreach($extra_options as $field_name => $field){
				$value = isset($_POST[$field_name]) && !empty($_POST[$field_name]) ? $_POST[$field_name] : '';
				$value = empty($value) && isset($_REQUEST[$field_name]) ? $_REQUEST[$field_name] : $value;
				$valid = $this->validate_field($valid, $field, $value);
			}
		}
		return $valid;
	}

	private function validate_field($valid, $field, $value){		
		if($field->is_required() && empty($value)) {
			$this->wcpf_add_error($this->__wcpf( 'Please enter a value for '.$field->get_title() ));
			$valid = false;
		}else{
			$validators = $field->get_validator();
			$validators = !empty($validators) ? explode("|", $validators) : false;

			if($validators && !empty($value)){
				foreach($validators as $validator){
					switch($validator) {
						case 'number' :
							if(!is_numeric($value)){
								$this->wcpf_add_error('<strong>'. $field->get_title() .'</strong> '. sprintf($this->__wcpf('(%s) is not a valid number.'), $value));
								$valid = false;
							}
							break;

						case 'email' :
							if(!is_email($value)){
								$this->wcpf_add_error('<strong>'. $field->get_title() .'</strong> '. sprintf($this->__wcpf('(%s) is not a valid email address.'), $value));
								$valid = false;
							}
							break;
					}
				}
			}
		}
		return $valid;
	}
	/************************************************
	**** CUSTOM PRODUCT OPTIONS VALIDATION - END ****
	*************************************************/
		

   /*********************************************************
	**** ADD CUSTOM OPTIONS & PRICE to CART ITEM - START ****
	*********************************************************/
	public function woo_add_cart_item_data( $cart_item_data, $product_id ) {
		$extra_options = $this->get_extra_options_from_wp_session_by_product_id($product_id);
		$this->set_extra_options_in_woo_session_using_product_id($product_id, $extra_options);
		$this->clear_extra_options_from_wp_session_by_product_id($product_id);
		
		if($extra_options){
			foreach($extra_options as $name => $data){
				if( isset($_POST[$name]) && !empty($_POST[$name]) ) {
					$cart_item_data[$name] = $_POST[$name];
					$cart_item_data['unique_key'] = md5( microtime().rand() );
				}
			}
		}
		return $cart_item_data;
	}

	public function woo_add_to_cart( $cart_item_key, $product_id = null, $quantity= null, $variation_id= null, $variation= null ) {
		$extra_options = $this->get_extra_options_from_woo_session_by_product_id($product_id);
		$extra_data = array();

		if($extra_options){
			foreach($extra_options as $name => $data){
				if( isset($_POST[$name]) && !empty($_POST[$name]) ) {
					$extra_data[$name] = $data;
				}
			}  	
		}	
		$this->set_extra_options_in_woo_session_using_cart_item_key($cart_item_key, $extra_data);		
	}

	public function woo_get_item_data( $cart_data, $cart_item = null ) {
		$custom_items = array();
		if(!empty( $cart_data ) ) {
			$custom_items = $cart_data;
		}		
		
		$extra_options = $this->get_extra_options_from_woo_session_by_product_id($cart_item['product_id']);
		if($extra_options){
			foreach($extra_options as $name => $data){
				if(isset($cart_item[$name])) {
					$custom_items[] = array( "name" => $data->get_title(), "value" => trim(stripslashes($cart_item[$name])) );
				}
			}
		}
		
		return $custom_items;
	}
	
	public function woo_add_order_item_meta( $item_id, $values, $cart_item_key ) {
		$options_extra = $this->get_extra_options_from_woo_session_by_cart_item_key($cart_item_key);
		if($options_extra){			
			foreach($options_extra as $name => $data){
				wc_add_order_item_meta( $item_id, $data->get_name(), $values[$name] );
			}
		}	
	}

	public function woo_order_items_meta_get_formatted( $formatted_meta, $item_meta ) {
		if(!empty($formatted_meta)){
			$options_extra = $this->get_product_custom_fields();
			if($options_extra){
				foreach($formatted_meta as &$meta){
					if(array_key_exists($meta['key'], $options_extra)) {
						$meta['label'] = $options_extra[$meta['key']]->get_title();
					}
				}
			}
		}
		return $formatted_meta;
	}
	
	public function woo_order_item_get_formatted_meta_data( $formatted_meta, $item){
		if(!empty($formatted_meta)){
			$options_extra = $this->get_product_custom_fields();
			if($options_extra){
				foreach($formatted_meta as $key => $meta){
					if(array_key_exists($meta->key, $options_extra)) {
						$formatted_meta[$key] = (object) array(
							'key'           => $meta->key,
							'value'         => $meta->value,
							'display_key'   => apply_filters( 'woocommerce_order_item_display_meta_key', $options_extra[$meta->key]->get_title() ),
							'display_value' => wpautop( make_clickable( apply_filters( 'woocommerce_order_item_display_meta_value', $meta->value ) ) ),
						);
					}
				}
			}
		}
		return $formatted_meta;
	}

   /*********************************************************
	**** ADD CUSTOM OPTIONS & PRICE to CART ITEM - END ******
	*********************************************************/
}
endif;