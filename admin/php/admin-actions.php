<?php include_once("../../api/connection.php"); include_once("../libraries/stripe/lib/Stripe.php");

if (isset($_POST['action']) && isset($_POST['orderNumber'])) {
	
	$order_id = mysql_real_escape_string($_POST['orderNumber']);
	
	//Grab the order
	
	$order_query = "SELECT * FROM flower_orders WHERE id = '{$order_id}'";
	$order_process = mysql_query($order_query);
	$order_array = mysql_fetch_array($order_process);
	
	if ($_POST['action'] == "accept") {
		
		//Handle Stripe call
		
		$token = $order_array['payment_token'];
		
		$amount = $order_array["payment_amount"] * 100;
		
		Stripe::setApiKey("sk_live_IfL106Tndiwr9IGXzWYsmGyp");
		try {
			$charge = Stripe_Charge::create(array(
						"amount" => $amount, // amount in cents, again
						"currency" => "usd",
						"card" => $token,
						"description" => "FMF Payment"
						)
					);
			} catch(Stripe_CardError $e) {
			// The card has been declined
		}
		
		$accept_update_query = "UPDATE flower_orders SET order_status = '2' WHERE id = '{$order_id}'";
		mysql_query($accept_update_query);
		
		//Today's date
		
		$date_today = date('m/d/Y');
		
		//Get price
		
		$price_query = "SELECT retail_price FROM flower_inventory WHERE arrangement_code = '{$order_array['arrangement_code']}'";
		$price_process = mysql_query($price_query);
		$price_array = mysql_fetch_array($price_process);
		$flower_price = round(($price_array[0] + 6), 2);
		
		//Do some curling mate
		
		$xml_full = "<?xml version='1.0' encoding='iso-8859-1' ?><!DOCTYPE FSIORDER[<!ELEMENT FSIORDER (SENDING_SHOP, FILLING_SHOP, SUCCESS_EMAIL, ERROR_EMAIL, SENDER_ORDER_NUMBER, DELIVERY_DATE, DELIVERY_TIME_REQUEST1, DELIVER_TO, ATTN_OF, ADDRESS_LINE_1, ADDRESS_LINE_2, CITY, STATE, ZIP, COUNTRY, TELEPHONE, LOCATION_TYPE, OCCASION, CARD, SPECIAL_INSTR, CHOICE_1, CHOICE_2, PRICE, CUSTOMER_DATA)><!ELEMENT SENDING_SHOP (#PCDATA)><!ELEMENT FILLING_SHOP (#PCDATA)><!ELEMENT SUCCESS_EMAIL (#PCDATA)><!ELEMENT ERROR_EMAIL (#PCDATA)><!ELEMENT SENDER_ORDER_NUMBER (#PCDATA)><!ELEMENT DELIVERY_DATE (#PCDATA)><!ELEMENT DELIVERY_TIME_REQUEST1 (#PCDATA)><!ELEMENT DELIVER_TO (#PCDATA)><!ELEMENT ATTN_OF (#PCDATA)><!ELEMENT ADDRESS_LINE_1 (#PCDATA)><!ELEMENT ADDRESS_LINE_2 (#PCDATA)><!ELEMENT CITY (#PCDATA)><!ELEMENT STATE (#PCDATA)><!ELEMENT ZIP (#PCDATA)><!ELEMENT COUNTRY (#PCDATA)><!ELEMENT TELEPHONE (#PCDATA)><!ELEMENT LOCATION_TYPE (#PCDATA)><!ELEMENT OCCASION (#PCDATA)><!ELEMENT CARD (#PCDATA)><!ELEMENT SPECIAL_INSTR (#PCDATA)><!ELEMENT CHOICE_1 (#PCDATA)><!ELEMENT CHOICE_2 (#PCDATA)><!ELEMENT PRICE (#PCDATA)><!ELEMENT INTERNAL_DATA (#PCDATA)>]><FSIORDER><SENDING_SHOP>14-7190</SENDING_SHOP><FILLING_SHOP>55-0000</FILLING_SHOP><SUCCESS_EMAIL></SUCCESS_EMAIL><ERROR_EMAIL></ERROR_EMAIL><SENDER_ORDER_NUMBER>{$order_array['id']}</SENDER_ORDER_NUMBER><DELIVERY_DATE>{$date_today}</DELIVERY_DATE><DELIVERY_TIME_REQUEST1></DELIVERY_TIME_REQUEST1><DELIVER_TO><![CDATA[]]>{$order_array['delivery_first_name']} {$order_array['delivery_last_name']}</DELIVER_TO><ATTN_OF><![CDATA[]]></ATTN_OF><ADDRESS_LINE_1><![CDATA[]]>{$order_array['delivery_address_1']}</ADDRESS_LINE_1><ADDRESS_LINE_2><![CDATA[]]>{$order_array['delivery_address_2']}</ADDRESS_LINE_2><CITY><![CDATA[]]>{$order_array['delivery_city']}</CITY><STATE><![CDATA[]]>{$order_array['delivery_state']}</STATE><ZIP><![CDATA[]]>{$order_array['delivery_zipcode']}</ZIP><COUNTRY>US</COUNTRY><TELEPHONE><![CDATA[]]>{$order_array['delivery_phone']}</TELEPHONE><LOCATION_TYPE>Home</LOCATION_TYPE><OCCASION><![CDATA[]]></OCCASION><CARD><![CDATA[]]>{$order_array['personal_message']}</CARD><SPECIAL_INSTR><![CDATA[]]>{$order_array['delivery_instructions']}</SPECIAL_INSTR><INTERNAL_DATA><![CDATA[]]></INTERNAL_DATA><CHOICE_1><![CDATA[]]>{$order_array['arrangement_code']}</CHOICE_1><CHOICE_2><![CDATA[]]></CHOICE_2><PRICE>{$flower_price}</PRICE></FSIORDER>";
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,"https://hampshire.floralsourceinc.com/FiveMinuteFlowers/incoming/order.cgi");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_full);
		
		$xml_return = curl_exec($ch);
		curl_close ($ch);
		
		echo $xml_return;
		
		//Get user's email
		
		$email_query = "SELECT email FROM user_info WHERE uuid = '{$order_array['uuid']}'";
		$email_process = mysql_query($email_query);
		$user_email = mysql_fetch_array($email_process);
		
		//Send email out to customer
		
		$mailcontents = file_get_contents('../../htmlemails/order-processed.html');
		$mailcontents = str_replace('{{number}}', $order_array['id'], $mailcontents);
		
		$to = $user_email[0];
		$subject = "Thanks for your order!";
		$message = $mailcontents;
		$from = "FiveMinuteFlowers Sales <sales@fiveminuteflowers.com>";
		$headers = "From: " . $from . "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		mail($to,$subject,$message,$headers);
		
	} else if ($_POST['action'] == "deny") {
		$deny_update_query = "UPDATE flower_orders SET order_status = '3' WHERE id = '{$order_id}'";
		mysql_query($deny_update_query);
	}
}

?>