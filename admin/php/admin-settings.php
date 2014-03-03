<?php include_once("../../api/connection.php");

### Change pricing values ###

if (isset($_POST["method"]) && $_POST["method"] == "write" && $_POST["action"] == "change-price") {
	if ($_POST["changeDirection"] == "up") {
		$change_num = mysql_real_escape_string($_POST["changeNum"]);
		$change_multiplier = round(1 + ($change_num / 100), 2);
		$change_query = "UPDATE flower_inventory SET retail_price = ROUND((retail_price * {$change_multiplier}), 2)";
		mysql_query($change_multiplier);
		
		echo "ok";
	}
	
	if ($_POST["changeDirection"] == "down") {
		$change_num = mysql_real_escape_string($_POST["changeNum"]);
		
		$price_query = "SELECT arrangement_code, retail_price FROM flower_inventory";
		$price_process = mysql_query($price_query);
		
		while ($prices = mysql_fetch_array($price_process)) {
			$down_amount = $prices[1] * ($change_num / 100);
			$new_amount = round($prices[1] - $down_amount, 2);
			$change_query = "UPDATE flower_inventory SET retail_price = '{$new_amount}' WHERE arrangement_code = '{$prices[0]}'";
		}
		
		echo "ok";
	}
}

?>