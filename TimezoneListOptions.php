<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'wp-load.php';

function TimezoneListOptions(){
	echo '<option selected="selected" value="0" disabled>Select a timezone</option>';
	global $wpdb;
	$sql = "SELECT DISTINCT timezone FROM map_points ORDER BY timezone ASC;";
	$myresults = $wpdb->get_results($sql);
	foreach($myresults as $key => $row) {
		echo '<option value="'.$row->timezone.'">'.$row->timezone.'</option>';
	}
	
}

?>
