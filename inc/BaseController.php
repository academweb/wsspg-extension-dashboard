<?php
/**
 * @package WSSPGExtention
 */
namespace Inc;

class BaseController
{
	public $plugin_path;
	public $plugin_url;
   public $stripe_mode;
   public $stripe_sk;

   public function __construct() {
      $this->plugin_path = plugin_dir_path( call_user_func_array( array($this, 'dirname_r'), array(__FILE__, 1) ) );
      $this->plugin_url = plugin_dir_url( call_user_func_array( array($this, 'dirname_r'), array(__FILE__, 1) ) );

      $stripe_settings = get_option( 'woocommerce_wsspg_settings', true );
      $this->stripe_mode = $stripe_settings['mode'];
      $this->stripe_sk = ($this->stripe_mode == 'test') ? $stripe_settings['test_secret_key'] : $stripe_settings['live_secret_key'];
   }

   function dirname_r($path, $count=1){
      if ($count > 1){
         return dirname( call_user_func_array( array($this, 'dirname_r'), array($path, --$count) ) );
      }else{
         return dirname($path);
      }
   }
}