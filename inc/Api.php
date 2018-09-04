<?php
/**
 * @package WSSPGExtention
 */
namespace Inc;
use \Inc\BaseController;

class Api extends BaseController
{
	public function register() {
		\Stripe\Stripe::setApiKey($this->stripe_sk);
	}

	public function get_one_subscription( $id ){
		$sub = \Stripe\Subscription::retrieve( $id );
		return $sub;
	}

	public function get_one_customer( $id ){
		$cus = \Stripe\Customer::retrieve( $id );
		return $cus;
	}

	public function get_cards( $customer ){
		$cards_array = array();
		$customer_sources = $customer->sources['data'];
		$customer_default = $customer->default_source;
		foreach ($customer_sources as $key_source => $value_source) {
			$object = $value_source->object;
			if($object == 'card'){
				$new_cards_array = array();
				$new_cards_array['card_id'] = $value_source->id;
				$default = false;
				if($customer_default == $value_source->id){
					$default = true;
				}
				$new_cards_array['default'] = $default;
				array_push($cards_array, $new_cards_array);
			}

		}

		return $cards_array;
	}

	public function get_customer_default_card( $customer_id ){
		$customer = $this->get_one_customer($customer_id);
		return $customer->default_source;
	}

	public function get_one_card( $customer_id, $id ){
		$customer = \Stripe\Customer::retrieve($customer_id);
		$card = $customer->sources->retrieve($id);
		return $card;
	}

	public function get_subscription_plan( $subscription ){
		$result = '';

		if(!empty($subscription)){
			$plans = $subscription->items->data;
			if(!empty($plans)){
				$plans_arr = array();
				foreach ($plans as $value) {
					array_push($plans_arr, $value->plan->id);
				}

				$result = $plans_arr;
			}else{
				$result = array();
			}
		}else{
			$result = array();
		}

		return $result;
	}

	public function get_one_plan( $id ){
		$p = \Stripe\Plan::retrieve( $id );
		return $p;
	}

	public function change_default_card( $customer, $new_card ){
		if($new_card != ''){
			$cu = \Stripe\Customer::retrieve( $customer );
			$cu->default_source = $new_card;
			$cu->save();
		}
	}

	public function add_new_subscription( $customer, $plan ){
		\Stripe\Subscription::create(array(
		  "customer" => $customer,
		  "items" => array(
		    array(
		      "plan" => $plan,
		    ),
		  )
		));
	}
	
}