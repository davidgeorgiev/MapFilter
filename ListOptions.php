<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_GET["jquery_refresh_timezone"])){
	require '../../../wp-load.php';
	TimezoneListOptions();
	echo '<script>$(".chosen1").val("").trigger("chosen:updated");</script>';
}else{
	require 'wp-load.php';
}

function TimezoneListOptions(){
	echo '<option selected="selected" value="null">Select a timezone</option>';
	global $wpdb;
	$sql = "SELECT DISTINCT timezone FROM map_points ORDER BY timezone ASC;";
	$myresults = $wpdb->get_results($sql);
	foreach($myresults as $key => $row) {
		echo '<option value="'.$row->timezone.'">'.$row->timezone.'</option>';
	}
	
}
function CountryListOptions(){
	echo '<option selected="selected" value="null">Select a country</option>';
	global $wpdb;
	$sql = "SELECT DISTINCT country_name FROM map_points ORDER BY country_name ASC;";
	$myresults = $wpdb->get_results($sql);
	foreach($myresults as $key => $row) {
		echo '<option value="'.$row->country_name.'">'.$row->country_name.'</option>';
	}
	
}
function CityListOptions(){
	echo '<option selected="selected" value="null">Select a city</option>';
	global $wpdb;
	$sql = "SELECT DISTINCT city_name FROM map_points ORDER BY city_name ASC;";
	$myresults = $wpdb->get_results($sql);
	foreach($myresults as $key => $row) {
		echo '<option value="'.$row->city_name.'">'.$row->city_name.'</option>';
	}
	
}

?>
