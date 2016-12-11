<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../../wp-load.php';

function ExperimantalGetter(){
	if(isset($_GET["experimantalinput"])){
		echo "<p>".$_GET["experimantalinput"]."</p>";
	}
}
ExperimantalGetter();
?>
