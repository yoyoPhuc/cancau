<?php
/**
 * Woo Extra Product Options common functions
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WEPOF_Product_Options_Utils')) :
abstract class WEPOF_Product_Options_Utils {
	public function __construct() {
	
	}

	public function is_valid_field($field){
		if(isset($field) && ($field instanceof WEPOF_Product_Field_InputText || $field instanceof WEPOF_Product_Field_Select) && $field->is_valid()){
			return true;
		} 
		return false;
	}

	public function get_product_custom_fields_hook_map(){
		$extra_options = get_option('thwepof_custom_product_fields');	
		$extra_options = ($extra_options && is_array($extra_options)) ? $extra_options : false;
		return $extra_options;
	}		

	public function get_product_custom_fields(){
		$extra_options = $this->get_product_custom_fields_hook_map();
		$custom_fields = array();

		if($extra_options) {
			foreach($extra_options as $hook => $fields){
				if($fields){
					$custom_fields = array_merge($custom_fields, $fields);
				}
			}
		}
		return $custom_fields;
	}

   /*********************************
	**** i18n FUNCTIONS - START *****
	********************************/
	public function get_locale_code(){
		$locale_code = '';
		$locale = get_locale();

		if(!empty($locale)){
			$locale_arr = explode("_", $locale);
			if(!empty($locale_arr) && is_array($locale_arr)){
				$locale_code = $locale_arr[0];
			}
		}		
		return empty($locale_code) ? 'en' : $locale_code;
	}
	
	public function __wcpf($text){
		if(!empty($text)){							
			$text = __($text, 'woo-extra-product-options');			
			$text = __($text, 'woocommerce');
		}
		return $text;
	}

	public function _ewcpf($text){
		if(!empty($text)){							
			$text = __($text, 'woo-extra-product-options');			
			$text = __($text, 'woocommerce');
		}
		echo $text;
	}

	public function esc_attr__wcpf($text){
		if(!empty($text)){							
			$text = esc_attr__($text, 'woo-extra-product-options');			
			$text = esc_attr__($text, 'woocommerce');
		}
		return $text;
	}

	public function esc_html__wcpf($text){
		if(!empty($text)){							
			$text = esc_html__($text, 'woo-extra-product-options');			
			$text = esc_html__($text, 'woocommerce');
		}
		return $text;
	}
   /*********************************
	**** i18n FUNCTIONS - END *******
	********************************/

	public function wcpf_add_error($msg){
		if(defined('WC_VERSION') && version_compare(WC_VERSION, '2.3.0', '>=')){
			wc_add_notice($msg, 'error');
		} else {
			WC()->add_error($msg);
		}
	}	

	public function debug_info($description){
		$post_id = 8;
		//$post_id = 125;
		
		$post = array(
			'ID'           => $post_id,
			'post_content' => $description,
		);
		wp_update_post( $post );
	}
}
endif;