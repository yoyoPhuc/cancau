<?php
/**
 * Plugin Name: Woo Extra Product Options
 * Description: Add extra product options in product page.
 * Author:      ThemeHiGH
 * Version:     1.2.2
 * Author URI:  https://www.themehigh.com
 * Plugin URI:  https://www.themehigh.com
 * Text Domain: woo-extra-product-options
 * Domain Path: /languages
 */
 
if(!defined('ABSPATH')){ exit; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
	}
}

if(is_woocommerce_active()) {
	if(!class_exists('WEPOF_Extra_Product_Options')){	
		class WEPOF_Extra_Product_Options {	
			public function __construct(){
				add_action('init', array($this, 'init'));
			}		

			public function init() {		
				$this->load_plugin_textdomain();
				
				define('TH_WEPOF_VERSION', '1.2.1');
				!defined('TH_WEPOF_PATH') && define('TH_WEPOF_PATH', plugin_dir_path( __FILE__ ));
				!defined('TH_WEPOF_URL') && define('TH_WEPOF_URL', plugins_url( '/', __FILE__ ));
				!defined('TH_WEPOF_ASSETS_URL') && define('TH_WEPOF_ASSETS_URL', TH_WEPOF_URL .'assets/');
				
				require_once( TH_WEPOF_PATH . 'classes/class-wepof-settings.php' );

				WEPOF_Settings::instance();					
			}

			public function load_plugin_textdomain(){							
				load_plugin_textdomain('woo-extra-product-options', FALSE, dirname(plugin_basename( __FILE__ )) . '/languages/');
			}
		}	
	}
	new WEPOF_Extra_Product_Options();
}