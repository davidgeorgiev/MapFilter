<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../../wp-load.php';

function ShowAllPoints(){
	global $wpdb;
	$sql = "Select lat FROM map_points;";
	$myresults = $wpdb->get_results($sql);
	echo '<p>All points in database are: '.count($myresults).'</p>';
	
}
ShowAllPoints();

?>
