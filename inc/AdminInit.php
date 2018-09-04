<?php
/**
 * @package WSSPGExtention
 */
namespace Inc;
use \Inc\BaseController;
use \Inc\Pages\Settings;
use \Inc\UpdateData;

class AdminInit extends BaseController
{
	public $settings;
	public $data_update;

	public function register() {
		// Admin
		add_action('admin_enqueue_scripts', array( $this, 'wsspg_ext_admin_scripts' ) );
		add_action('login_enqueue_scripts', array( $this, 'wsspg_ext_admin_scripts' ));
		// Menu page
		add_action('admin_menu', array( $this, 'wsspg_ext_settings_page' ));

		// Call classes
		$this->settings = new Settings();
		$this->data_update = new UpdateData();

		// Data update
		add_action('admin_init', array( $this->data_update, 'update_wsspgs_settings_page' ));
	}

	function my_enqueuer($my_handle, $relpath, $type='script', $my_deps=array()) {
	    $uri = get_theme_file_uri($relpath);
	    $vsn = filemtime(get_theme_file_path($relpath));
	    if($type == 'script') wp_enqueue_script($my_handle, $uri, $my_deps, $vsn);
	    else if($type == 'style') wp_enqueue_style($my_handle, $uri, $my_deps, $vsn);      
	}

	function wsspg_ext_admin_scripts(){
		wp_enqueue_style('wsspg_ext_admin_style', $this->plugin_url . 'css/admin.css', array(), filemtime( $this->plugin_path . 'css/admin.css'));
		wp_enqueue_script('wsspg_ext_admin_script', $this->plugin_url . 'js/admin.js', array('jquery'), filemtime( $this->plugin_path . 'js/admin.js') );
		$this->my_enqueuer('wsspg_ext_admin_script', '/js/admin.js');
		$this->my_enqueuer('wsspg_ext_admin_style', '/css/admin.css');
		wp_localize_script('wsspg_ext_admin_script', 'wsspg_page_url', menu_page_url('wsspg-edit-subscriptions', $echo = false));
	}

	function wsspg_ext_settings_page(){
		add_submenu_page('woocommerce', 'Stripe - Edit Subscriptions', 'Stripe - Edit Subscriptions', 'manage_options', 'wsspg-edit-subscriptions', array( $this->settings, 'wsspg_edit_subscriptions_fill' ));
	}
}