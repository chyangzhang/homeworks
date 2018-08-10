<?php
/* PHP Document 
* Chenyang Zhang CSCI 571 homework8
*/

	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
	error_reporting(E_ALL^E_NOTICE);
	
	$apikey = "0ba00a02ec0b4eaf842c420b30266251";
	//$searchurl = "http://congress.api.sunlightfoundation.com/";
	$searchurl = "http://104.198.0.197:8080/";
	if(isset($_POST["keyword"])) {
		$keyword = $_POST["keyword"];
	}
	if(isset($_GET["keyword"])) {
		$keyword = $_GET["keyword"];
	}

	$searchurl .= "{$keyword}?apikey={$apikey}";

	if(isset($_POST["bio_id"]))
		$bio_id = $_POST["bio_id"];
	if(isset($_GET["bio_id"]))
		$bio_id = $_GET["bio_id"];

	switch ($keyword) {
		case 'legislators':
			if($bio_id != null){
				$searchurl .="&bioguide_id={$bio_id}&all_legislators=true";
			}
			else {
				$order = "state__asc,last_name__asc";
				$searchurl .="&per_page=all&order={$order}";
			}
			break;
		
		case 'bills':
			if($bio_id != null){
				$searchurl .="&sponsor_id={$bio_id}&per_page=5";
			}
			else {
				if(isset($_POST["bills"])) {
					$bills = $_POST["bills"];
				}
				if(isset($_GET["bills"])) {
					$bills = $_GET["bills"];
				}
				switch($bills) {
					case 'active':
						$searchurl .= "&history.active=true&order=introduced_on";
						break;
					case 'new':
						$searchurl .= "&history.active=false&order=introduced_on";
						break;
					default:
						$searchurl .= "&history.active=true&order=introduced_on";
				}
				$searchurl .="&per_page=50";
			}
			break;
		case 'committees':
			if($bio_id != null){
				$searchurl .="&member_ids={$bio_id}&per_page=5";
			}
			else {
				if(isset($_POST["committees"])) {
					$committees = $_POST["committees"];
				}
				if(isset($_GET["committees"])) {
					$committees = $_GET["committees"];
				}
				$searchurl .= "&chamber={$committees}&per_page=all&order=committee_id";
			}
			break;
	}
	$result = file_get_contents($searchurl);
	echo json_encode($result);
?>