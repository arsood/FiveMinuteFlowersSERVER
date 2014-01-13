<?php session_start(); ob_start();

if (isset($_POST['login-username']) && isset($_POST['login-password'])) {
	if ($_POST['login-username'] == "admin" && $_POST['login-password'] == "FMFadmin") {
		$_SESSION['admin-login'] = true;
		header("Location:../dashboard.php");
		exit;
	} else {
		header("Location:../index.php?error=true");
		exit;
	}
} else {
	header("Location:../index.php?error=true");
	exit;
}

ob_flush(); ?>