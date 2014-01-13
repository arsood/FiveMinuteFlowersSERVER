<?php include_once("../../api/connection.php"); include_once("../libraries/stripe/lib/Stripe.php");

if (isset($_POST['action']) && isset($_POST['orderNumber'])) {
	
	$order_id = mysql_real_escape_string($_POST['orderNumber']);
	
	//Grab the order
	
	$order_query = "SELECT * FROM flower_orders WHERE id = '{$order_id}'";
	$order_process = mysql_query($order_query);
	$order_array = mysql_fetch_array($order_process);
	
	if ($_POST['action'] == "accept") {
		$token = $order_array['payment_token'];
		
		$amount = $order_array["payment_amount"] * 100;
		
		Stripe::setApiKey("sk_test_PWbg81lqbrrfpFrmAS9iVFsb");
		try {
			$charge = Stripe_Charge::create(array(
						"amount" => $amount, // amount in cents, again
						"currency" => "usd",
						"card" => $token,
						"description" => "Payment")
					);
			} catch(Stripe_CardError $e) {
			// The card has been declined
		}
		
		$accept_update_query = "UPDATE flower_orders SET order_status = '2' WHERE id = '{$order_id}'";
		mysql_query($accept_update_query);
	} else if ($_POST['action'] == "deny") {
		$deny_update_query = "UPDATE flower_orders SET order_status = '3' WHERE id = '{$order_id}'";
		mysql_query($deny_update_query);
	} else if ($_POST['action'] == "ship") {
		$tracking_number = mysql_real_escape_string($_POST['trackingNum']);
		
		$tracking_update_query = "UPDATE amino_orders SET tracking_number = '{$tracking_number}', order_status = '3' WHERE id = '{$order_id}'";
		mysql_query($tracking_update_query);
		
		//Send email out to customer
		
		$mailcontents = file_get_contents('../../htmlemails/order-ship.html');
		$mailcontents = str_replace('{{tracking}}', $tracking_number, $mailcontents);
		
		$to = $order_array['email_address'];
		$subject = "Your order has shipped!";
		$message = $mailcontents;
		$from = "info@aminopharmaceuticals.com";
		$headers = "From: " . $from . "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		mail($to,$subject,$message,$headers);
	}
}

?>