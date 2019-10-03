<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("../include/pConfig.php");
include_once("../include/classes.inc.php");
include_once("../include/dbh.inc.php");
include_once("paypal.class.php");

$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';

if(isset($_SESSION["cart"])) //Post Data received from product list page.
{
    //Other important variables like tax, shipping cost

    //we need 4 variables from product page Item Name, Item Price, Item Number and Item Quantity.
    //Please Note : People can manipulate hidden field amounts in form,
    //In practical world you must fetch actual price from database using item id. 
    //eg : $ItemPrice = $mysqli->query("SELECT item_price FROM products WHERE id = Product_Number");
    $paypal_data ='';
    $ItemTotalPrice = 0;
    $i = 0;
    $cart = unserialize($_SESSION['cart']);
    foreach ($cart->cart_items as $item) {
    $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$i.'='.urlencode($item->name); //name
    $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$i.'='.urlencode($item->product_id); //id
    $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$i.'='.urlencode($item->price); // price
    $paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$i.'='. urlencode(1); // quntity
        
    //total price
    $ItemTotalPrice = $ItemTotalPrice + $item->price;
		
    //create items for session
    $paypal_product['items'][] = array(
        'itm_name'=>$item->name,
        'itm_price'=>$item->price,
        'itm_code'=>$item->product_id, 
        'itm_qty'=>1);
        $i++;
    }
	
    $total_tax = 0;	
    foreach($taxes as $key => $value) { //list and calculate all taxes in array
        $tax_amount     = round($ItemTotalPrice * ($value / 100));
        $tax_item[$key] = $tax_amount;
        $total_tax = $total_tax + $tax_amount; //total tax amount
    }
				
    //Grand total including all tax, insurance, shipping cost and discount
    $GrandTotal = ($ItemTotalPrice + $total_tax + $HandalingCost + $InsuranceCost + $shipping_cost + $ShippinDiscount);
								
    $paypal_product['assets'] = array(
        'tax_total'=>$total_tax, 
        'handaling_cost'=>$HandalingCost, 
        'insurance_cost'=>$InsuranceCost,
        'shippin_discount'=>$ShippinDiscount,
        'shippin_cost'=>$shipping_cost,
        'grand_total'=>$GrandTotal);
	
    //create session array for later use
    $_SESSION["paypal_products"] = $paypal_product;
	
    //Parameters for SetExpressCheckout, which will be sent to PayPal
    $padata = 	'&METHOD=SetExpressCheckout'.
                '&RETURNURL='.urlencode($PayPalReturnURL ).
                '&CANCELURL='.urlencode($PayPalCancelURL).
                '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
                $paypal_data.				
                '&NOSHIPPING=0'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                '&PAYMENTREQUEST_0_TAXAMT='.urlencode($total_tax).
                '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_cost).
                '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
                '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
                '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
                '&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&LOCALECODE=GB'. //PayPal pages to match the language on your website.
                '&LOGOIMG=http://maysh.mtacloud.co.il/images/woodit.png'. //site logo
                '&CARTBORDERCOLOR=FFFFFF'. //border color of cart
                '&ALLOWNOTE=1';
		
    //We need to execute the "SetExpressCheckOut" method to obtain paypal token
    $paypal= new MyPayPal();
    $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
    //Respond according to message we receive from Paypal
    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        unset($_SESSION["cart_products"]); //session no longer needed
        //Redirect user to PayPal store with Token received.
        $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
        header('Location: '.$paypalurl);
    } else {
        //Show error message
        $cart_summary = new cart_summary();
        $cart_summary->is_error = true;
        $cart_summary->error_message = 'שגיאה: ' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
        $_SESSION['cart_summary'] = serialize($cart_summary);
        header('Location: ../summary.php');
    }
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["token"]))
{
    //we will be using these two variables to execute the "DoExpressCheckoutPayment"
    //Note: we haven't received any payment yet.

    $token = $_GET["token"];
    $payer_id = $_GET["PayerID"];

    //get session variables
    $paypal_product = $_SESSION["paypal_products"];
    $paypal_data = '';
    $ItemTotalPrice = 0;

    foreach($paypal_product['items'] as $key=>$p_item) {		
        $paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='. urlencode($p_item['itm_qty']);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($p_item['itm_price']);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($p_item['itm_name']);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($p_item['itm_code']);
        
        // item price X quantity
        $subtotal = ($p_item['itm_price']*$p_item['itm_qty']);
		
        //total price
        $ItemTotalPrice = ($ItemTotalPrice + $subtotal);
    }

    $padata = 	'&TOKEN='.urlencode($token).
                '&PAYERID='.urlencode($payer_id).
                '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
                $paypal_data.
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                '&PAYMENTREQUEST_0_TAXAMT='.urlencode($paypal_product['assets']['tax_total']).
                '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($paypal_product['assets']['shippin_cost']).
                '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($paypal_product['assets']['handaling_cost']).
                '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($paypal_product['assets']['shippin_discount']).
                '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($paypal_product['assets']['insurance_cost']).
                '&PAYMENTREQUEST_0_AMT='.urlencode($paypal_product['assets']['grand_total']).
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);

    //We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
    $paypal= new MyPayPal();
    $httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

    $cart_summary = new cart_summary();
    //Check if everything went ok..
    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        //Sometimes Payment are kept pending even when transaction is complete. 
        //hence we need to notify user about it and ask him manually approve the transiction
        if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
            $cart_summary->is_complete = true;
            $cart_summary->complete_message = 'תשלום התקבל, המוצרים שלך ישלחו אליך בהקדם';
        } elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
            $cart_summary->is_complete = true;
            $cart_summary->complete_message = 'עסקה בוצעה, אך התשלום עדיין ממתין, יש לבצע אישור תשלום בחשבון הPaypal שלך';
        }

        $transactionID = urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

        // we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
        // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
        $padata = '&TOKEN='.urlencode($token);
        $paypal= new MyPayPal();
        $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            $user_id = $_SESSION['user']['id'];
            $insert_row = $conn->query("INSERT INTO orders (user, transaction) VALUES ($user_id, '$transactionID')");
            if($insert_row){
                $order_id = $conn->insert_id;
                $cart = unserialize($_SESSION['cart']);
                foreach ($cart->cart_items as $item) {
                     $conn->query("INSERT INTO order_products (order_id, product_id, image, price) 
                                    VALUES ($order_id, $item->product_id, '$item->image', $item->price)");
                }
                unset($_SESSION["cart"]);
                unset($_SESSION["paypal_products"]);
                
                // the message
                $msg = '<html lang="he-IL">';
                $msg .= '<head><meta charset="utf-8"></head>';
                $msg .= '<body dir="rtl">';
                $msg .= "שלום " . $_SESSION['user']['name'] . ",";
                $msg .= "<br/><br/>";
                $msg .= "הזמנתך באתר WOODit התקבלה";
                $msg .= "<br/>";
                $msg .= "מספר הזמנה: " . $order_id;
                $msg .= "<br/>";
                $msg .= "מספר עסקה: " . $transactionID;
                $msg .= "<br/><br/>";
                $msg .= "אנחנו כבר מתחלים לעבוד על המשלוח";
                $msg .= "<br/><br/>";
                $msg .= "בברכה,";
                $msg .= "<br/>";
                $msg .= "WOODit";
                $msg .= '</body></html>';
                // To send HTML mail, the Content-type header must be set
                $headers = array(
                  "Content-Type: text/html; charset=utf-8",
                  "MIME-Version: 1.0",
                  "X-Mailer: PHP/" . PHP_VERSION
                );
                $headers = implode("\r\n", $headers);       
                // send email
                mail($_SESSION['user']['email'], "Woodit - הזמנתך התקבלה", $msg, $headers);
                
            } else {
                $cart_summary->is_error = true;
                $cart_summary->error_message = 'שגיאה: ('. $conn->errno .') '. $conn->error;
            }
        } else  {
            $cart_summary->is_error = true;
            $cart_summary->error_message = 'GetTransactionDetails failed: ' .urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
        }	
    }else {
        $cart_summary->is_error = true;
        $cart_summary->error_message = 'שגיאה: ' .urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
    }
    
    $_SESSION['cart_summary'] = serialize($cart_summary);
    header('Location: ../summary.php');
}
?>
