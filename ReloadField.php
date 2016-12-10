<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../../wp-load.php';

function CountryReload(){
	//echo '<select id="country" data-placeholder = "Select a country" class="chosen2">';
	echo '<option selected="selected" value="null">Select a country</option>';
	global $wpdb;
	$timezone = "";
	if(isset($_GET["timezone"])){
		if($_GET["timezone"]!="null"){
			$timezone = "WHERE timezone=\"".urldecode($_GET["timezone"])."\"";
		}
	}
	
	$sql = "SELECT DISTINCT country_name FROM map_points ".$timezone." ORDER BY country_name ASC;";
	$myresults = $wpdb->get_results($sql);
	foreach($myresults as $key => $row) {
		echo '<option value="'.$row->country_name.'">'.$row->country_name.'</option>';
	}
	//echo '</select>';
	echo '<script>$(".chosen2").val("").trigger("chosen:updated");$(".chosen3").val("").trigger("chosen:updated");</script>';
	
}
function CityReload(){
	//echo '<select id="city" data-placeholder = "Select a city" class="chosen3">';
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
	echo '<option selected="selected" value="null">Select a city</option>';
	$sql = "SELECT DISTINCT city_name FROM map_points ".$where.$country.$timezone." ORDER BY city_name ASC;";
	$myresults = $wpdb->get_results($sql);
	foreach($myresults as $key => $row) {
		echo '<option value="'.$row->city_name.'">'.$row->city_name.'</option>';
	}
	//echo '</select>';
	echo '<script>$(".chosen3").val("").trigger("chosen:updated");</script>';
}
if(isset($_GET["field"])){
	if($_GET["field"]=="country"){
		CountryReload();
	}else if($_GET["field"]=="city"){
		CityReload();
	}
}

?>
