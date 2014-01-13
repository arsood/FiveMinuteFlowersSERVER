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
$delivery_first_name = mysql_real_escape_string($_POST["deliveryFirstName"]);
$delivery_last_name = mysql_real_escape_string($_POST["deliveryLastName"]);
$delivery_address_1 = mysql_real_escape_string($_POST["deliveryAddress1"]);
$delivery_address_2 = mysql_real_escape_string($_POST["deliveryAddress2"]);
$delivery_city = mysql_real_escape_string($_POST["deliveryCity"]);
$delivery_state = mysql_real_escape_string($_POST["deliveryState"]);
$delivery_zipcode = mysql_real_escape_string($_POST["deliveryZipcode"]);
$personal_message = mysql_real_escape_string($_POST["personalMessage"]);

//Make the insert chu chi

$insert_query = "INSERT INTO flower_orders (uuid, billing_first_name, billing_last_name, billing_address_1, billing_address_2, billing_city, billing_state, billing_zipcode, delivery_first_name, delivery_last_name, delivery_address_1, delivery_address_2, delivery_city, delivery_state, delivery_zipcode, payment_token, payment_amount, personal_message, order_status) VALUES ('{$user_id}', '{$billing_first_name}', '{$billing_last_name}', '{$billing_address_1}', '{$billing_address_2}', '{$billing_city}', '{$billing_state}', '{$billing_zipcode}', '{$delivery_first_name}', '{$delivery_last_name}', '{$delivery_address_1}', '{$delivery_address_2}', '{$delivery_city}', '{$delivery_state}', '{$delivery_zipcode}', '{$payment_token}', '{$arrangement_price}', '{$personal_message}', '1')";
mysql_query($insert_query);

if ($_POST["saveBilling"] == "true") {
	$insert_billing_query = "INSERT INTO saved_billing (uuid, billing_first_name, billing_last_name, billing_address_1, billing_address_2, billing_city, billing_state, billing_zipcode) VALUES ('{$user_id}', '{$billing_first_name}', '{$billing_last_name}', '{$billing_address_1}', '{$billing_address_2}', '{$billing_city}', '{$billing_state}', '{$billing_zipcode}')";
	mysql_query($insert_billing_query);
}

if ($_POST["saveDelivery"] == "true") {
	$insert_delivery_query = "INSERT INTO saved_delivery (uuid, delivery_first_name, delivery_last_name, delivery_address_1, delivery_address_2, delivery_city, delivery_state, delivery_zipcode) VALUES ('{$user_id}', '{$delivery_first_name}', '{$delivery_last_name}', '{$delivery_address_1}', '{$delivery_address_2}', '{$delivery_city}', '{$delivery_state}', '{$delivery_zipcode}')";
	mysql_query($insert_delivery_query);
}

echo "ok";

?>