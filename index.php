<?php
session_start();
$_controller = isset($_GET['controller']) ? $_GET['controller'] : 'register';

if ((!isset($_SESSION['log']) || $_SESSION['log'] == false) && isset($_GET['controller'])) {
	if ($_GET['controller'] == 'profile' || $_GET['controller'] == 'home' || $_GET['controller'] == 'privateConversation') {
		$_controller = 'connexion';
	}
}

$controller_name = ucfirst($_controller) . "Controller";

if (file_exists('Controller/'.$controller_name.'.php')) {
	require_once 'Controller/'.$controller_name.'.php';

	if (file_exists('View/'.$_controller.'.php')) {
		require_once 'View/'.$_controller.'.php';
	}else{
		die("View doesn't exist.");
	}
}else{
	die("Controller doesn't exist.");
}