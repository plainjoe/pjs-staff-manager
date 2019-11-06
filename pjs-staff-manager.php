<?php
/**
 * Plugin Name: PJS Staff Manager
 * Plugin URI: https://www.plainjoestudios.com/
 * Description: Elegantly displays your staff members.
 * Version: 1.0.0
 * Author: PlainJoe Studios
 * Author URI: https://www.plainjoestudios.com/
 * License: GPLv2 or later
 * Text Domain: pjs-staff-manager
 */

if (!defined('ABSPATH')) {
	die();
}

if (!class_exists('pjsStaffManager')) {
	
	define('PJS_STAFF_PATH', plugin_dir_path(__FILE__));
	define('PJS_STAFF_URL', plugin_dir_url(__FILE__));
	
	// create class pjsStaffManager
	class pjsStaffManager {
		
		function __construct() {
			add_action('init', array($this, 'create_pjs_staff'));
			add_filter('template_include', array($this, 'templates'));
		}
		
		function register_scripts() {
			add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		}
		
		function activate() {
			if (is_plugin_active('advanced-custom-fields-pro/acf.php')) {
				$this->create_pjs_staff();
				flush_rewrite_rules();
			} else {
				$requireAcfErr = 'Sorry, this plugin requires the Advanced Custom Fields Pro plugin to be installed and active.';
				$requireAcfErr .= '<br><a href="' . admin_url('plugins.php') . '">Return to Plugins</a>';
				wp_die($requireAcfErr);
			}
		}
		
		function deactivate() {
			flush_rewrite_rules();
		}
		
		function create_pjs_staff() {
			require_once(PJS_STAFF_PATH . '/templates/post-type/staff.php');
		}
		
		function enqueue() {
			// plugin JS/CSS
			wp_enqueue_style('pjs-staff-manager', PJS_STAFF_URL . 'css/pjs.css', array(), '', false);
			wp_enqueue_style('pjs-staff-manager-responsive', PJS_STAFF_URL . 'css/responsive.css', array(), '', false);
			wp_enqueue_script('pjs-jquery', PJS_STAFF_URL . 'js/jquery.min.js', array(), '', false);
			wp_enqueue_script('pjs-staff-manager-js', PJS_STAFF_URL . 'js/pjs.js', array(), '', false);
			
			// included plyr JS/CSS
			wp_enqueue_style('pjs-staff-manager-font-awesome', PJS_STAFF_URL . 'includes/fontawesome/css/all.min.css', array(), '', false);
		}
		
		function archiveAJAX() {
			// load more AJAX call
			wp_enqueue_script('pjs-staff-manager-ajax', PJS_STAFF_URL . 'ajax/load-more.js', array(), '', true);
		}
		
		// setup templates
		function templates($template) {
			if (is_singular('pjs_staff')) {
				$this->register_scripts();
				return PJS_STAFF_PATH . '/templates/page/single.php';
			} elseif (is_post_type_archive('pjs_staff')) {
				$this->register_scripts();
				$this->archiveAJAX();
				return PJS_STAFF_PATH . '/templates/page/archive.php';
			}
			
			return $template;
		}
		
	}
	
	$pjsStaffManager = new pjsStaffManager();
	
	// activation
	register_activation_hook(__FILE__, array($pjsStaffManager, 'activate'));
	
	// deactivation
	register_deactivation_hook(__FILE__, array($pjsStaffManager, 'deactivate'));
	
	
	
	
	// flush rewrite rules upon saving the settings
	function pjs_staff_settings_update() {
		if (!$option = get_option('pjs-staff-flush-rewrite-rules')) {
			return false;
		}
		
		if ($option == 1) {
			flush_rewrite_rules();
			update_option('pjs-staff-flush-rewrite-rules', 0);
		}
		
		return true;
	}
	add_action('init', 'pjs_staff_settings_update', 99999);

	function pjs_staff_settings_save() {
		update_option('pjs-staff-flush-rewrite-rules', 1);
		return true;
	}
	add_action('acf/save_post', 'pjs_staff_settings_save', 10, 2);


	// creates the ACF fields used in the plugin
	function pjs_staff_create_acf_fields() {
		require_once(PJS_STAFF_PATH . '/includes/acf/fields.php');
	}
	add_action('acf/init', 'pjs_staff_create_acf_fields');

}