<?php

/**
* How to use this class:
* Start to create an account on https://stripe.com/
* You can found your secret and private key https://manage.stripe.com/account/apikeys
* Use Test keys during devellopment and test card: 4242 4242 4242 4242
* Be careful the amound is in cents
* Use this basic form 
**/
/*
<form action="yourRoute" method="POST">
    <script
      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
      data-key="pk_test_K8Ipt5hlOkL0j2vqmzUK73EL"
      data-amount="2000"
      data-name="Demo Site"
      data-description="2 widgets ($20.00)"
      data-image="/128x128.png">
    </script>
</form>
**/

class Payment extends Prefab {

	/**
	*	Execute a payment
	*	@return bool
	*	@param $key string
	*	@param $amount int
	*	@param $function callback
	**/
	public function pay ($key, $amount, $function){
		Stripe::setApiKey($key);
		// Get the credit card details submitted by the form
		$token = $_POST['stripeToken'];
		// Create the charge on Stripe's servers - this will charge the user's card
		try {
			$charge = Stripe_Charge::create(array(
			  "amount" => $amount, // amount in cents, again
			  "currency" => "eur",
			  "card" => $token,
			  "description" => "An payment")
			);
			// Execute the callback function
			$function(false);
		} catch(Stripe_CardError $e) {
			// Execute the callback function
			$function(true);
		}
	}
}

?>