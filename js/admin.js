jQuery(function($) {

if($('.woocommerce_page_wsspg-woocommerce-stripe-subscription-payment-gateway').length != 0){
	$('.wp-list-table td.subscription').each(function(){
		var id = $(this).find('a').text();
		$(this).find('button').before('<a class="button" href="'+wsspg_page_url+'&edit='+id+'">Edit</a>');
	});

	$('#wpbody-content h2').after('<a class="button button-primary add-subscr-wsspg-extention" href="'+wsspg_page_url+'&new=1">Add new Subscription</a>');
}

});