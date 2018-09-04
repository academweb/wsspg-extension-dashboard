<?php
/**
 * @package WSSPGExtention
 */
namespace Inc;

use \Inc\Api;

class UpdateData extends Api
{
	public function update_wsspgs_settings_page(){
		if(isset($_POST['wsspg_extention_settings'])){
			add_filter( 'admin_notices', array( $this, 'wsspg_ext_admin_notice__success' ));

			// Change default card
			if(isset($_POST['wsspg_extention_settings']['customer'])):
			$customer_id = $_POST['wsspg_extention_settings']['customer'];
			$default_card = $this->get_customer_default_card($customer_id);
			$send_default_card = $_POST['wsspg_extention_settings']['payment_method'];

			if($default_card != $send_default_card){
				$this->change_default_card($customer_id, $send_default_card);
			}
			endif;

			// Add new subscription
			if(isset($_POST['wsspg_extention_settings']['new_user'])):
				$new_user = $_POST['wsspg_extention_settings']['new_user'];
				$new_product = $_POST['wsspg_extention_settings']['new_product'];

				if($new_user != '' && $new_product != ''){
					$this->add_new_subscription($new_user, $new_product);
				}
			endif;

		}
	}

	function wsspg_ext_admin_notice__success(){
		?>
	    <div class="notice notice_mib notice-success is-dismissible">
	        <p>Data updated!</p>
	    </div>
	    <?php
	}
}