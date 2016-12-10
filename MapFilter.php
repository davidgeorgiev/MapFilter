<?php
/*
Plugin Name: MapFilter
Description: Showing points on google maps. Filtring them by queries
Author: David Georgiev
Version: 0.1
*/

define("GOOGLEAPIKEY", "your google api here");
error_reporting(E_ALL);
ini_set('display_errors', 1);

function reset_tables(){
	global $wpdb;
	$myrows = $wpdb->get_results("DROP TABLE map_points;");
	$myrows = $wpdb->get_results("DROP TABLE map_points_last;");
	$myrows = $wpdb->get_results("CREATE TABLE map_points(map_point_id int,country_name varchar(64),city_name varchar(64),timezone varchar(64),lat float,lng float);");
	$myrows = $wpdb->get_results("CREATE TABLE map_points_last(last_max_counter int,last_i int, last_j int);");
	$myrows = $wpdb->get_results("INSERT INTO map_points_last (last_max_counter ,last_i, last_j) VALUES(1,1,1);");
}

function get_lat_lng_from_nekudo_please($firstdigit,$seconddigit){
	$country = 0;
	$latitude = 0;
	$longitude = 0;
	$timezone = 0;
	$city = 0;
	
	$server_error = 0;
	$index = 0;
	$index2 = 0;
	$url = 'http://geoip.nekudo.com/api/'.$firstdigit.'.'.$seconddigit.'.1.1';
	$jsondata = file_get_contents($url);
	$myarray = (json_decode($jsondata, true));
	
	
	if (is_array($myarray) || is_object($myarray)){
		foreach($myarray as $key => $row1) {
			if (is_array($row1) || is_object($row1)){
				foreach($row1 as $key => $row2) {
					$index++;
					if($index == 1){
						$country = $row2;
					}
					if($index == 4){
						$latitude = $row2;
					}
					if($index == 5){
						$longitude = $row2;
					}
					if($index == 6){
						$timezone = $row2;
					}
					//echo '<p>'.$index.' '.$row2.'</p>';
				}
			}else{
				$index2++;
				if($index2 == 1){
					$city = $row1;
				}
				//echo "<p>".$row1."</p>";
			}
		}
	}
	if($latitude&&$longitude){
		if(!$city){
			$city = "_unknown_";
		}
		if(!$country){
			$country = "_unknown_";
		}
		if(!$timezone){
			$timezone = "_unknown_";
		}
		return array($latitude,$longitude,$country,$city,$timezone);
	}else{
		return -1;
	}
}

function add_points_to_db($maxnum){
	global $wpdb;
	$counter = 0;
	$myrows = $wpdb->get_results("SELECT last_max_counter, last_i, last_j FROM map_points_last;");
	foreach($myrows as $key => $row) {
		$counter = $row->last_max_counter;
		$i = $row->last_i;
		$j = $row->last_j;
	}
	//$myrows = $wpdb->get_results("TRUNCATE TABLE map_points;");
	while($i <= $maxnum){
		while($j <= $maxnum){
			$lat_and_lng = get_lat_lng_from_nekudo_please($i,$j);
			if($lat_and_lng!=-1){
				$lat = $lat_and_lng[0];
				$lng = $lat_and_lng[1];
				$country = $lat_and_lng[2];
				$city = $lat_and_lng[3];
				$timezone = $lat_and_lng[4];
				//echo '<div><h1>Point: '.$counter.'</h1>';
				//echo '<p>'.$country.'</p>';
				//echo '<p>'.$city.'</p>';
				//echo '<p>'.$timezone.'</p>';
				//echo '<p>'.$lat.'</p>';
				//echo '<p>'.$lng.'</p>';
				//echo '</div>';
				$myrows = $wpdb->get_results("INSERT INTO map_points (map_point_id,country_name,city_name,timezone,lat,lng) VALUES(".$counter.",\"".$country."\",\"".$city."\",\"".$timezone."\",".$lat.",".$lng.")");
				$myrows = $wpdb->get_results("INSERT INTO map_points_last (last_max_counter ,last_i, last_j) VALUES(".$counter.",".$i.",".$j.");");
				$counter++;
			}
			$j++;
		}
		$j=1;
		$i++;
	}
}
function show_the_user_interface(){
	require 'ListOptions.php';
	echo "<style>#map{height: 400px;width: 100%;}</style>";
	echo '<link rel="stylesheet" href="/wp-content/plugins/MapFilter/chosen_v1.6.2/chosen.css">';
	echo '<script src="/wp-content/plugins/MapFilter/jquery.min.js"></script>';
	echo '<script src="/wp-content/plugins/MapFilter/chosen_v1.6.2/chosen.jquery.js"></script>';
	echo '<div id="timezone_div"><select id="timezone" data-placeholder = "Select a timezone" class="chosen1">';
	TimezoneListOptions();
	echo '</select></div>';
	echo '<div id="country_div"><select id="country" data-placeholder = "Select a country" class="chosen2">';
	CountryListOptions();
	echo '</select></div>';
	echo '<div id="city_div"><select id="city" data-placeholder = "Select a city" class="chosen3">';
	CityListOptions();
	echo '</select></div>';
	echo '<button type="submit" id="RefreshMapButton">Filter</button>';
	
	echo '<div id="mymaphere"></div>';
	
	echo "<script>";
	echo "$(document).ready(function(){";
	echo '$(".chosen1").chosen({width: "50%",allow_single_deselect: true,no_results_text: "Timezone not found!"});';
	echo '$(".chosen1").val("").trigger("chosen:updated");';
	echo '$(".chosen2").chosen({width: "50%",allow_single_deselect: true,no_results_text: "Country not found!"});';
	echo '$(".chosen2").val("").trigger("chosen:updated");';
	echo '$(".chosen3").chosen({width: "50%",allow_single_deselect: true,no_results_text: "City not found!"});';
	echo '$(".chosen3").val("").trigger("chosen:updated");';
	//echo '$(".chosen1").change(function(){$("#country_div").load("/wp-content/plugins/MapFilter/ReloadField.php?field=country&timezone=" + $("#timezone").val());';
	echo '$("#RefreshMapButton").click(function(){';
	echo '$("#mymaphere").load("/wp-content/plugins/MapFilter/LoadMap.php?timezone="+$("#timezone").val()+"&country="+$("#country").val() + "&city="+$("#city").val());';
	echo '});';
	
	echo '$("#timezone_div").change(function(){';
	echo '$("#country").load("/wp-content/plugins/MapFilter/ReloadField.php?field=country&timezone="+$("#timezone").val());';
	echo '$("#city").load("/wp-content/plugins/MapFilter/ReloadField.php?field=city&timezone="+$("#timezone").val()+"&country="+$("#country").val());';
	echo '});';
	
	echo '$("#country_div").change(function(){';
	echo '$("#city").load("/wp-content/plugins/MapFilter/ReloadField.php?field=city&timezone="+$("#timezone").val()+"&country="+$("#country").val());';
	echo '});';
	
	
	echo "});";
	echo "</script>";
	
}


function mainGoogleMapDavidsPlugin($reset,$search,$show,$maxip){
	if($reset){
		reset_tables();
	}
	if($search){
		add_points_to_db($maxip);
	}
	if($show){
		show_the_user_interface();
	}
}

?>