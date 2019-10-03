<?php
//paypal settings
$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'danielts86-facilitator_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword 		= 'MV96YQHDZ2JC3U8H'; //Paypal API password
$PayPalApiSignature 	= 'Aql07BfdJ2YpUM2-t94hLviCHmeBAs9iRx0tr3UCN7o5i5BkL0rISH.Z'; //Paypal API Signature
$PayPalCurrencyCode 	= 'ILS'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://maysh.mtacloud.co.il/paypal-express-checkout/'; //Point to paypal-express-checkout page
$PayPalCancelURL 		= 'http://maysh.mtacloud.co.il/paypal-express-checkout/cancel_url.html'; //Cancel URL if user clicks cancel

//Additional taxes and fees											
$HandalingCost 		= 0.00;  //Handling cost for the order.
$InsuranceCost 		= 0.00;  //shipping insurance cost for the order.
$shipping_cost      = 0.00; //shipping cost
$ShippinDiscount 	= 0.00; //Shipping discount for this order. Specify this as negative number (eg -1.00)
$taxes              = array( //List your Taxes percent here.
                            'VAT' => 0, 
                            'Service Tax' => 0
                            );
?>