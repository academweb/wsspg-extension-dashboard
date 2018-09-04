<?php
/**
 * @package WSSPGExtention
 */
namespace Inc\Pages;

use \Inc\Api;

class Settings extends Api
{	
	public $edit;
	public $new;

	public function wsspg_edit_subscriptions_fill(){
		$this->edit = $_GET['edit'];
		$this->new = $_GET['new'];
		?>
		<div class="wrap">

			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
			<?php if(isset($this->edit)){ ?>
			<h1>Edit Stripe Subscription</h1>
			<?php }elseif(isset($this->new)){ ?>
			<h1>Add Stripe Subscription</h1>
			<?php }else{ ?>
			<h1>Stripe Subscription</h1>
			<?php } ?>
			
			<div class="wsspg-ext-page-content">
				
			<?php  
				if(!isset($this->edit) && !isset($this->new)){
					echo '<h2>Please select a subscription to edit. <a href="'. menu_page_url('wsspg-woocommerce-stripe-subscription-payment-gateway', $echo = false) . '">Click here</a></h2>';
				}
			?>

			<?php
			if(isset($this->edit)):
				$current_subscription = $this->get_one_subscription($this->edit);
				$current_products = $this->get_subscription_plan($current_subscription);
			?>
				<div class="wsspg-ext-page-info-block">
					<p class="wsspg-ext-page-info-block-description">Information</p>
					<p class="wsspg-ext-page-info-block-inf"><b>ID: </b><?php echo $current_subscription->id; ?></p>
					<p class="wsspg-ext-page-info-block-inf"><b>Status: </b><?php echo $current_subscription->status; ?></p>
					<p class="wsspg-ext-page-info-block-inf"><b>Customer ID: </b><?php echo $current_subscription->customer; ?></p>
					<?php if(!empty($current_products)){ ?>
					<p class="wsspg-ext-page-info-block-inf"><b>Plan(s): </b>
						<?php foreach ($current_products as $product_plan_k => $product_plan) {
							$coma = ($product_plan_k != '') ? ', ' : '';
							echo $product_plan . $coma;
						} ?>
					</p>
					<?php } ?>
					<p class="wsspg-ext-page-info-block-inf"><b>Current period start: </b><?php echo date('Y/m/d' ,$current_subscription->current_period_start); ?></p>
					<p class="wsspg-ext-page-info-block-inf"><b>Current period end: </b><?php echo date('Y/m/d' ,$current_subscription->current_period_end); ?></p>
				</div>

				<div class="wsspg-ext-page-info-block">
					<p class="wsspg-ext-page-info-block-description">Edit</p>
					
					<p><b>Payment method</b></p>
					<?php $customer = $this->get_one_customer($current_subscription->customer); ?>
					<input type="hidden" value="<?php echo $current_subscription->customer; ?>" name="wsspg_extention_settings[customer]">
					<?php

					$cards_arr = array();
					if(!empty($customer)){
						$cards_arr = $this->get_cards($customer);
					}

					if(!empty($cards_arr)){
						foreach ($cards_arr as $key_card => $value_card) {
							$card_info = $this->get_one_card($current_subscription->customer, $value_card['card_id']);
							?>
							<div class="wsspg-ext-page-info-block-radio">
								<input id="payment_method_<?php echo $key_card; ?>" type="radio" name="wsspg_extention_settings[payment_method]" value="<?php echo $card_info->id; ?>" <?php checked( 1 == $value_card['default']); ?>>
								<label for="payment_method_<?php echo $key_card; ?>"><?php echo $card_info->brand . ' ****' . $card_info->last4; ?></label>
							</div>
							<?php
						}
					}else{
						echo '<p>No payment methods</p>';
					}
					?>
				</div>
			<?php
			endif;
			?>

			<?php if(isset($this->new)): ?>
				<div class="wsspg-ext-page-info-block">
					<p class="wsspg-ext-page-info-block-description">Settings</p>
					
					<div class="wsspg-ext-page-setting-block">
						<p>Select customer</p>
						<?php $all_users = get_users(); ?>
						<select name="wsspg_extention_settings[new_user]">
						<?php 
						foreach ($all_users as $key_user => $value_user) { 
							$user_id = $value_user->ID;
							$user_stripe_id = get_user_meta($user_id, 'wsspg_test_stripe_id', true);

							if($user_stripe_id != ''){
						?>
							<option value="<?php echo $user_stripe_id; ?>"><?php echo $value_user->user_email . ', ' . $value_user->user_nicename . ', Stripe ID: ' . $user_stripe_id; ?></option>
						<?php 
							}
						?>
						<?php } ?>
						</select>
					</div>

					<div class="wsspg-ext-page-setting-block">
						<p>Select product</p>
						<?php  
						$products = wc_get_products( array('limit' => -1, 'return' => 'ids') );
						if(!empty($products)){
							?>
						<select name="wsspg_extention_settings[new_product]">
							<?php
							foreach ($products as $key_product => $value_product) {
							$product = get_product( $value_product );
							if( $product->is_type( 'wsspg_subscription' ) ){
							$product_plan_id = get_post_meta( $value_product, '_wsspg_stripe_plan_id', true );
							if($product_plan_id != ''){
						?>
							<option value="<?php echo $product_plan_id; ?>"><?php echo $product->get_title(); ?></option>
						<?php 
							}
							}
							}
							?>
						</select>
							<?php
						}
						?>
					</div>
				</div>
			<?php endif; ?>
			
			</div>

			<?php 
				if(isset($this->edit) || isset($this->new)){
					submit_button(); 
				}
			?>

			</form>
		</div>
		<?php
	}
}