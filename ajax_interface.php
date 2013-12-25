<?php require_once("connection.php");

#Here is all the code that will output back to our HTML

//Get specific flower information

if (isset($_GET["action"]) && $_GET["action"] == "get-flower-info") {
	$arrangement_code = mysql_real_escape_string($_GET['arrangement']);
	$flower_info_query = "SELECT * FROM flower_inventory WHERE arrangement_code = '{$arrangement_code}'";
	$flower_info_process = mysql_query($flower_info_query);
	$flower_info = mysql_fetch_assoc($flower_info_process);
	
	$flower_information = json_encode($flower_info);
	echo $flower_information;
}

//Get array of personalized flower options

if (isset($_POST["getAction"]) && $_POST["getAction"] == "get-personalized-array") {
	//Build query for occasion selected
	
	if ($_POST["personalizedOccasion"] != "") {
		$occasion_addon = "AND occasion = '{$_POST['personalizedOccasion']}'";
	} else {
		$occasion_addon = "";
	}
	
	//Build query for flower type selected
	
	if ($_POST["personalizedFlowerType"] != "") {
		$flower_type_addon = "AND flower_type = '{$_POST['personalizedFlowerType']}'";
	} else {
		$flower_type_addon = "";
	}
	
	//Build query for budget selected
	
	if ($_POST["personalizedBudget"] != "") {
		if ($_POST["personalizedBudget"] == 1) {
			$retail_addon = "AND retail_price BETWEEN '19' AND '500'";
		} else if ($_POST["personalizedBudget"] == 2) {
			$retail_addon = "AND retail_price BETWEEN '51' AND '500'";
		} else if ($_POST["personalizedBudget"] == 3) {
			$retail_addon = "AND retail_price BETWEEN '101' AND '500'";
		} else if ($_POST["personalizedBudget"] == 4) {
			$retail_addon = "AND retail_price BETWEEN '201' AND '500'";
		}
	} else {
		$retail_addon = "";
	}
	
	//Dooooo it
	
	$personalized_query = "SELECT * FROM flower_inventory WHERE 1 = 1 {$occasion_addon} {$flower_type_addon} {$retail_addon} ORDER BY retail_price ASC";
	$personalized_process = mysql_query($personalized_query);
	
	while ($personal = mysql_fetch_assoc($personalized_process)) {
		$personalized_array[] = $personal;
	}
	
	if (isset($personalized_array)) {
		$personalized_output = json_encode($personalized_array);
		echo $personalized_output;
	} else {
		echo "none";
	}
}

?>

<?php

### Get select options for saved billing information ###

if (isset($_POST["action"]) && $_POST["action"] == "read" && $_POST["pageLayout"] == "billing-selects") {

	$user_id = mysql_real_escape_string($_POST["uuid"]);
	
	$saved_billing_query = "SELECT * FROM saved_billing WHERE uuid = '{$user_id}'";
	$saved_billing_process = mysql_query($saved_billing_query);
	
	while ($billing = mysql_fetch_assoc($saved_billing_process)) {
		$billing_array[] = $billing;
	}
	
	if (isset($billing_array)) {
		$billing_output = json_encode($billing_array);
		echo $billing_output;
	} else {
		echo "none";
	}

}

?>

<?php

### Get select options for saved delivery information ###

if (isset($_POST["action"]) && $_POST["action"] == "read" && $_POST["pageLayout"] == "delivery-selects") {

	$user_id = mysql_real_escape_string($_POST["uuid"]);
	
	$saved_delivery_query = "SELECT * FROM saved_delivery WHERE uuid = '{$user_id}'";
	$saved_delivery_process = mysql_query($saved_delivery_query);
	
	while ($delivery = mysql_fetch_assoc($saved_delivery_process)) {
		$delivery_array[] = $delivery;
	}
	
	if (isset($delivery_array)) {
		$delivery_output = json_encode($delivery_array);
		echo $delivery_output;
	} else {
		echo "none";
	}

}

?>