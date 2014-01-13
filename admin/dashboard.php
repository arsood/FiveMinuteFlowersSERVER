<?php session_start(); ob_start(); include_once("../api/connection.php");

//Users only people

if (!isset($_SESSION['admin-login'])) {
	header("Location:login.php");
	exit;
}

//Call em' up

include_once("php/admin-calls.php");

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>FMF Admin Panel</title>

<link rel="stylesheet" type="text/css" href="libraries/bootstrap-3.0.0/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="libraries/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="css/helpers.css" />
<link rel="stylesheet" type="text/css" href="css/admin.css" />

</head>

<body>

<nav class="navbar navbar-inverse no-rad">
	<div class="navbar-header">
    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".admin-navbar">
        	<span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="admin-logo">
        	<img src="img/fmf-logo-white.png" />
        </div>
    </div>
    <div class="collapse navbar-collapse admin-navbar">
    	<ul class="nav navbar-nav navbar-right">
        	<li><button class="btn btn-view-over-the-town-3" style="margin-top:7px;" onClick="window.location='index.php?logout=true'">Logout</button></li>
        </ul>
    </div>
</nav>

<div class="container margin-top-30 margin-bottom-20">
	<h2 class="txt-center">Pending Orders</h2>
    <table class="table-responsive admin-table margin-top-20">
    	<thead>
        	<tr>
            	<td>Name</td>
                <td>Email</td>
                <td>Order Amount</td>
                <td>Action</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        	<!--Pending Order-->
            <?php while ($pending_orders = mysql_fetch_array($pending_orders_process)) { ?>
            
            <?php
				$user_id = $pending_orders["uuid"];
				$email_query = "SELECT email FROM user_info WHERE uuid = '{$user_id}'";
				$email_process = mysql_query($email_query);
				$user_email = mysql_fetch_array($email_process);
			?>
            
            <tr id="summary<?php echo $pending_orders['id']; ?>">
            	<td><?php echo $pending_orders['billing_first_name']." ".$pending_orders['billing_last_name']; ?></td>
                <td><?php echo $user_email[0]; ?></td>
                <td><?php echo "$".$pending_orders['payment_amount']; ?></td>
                <td>
                	<button class="btn btn-success order-accept">Accept</button>
                    <button class="btn btn-danger margin-left-5 order-deny">Deny</button>
                </td>
                <td><i class="icon-arrow-down summary-arrow"></i></td>
            </tr>
            <tr id="detailed<?php echo $pending_orders['id']; ?>" style="display:none;">
            	<td colspan="5">
                	<table class="admin-detailed-table margin-top-10">
                    	<thead>
                        	<tr>
                            	<td>Name</td>
                                <td>Address 1</td>
                                <td>Address 2</td>
                                <td>City</td>
                                <td>State</td>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                            	<td><?php echo $pending_orders['delivery_first_name']." ".$pending_orders['delivery_last_name']; ?></td>
                                <td><?php echo $pending_orders['delivery_address_1']; ?></td>
                                <td><?php echo $pending_orders['delivery_address_2']; ?></td>
                                <td><?php echo $pending_orders['delivery_city']; ?></td>
                                <td><?php echo $pending_orders['delivery_state']; ?></td>
                            </tr>
                        </tbody>
                        <thead>
                        	<tr>
                            	<td>Zipcode</td>
                                <td>Message</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                            	<td><?php echo $pending_orders['delivery_zipcode']; ?></td>
                                <td><?php echo $pending_orders['personal_message']; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php } ?>
            <!--/Pending Order-->
        </tbody>
    </table>
    
    <h2 class="txt-center" style="margin-top:50px;">Processed Orders</h2>
    <table class="table-responsive admin-table margin-top-20">
    	<thead>
        	<tr>
            	<td>Name</td>
                <td>Email</td>
                <td>Order Amount</td>
                <td>Status</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        	<!--Pending Order-->
            <?php while ($processed_orders = mysql_fetch_array($processed_orders_process)) { ?>
            
            <?php
				$user_id = $processed_orders["uuid"];
				$email_query_processed = "SELECT email FROM user_info WHERE uuid = '{$user_id}'";
				$email_process_processed = mysql_query($email_query_processed);
				$user_email_processed = mysql_fetch_array($email_process_processed);
			?>
            
            <tr id="summary<?php echo $processed_orders['id']; ?>">
            	<td><?php echo $processed_orders['billing_first_name']." ".$processed_orders['billing_last_name']; ?></td>
                <td><?php echo $user_email_processed[0]; ?></td>
                <td><?php echo "$".$processed_orders['payment_amount']; ?></td>
                <td>
					<?php 
						if ($processed_orders["order_status"] == 2) {
							echo "Accepted";
						} else {
							echo "Denied";
						}
					?>
                </td>
                <td><i class="icon-arrow-down summary-arrow"></i></td>
            </tr>
            <tr id="detailed<?php echo $processed_orders['id']; ?>" style="display:none;">
            	<td colspan="5">
                	<table class="admin-detailed-table margin-top-10">
                    	<thead>
                        	<tr>
                            	<td>Name</td>
                                <td>Address 1</td>
                                <td>Address 2</td>
                                <td>City</td>
                                <td>State</td>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                            	<td><?php echo $processed_orders['delivery_first_name']." ".$pending_orders['delivery_last_name']; ?></td>
                                <td><?php echo $processed_orders['delivery_address_1']; ?></td>
                                <td><?php echo $processed_orders['delivery_address_2']; ?></td>
                                <td><?php echo $processed_orders['delivery_city']; ?></td>
                                <td><?php echo $processed_orders['delivery_state']; ?></td>
                            </tr>
                        </tbody>
                        <thead>
                        	<tr>
                            	<td>Zipcode</td>
                                <td>Message</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                            	<td><?php echo $processed_orders['delivery_zipcode']; ?></td>
                                <td><?php echo $processed_orders['personal_message']; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php } ?>
            <!--/Pending Order-->
        </tbody>
    </table>
</div>

<!--Accept Modal-->
<div class="modal fade accept-modal" id="modal-accept" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Accept Confirmation</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to accept this order and charge the customer?
			</div>
			<div class="modal-footer">
				<button id="accept-order-button" type="button" class="btn btn-success">Ok</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <input id="accept-hidden" type="hidden" name="order-number" value="" />
			</div>
		</div>
	</div>
</div>
<!--/Accept Modal-->

<!--Deny Modal-->
<div class="modal fade deny-modal" id="modal-deny" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Deny Confirmation</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to deny this order and refund the customer?
			</div>
			<div class="modal-footer">
				<button id="deny-order-button" type="button" class="btn btn-success">Ok</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <input id="deny-hidden" type="hidden" name="order-number" value="" />
			</div>
		</div>
	</div>
</div>
<!--/Deny Modal-->

<!--Add Shipping Modal-->
<div class="modal fade shipping-modal" id="modal-shipping" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Shipping Confirmation</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to confirm this as the shipping tracking code?
			</div>
			<div class="modal-footer">
				<button id="ship-button" type="button" class="btn btn-success">Ok</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <input id="shipping-hidden" type="hidden" name="order-number" value="" />
			</div>
		</div>
	</div>
</div>
<!--/Add Shipping Modal-->

<script type="text/javascript" src="libraries/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="libraries/bootstrap-3.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>

</body>
</html>

<?php ob_flush(); ?>