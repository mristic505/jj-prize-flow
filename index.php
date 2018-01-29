<?php
require_once 'meekrodb.2.3.class.php';
DB::$user = 'root';
DB::$password = 'root';
DB::$dbName = 'test';

date_default_timezone_set('America/New_York');

$uemail = 'mateja.ristic@gmail.com';

$current_date = date("m-d-Y");
$current_time = time();
$prize_availability = true;
$results = DB::query("SELECT * FROM awards WHERE prize_date=%s", $current_date);
$already_won = $results[0]['already_won'];
$prize_time = strtotime($results[0]['prize_time']);
$prize_id = $results[0]['id'];

/**************************************************/
/**** DETERMINE IF PRIZE AVAILABLE FOR THE DAY ****/
/**************************************************/

// If no prize exists in DB or prize already won
if(empty($results) || $already_won > 0) {
	$prize_availability = false;		
}
// if prize exists in DB and not previously won today
else {

	/*******************************************/
	/**** DETERMINE IF IT IS TIME FOR PRIZE ****/
	/*******************************************/

	// If current time is after the prize time =========
	if ($current_time > $prize_time) {
		$prize_availability = true;
	    // mark prize as won ===========
	    DB::update('awards', array('already_won' => 1, 'won_by' => $uemail), "prize_date=%s", $current_date);
	// If current time is before the prize time ========
	} else {
		// ==========
		$prize_availability = false;
		echo "prize not available yet";
	}
}
if (!$prize_availability) echo 'no prize exists in DB or prize already won';
if ($prize_availability) echo 'prize available';