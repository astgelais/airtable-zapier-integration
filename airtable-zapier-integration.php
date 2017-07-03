<?
if (isset($_GET['ping'])) { echo 'syntax ok'; exit; }

/*
===================================
Modification Log (recent entries):
===================================
2017-04-17   aaronstg - initial version
*/
//phpinfo();
//exit;
set_time_limit(0);

//Exit Script if HTTP_USER_AGENT is not Zapier
if($_SERVER["HTTP_USER_AGENT"] != 'Zapier') {exit;}

//PHP Variable Creation
foreach ($_GET as $key => $val) {
	$$key = $val;
	//echo $key. " => " . $val;
	//echo "<br>";
}

//Email Data
$to = "email@examplesite.com";
$from = $_SERVER[SCRIPT_NAME];
$subject = "PHP Zapier Tool";
$edata = "";

//Functions
function send_mail($subject, $edata, $from, $to){
	$message = 'From: ' . $from . "\t\n" . $edata;
	$email = 'noreply@efilefreeonline.com';
	$headers = 'From: ' . $email . "\t\n" .
            'Reply-To: ' . $email . "\t\n" .
          'X-Mailer: PHP/' . phpversion();
 
	$sendmail = mail ($to, $subject, $message, $headers);
	return $sendmail;
}

function curl_call_to_webhook($url, $postData){
	/*  START - ZAP CALL */
	// Initialize curl
	$curl = curl_init();
	
	$opts = array(
		CURLOPT_URL             => $url,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_CUSTOMREQUEST   => 'POST',
		CURLOPT_POST            => 1,
		CURLOPT_POSTFIELDS      => $postData,
		CURLOPT_HTTPHEADER  => array('Content-Type: application/json','Content-Length: ' . strlen($postData))
	);
	
	// Set curl options
	curl_setopt_array($curl, $opts);
	
	// Get the results
	$result = curl_exec($curl);
	
	// Close resource
	curl_close($curl);
	
	return $result;
	
	/* END - ZAP CALL */
}

//Script Data
$title = "PHP Zapier Tool";

$curl = curl_init();
$timeout=5;

//Main Airtable View
$url = 'https://api.airtable.com/v0/app2AdJfucaKUslbm/Projects/?view=Main%20View&api_key=keyOrZG0cWjeoaA2t';

//Project Airtable View
//$url = 'https://api.airtable.com/v0/app2AdJfucaKUslbm/Projects/reclkIbsyApHFTNuj?api_key=keyOrZG0cWjeoaA2t';

// Initialize curl to get Airtable database information
curl_setopt($curl,CURLOPT_URL,$url); 

$ua_arr = array('Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)','Mozilla/5.0 (iPhone; CPU iPhone OS 10_1_1 like Mac OS X) AppleWebKit/602.2.14 (KHTML, like Gecko) Version/10.0 Mobile/14B100 Safari/602.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; FSL 7.0.5.01003)');
$ref_arr = array('http://www.examplereferrer.com');
$index=rand(0,4);
curl_setopt($curl, CURLOPT_USERAGENT, $ua_arr[$index]); 
$index=rand(0,4);
curl_setopt($curl, CURLOPT_REFERER, $ref_arr[$index]); 
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$timeout);

$response = curl_exec($curl);
curl_close($curl);
if ($response === false) {
	
die('Error '. curl_errno($curl) .': '. curl_error($curl));}

$response_decode = json_decode($response, true);
/* FOR TESTING
echo "<pre>";
print_r($response_decode);
echo "</pre>";
*/

//Work Type Array
$workType = array ('SEO Program','Paid Program','SCOPE','CORE');

$x = 0;

foreach ($response_decode as $records) {
	foreach ($records as $rows) {
		//Determine Inflow Work Type
		$run = false;
		foreach($records[$x]['fields']['Work Type'] as $key => $val) {
			if(!$run) {
				//echo "<br>".$key." ".$val."<br>";
				if(in_array($val,$workType)) { $run = true; break; } else { $run = false; }
			}
		}
		switch ($check) {
			case "budget":
				//Budget Check
				if(isset($records[$x]['fields']['Slack Channel']) &&
				((strtotime($records[$x]['fields']['Budget -Next Date To Discuss']) < strtotime('+1 month')) ||
				!isset($records[$x]['fields']['Budget -Next Date To Discuss']))) {
					
					if($run) {
						if(isset($details)) {
							echo "<pre>";
							print_r($records[$x]);
							echo "</pre>";
						}
						
						//JSON payload
						$postData = json_encode($records[$x]);
						
						if(isset($details)) {
							echo "JSON payload: ".$postData."<br><br>";
						}
						
						//Zapier webhook url
						if(isset($test)) {
							$zapurl = 'https://hooks.zapier.com/hooks/catch/XXXX/XXXX/';
						} else {
							$zapurl = 'https://hooks.zapier.com/hooks/catch/XXXX/XXXX/';
						}
						
						//Curl Call to webhook
						if(isset($update)){
							//$edata .= "\t\n\t\n".$records[$x]['fields']['Slack Channel']."\t\n\t\n";
							$curlCall = curl_call_to_webhook($zapurl, $postData);
						}
						if(isset($details)) {
							echo "webhook result: ".$curlCall;
						}
						//exit;
					}
				}
			break;
			case "warmth":
				//Warmth Check
				if(isset($records[$x]['fields']['Slack Channel']) && 
				((strtotime($records[$x]['fields']['Date Of Last Warmth Gesture']) < strtotime('-6 month')) || 
				!isset($records[$x]['fields']['Date Of Last Warmth Gesture']))){
					
					if($run) {
						if(isset($details)) {
							echo "<pre>";
							print_r($records[$x]);
							echo "</pre>";
						}
						
						//JSON payload
						$postData = json_encode($records[$x]);
						
						if(isset($details)) {
							echo "JSON payload: ".$postData."<br><br>";
						}
						
						//Zapier webhook url enter your custom webhooks here
						if(isset($test)) {
							$zapurl = 'https://hooks.zapier.com/hooks/catch/XXXX/XXXX/';
						} else {
							$zapurl = 'https://hooks.zapier.com/hooks/catch/XXXX/XXXX/';
						}
						
						//Curl Call to webhook
						if(isset($update)){
							//$edata .= "\t\n\t\n".$records[$x]['fields']['Slack Channel']."\t\n\t\n";
							$curlCall = curl_call_to_webhook($zapurl, $postData);
						}
						if(isset($details)) {
							echo "webhook result: ".$curlCall;
						}
						//exit;
					}
				}
			break;
		}
		$x++;
	}
}


//Send Debug Email
$serverVariables = '';
$sessionVariables = '';

foreach($_SERVER as $key => $val) {
	$serverVariables .= "Variable: ".$key." Value:".$val."\t\n";
}

foreach($_SESSION as $key => $val) {
	$sessionVariables .= "Variable: ".$key." Value:".$val."\t\n";
}

$scriptData = "\t\n\t\nServer Variables:\t\n\t\n".$serverVariables."\t\n"."Session Variables:".$sessionVariables."\t\n\t\n";
if(isset($details)) {
	echo $scriptData;
}
$edata .= $scriptData;
$mailsent=send_mail($subject, $edata, $from, $to);

?>