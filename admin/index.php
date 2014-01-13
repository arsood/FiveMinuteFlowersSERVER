<?php session_start(); ?>

<?php 

if (isset($_GET['logout'])) {
	session_destroy();
	$logout_flag = true;
} else {
	$logout_flag = false;
}

if (isset($_GET['error'])) {
	$error_flag = true;
} else {
	$error_flag = false;
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>

<link rel="stylesheet" type="text/css" href="libraries/bootstrap-3.0.0/css/bootstrap.css" />
<!--<link rel="stylesheet" type="text/css" href="libraries/bootstrap-3.0.0/buttons/buttons.css" />-->
<link rel="stylesheet" type="text/css" href="libraries/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="css/helpers.css" />
<link rel="stylesheet" type="text/css" href="css/admin.css" />

</head>

<body>

<nav class="navbar navbar-inverse no-rad">
	<div class="navbar-header">
        <div class="admin-logo">
        	<img src="img/fmf-logo-white.png" />
        </div>
    </div>
</nav>

<div class="container margin-top-30">
	<div class="login-form">
    	<?php if ($logout_flag) { ?>
        <div class="alert alert-success">
        	You have been logged out!
        </div>
        <?php } ?>
        <?php if ($error_flag) { ?>
        <div class="alert alert-danger">
        	There was an error processing your request.
        </div>
        <?php } ?>
        <div class="well">
        	<form method="post" action="php/admin-login.php">
                <div class="login-header">
                    Admin Login
                </div>
                <div class="row margin-top-30">
                    <div class="col-sm-1 txt-center">
                        <i class="icon-user icon-2x"></i>
                    </div>
                    <div class="col-sm-11">
                        <input type="text" name="login-username" class="form-control" placeholder="Username" />
                    </div>
                </div>
                <div class="row margin-top-30">
                    <div class="col-sm-1 txt-center">
                        <i class="icon-unlock-alt icon-2x" style="margin-left:2px;"></i>
                    </div>
                    <div class="col-sm-11">
                        <input type="password" name="login-password" class="form-control" placeholder="Password" />
                    </div>
                </div>
                <div class="margin-top-20 txt-center">
                    <button class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="libraries/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="libraries/bootstrap-3.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>

</body>
</html>
