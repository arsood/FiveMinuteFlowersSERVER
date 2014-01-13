<?php

//Get pending orders

$pending_orders_query = "SELECT * FROM flower_orders WHERE order_status = '1' ORDER BY id DESC";
$pending_orders_process = mysql_query($pending_orders_query);


//Get processed orders

$processed_orders_query = "SELECT * FROM flower_orders WHERE order_status = '2' OR order_status = '3' ORDER BY id DESC";
$processed_orders_process = mysql_query($processed_orders_query);

?>