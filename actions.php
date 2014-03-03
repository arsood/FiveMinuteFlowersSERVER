<?php include_once("connection.php");

//Let's escape all the post data

$user_id = mysql_real_escape_string($_POST["userUuid"]);
$arrangement_selected = mysql_real_escape_string($_POST["arrangementSelected"]);
$arrangement_price = mysql_real_escape_string($_POST["arrangementPrice"]);
$payment_token = mysql_real_escape_string($_POST["paymentToken"]);
$billing_first_name = mysql_real_escape_string($_POST["billingFirstName"]);
$billing_last_name = mysql_real_escape_string($_POST["billingLastName"]);
$billing_address_1 = mysql_real_escape_string($_POST["billingAddress1"]);
$billing_address_2 = mysql_real_escape_string($_POST["billingAddress2"]);
$billing_city = mysql_real_escape_string($_POST["billingCity"]);
$billing_state = mysql_real_escape_string($_POST["billingState"]);
$billing_zipcode = mysql_real_escape_string($_POST["billingZipcode"]);
$billing_phone = mysql_real_escape_string($_POST["billingPhone"]);
$delivery_first_name = mysql_real_escape_string($_POST["deliveryFirstName"]);
$delivery_last_name = mysql_real_escape_string($_POST["deliveryLastName"]);
$delivery_address_1 = mysql_real_escape_string($_POST["deliveryAddress1"]);
$delivery_address_2 = mysql_real_escape_string($_POST["deliveryAddress2"]);
$delivery_city = mysql_real_escape_string($_POST["deliveryCity"]);
$delivery_state = mysql_real_escape_string($_POST["deliveryState"]);
$delivery_zipcode = mysql_real_escape_string($_POST["deliveryZipcode"]);
$delivery_phone = mysql_real_escape_string($_POST["deliveryPhone"]);
$personal_message = mysql_real_escape_string($_POST["personalMessage"]);
$delivery_instructions = mysql_real_escape_string($_POST["deliveryInstructions"]);

//Make the insert chu chi

$insert_query = "INSERT INTO flower_orders (uuid, arrangement_code, billing_first_name, billing_last_name, billing_address_1, billing_address_2, billing_city, billing_state, billing_zipcode, billing_phone, delivery_first_name, delivery_last_name, delivery_address_1, delivery_address_2, delivery_city, delivery_state, delivery_zipcode, delivery_phone, payment_token, payment_amount, personal_message, delivery_instructions, order_status) VALUES ('{$user_id}', '{$arrangement_selected}', '{$billing_first_name}', '{$billing_last_name}', '{$billing_address_1}', '{$billing_address_2}', '{$billing_city}', '{$billing_state}', '{$billing_zipcode}', '{$billing_phone}', '{$delivery_first_name}', '{$delivery_last_name}', '{$delivery_address_1}', '{$delivery_address_2}', '{$delivery_city}', '{$delivery_state}', '{$delivery_zipcode}', '{$delivery_phone}', '{$payment_token}', '{$arrangement_price}', '{$personal_message}', '{$delivery_instructions}', '1')";
mysql_query($insert_query);

$order_id = mysql_insert_id();

if ($_POST["saveBilling"] == "true") {
	$insert_billing_query = "INSERT INTO saved_billing (uuid, billing_first_name, billing_last_name, billing_address_1, billing_address_2, billing_city, billing_state, billing_zipcode, billing_phone) VALUES ('{$user_id}', '{$billing_first_name}', '{$billing_last_name}', '{$billing_address_1}', '{$billing_address_2}', '{$billing_city}', '{$billing_state}', '{$billing_zipcode}', '{$billing_phone}')";
	mysql_query($insert_billing_query);
}

if ($_POST["saveDelivery"] == "true") {
	$insert_delivery_query = "INSERT INTO saved_delivery (uuid, delivery_first_name, delivery_last_name, delivery_address_1, delivery_address_2, delivery_city, delivery_state, delivery_zipcode, delivery_phone) VALUES ('{$user_id}', '{$delivery_first_name}', '{$delivery_last_name}', '{$delivery_address_1}', '{$delivery_address_2}', '{$delivery_city}', '{$delivery_state}', '{$delivery_zipcode}', '{$delivery_phone}')";
	mysql_query($insert_delivery_query);
}

//Get user email

$email_query = "SELECT email FROM user_info WHERE uuid = '{$user_id}'";
$email_process = mysql_query($email_query);
$user_email = mysql_fetch_array($email_process);

//Get full arrangement name

$name_query = "SELECT arrangement_name FROM flower_inventory WHERE arrangement_code = '{$arrangement_selected}'";
$name_process = mysql_query($name_query);
$flower_name = mysql_fetch_array($name_process);

//Send email out to customer

$mailcontents = file_get_contents('../htmlemails/order-received.html');
$mailcontents = str_replace('{{number}}', $order_id, $mailcontents);
$mailcontents = str_replace('{{delivery_name}}', $delivery_first_name. " ".$delivery_last_name, $mailcontents);
$mailcontents = str_replace('{{delivery_address}}', $delivery_address_1, $mailcontents);
$mailcontents = str_replace('{{delivery_city}}', $delivery_city.", ".$delivery_state." ".$delivery_zipcode, $mailcontents);
$mailcontents = str_replace('{{delivery_phone}}', $delivery_phone, $mailcontents);
$mailcontents = str_replace('{{personal_message}}', $personal_message, $mailcontents);
$mailcontents = str_replace('{{delivery_instructions}}', $delivery_instructions, $mailcontents);
$mailcontents = str_replace('{{arrangement_name}}', $flower_name[0], $mailcontents);
$mailcontents = str_replace('{{image_link}}', $arrangement_selected."_low.jpg", $mailcontents);


$to = $user_email[0];
$subject = "Order Received";
$message = $mailcontents;
$from = "FiveMinuteFlowers Sales <sales@fiveminuteflowers.com>";
$headers = "From: " . $from . "\r\n";
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
mail($to,$subject,$message,$headers);

//Send email to sales

$to = "sales@fiveminuteflowers.com";
$subject = "New Order!";
$message = "There is a new order. Please login to the admin panel";
$from = "New Orders <info@fiveminuteflowers.com>";
$headers = "From: " . $from . "\r\n";
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
mail($to,$subject,$message,$headers);

echo "ok";

?>