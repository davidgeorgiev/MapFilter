<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../../wp-load.php';

function NumOfTimezones(){
	global $wpdb;
	$sql = "SELECT DISTINCT timezone FROM map_points;";
	$myresults = $wpdb->get_results($sql);
	echo count($myresults);
	
}
function NumOfCountries(){
	global $wpdb;
	$where = "";
	$timezone = "";
	global $wpdb;
	if(isset($_GET["timezone"])){
		if($_GET["timezone"]!="null"){
			$where = " WHERE 1=1 ";
			$timezone = "AND timezone=\"".$_GET["timezone"]."\"";
		}
	}
	
	$sql = "SELECT DISTINCT country_name FROM map_points".$where.$timezone.";";
	$myresults = $wpdb->get_results($sql);
	echo count($myresults);
	
}
function NumOfCities(){
	$where = "";
	$country = "";
	$timezone = "";
	global $wpdb;
	if(isset($_GET["country"])){
		if($_GET["country"]!="null"){
			$where = " WHERE 1=1 ";
			$country = "AND country_name=\"".$_GET["country"]."\"";
		}
	}
	if(isset($_GET["timezone"])){
		if($_GET["timezone"]!="null"){
			$where = " WHERE 1=1 ";
			$timezone = "AND timezone=\"".$_GET["timezone"]."\"";
		}
	}
	
	$sql = "SELECT DISTINCT city_name FROM map_points".$where.$country.$timezone.";";
	$myresults = $wpdb->get_results($sql);
	echo count($myresults);
}
if(isset($_GET["field"])){
	if($_GET["field"]=="timezones"){
		NumOfTimezones();
	}else if($_GET["field"]=="countries"){
		NumOfCountries();
	}else if($_GET["field"]=="cities"){
		NumOfCities();
	}
}

?>
