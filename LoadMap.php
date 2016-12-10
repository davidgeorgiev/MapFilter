<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../../wp-load.php';

function LoadMap($timezone,$country,$city){
	global $wpdb;
	$firstid = 0;
	$where = "";
	if($timezone != "null"){
		$where = " WHERE 1=1 ";
		$timezone = " AND timezone=\"".$timezone."\"";
	}else{
		$timezone = "";
	}
	if($country != "null"){
		$where = " WHERE 1=1 ";
		$country = " AND country_name=\"".$country."\"";
	}else{
		$country = "";
	}
	if($city != "null"){
		$where = " WHERE 1=1 ";
		$city = " AND city_name=\"".$city."\"";
	}else{
		$city = "";
	}
	$sql = "SELECT map_point_id ,lat, lng FROM map_points ".$where.$timezone.$country.$city.";";
	$myrows = $wpdb->get_results($sql);
	//echo $sql;
	echo '<div id="map"></div><script>function initMap() {';
		  	foreach($myrows as $key => $row) {
		  		if($firstid == 0){
		  			$firstid = $row->map_point_id;
		  		}
				echo 'var uluru'.$row->map_point_id.' = {lat: '.$row->lat.', lng: '.$row->lng.'};';
			}
	echo 'var map = new google.maps.Map(document.getElementById("map"), {zoom: 1,center: uluru'.$firstid.'});';
		    foreach($myrows as $key => $row) {
				echo 'var marker = new google.maps.Marker({position: uluru'.$row->map_point_id.',map: map});';
			};
		echo '}</script><script async defer src="https://maps.googleapis.com/maps/api/js?key='.GOOGLEAPIKEY.'&callback=initMap"></script>';
	
}
function mainLoadMap(){
	$timezone = "null";
	$country = "null";
	$city = "null";
	if(isset($_GET["timezone"])){
		$timezone = $_GET["timezone"];
	}
	if(isset($_GET["country"])){
		$country = $_GET["country"];
	}
	if(isset($_GET["city"])){
		$city = $_GET["city"];
	}
	LoadMap($timezone,$country,$city);
}

mainLoadMap();

?>
