<?php
require_once('Controller/Controller.php');
class LogoutController extends Controller {
	
	function __construct() {
		if(!session_id()) session_start();
		session_unset();
		session_destroy();
		$_SESSION['log'] = false;

		header('Location: index.php?controller=connexion');
		exit;
	}
}

$logout = new LogoutController();