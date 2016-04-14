<?php 

header('Content-Type: text/html; charset=utf-8');

$content_mail = file_get_contents("https://libre.lugons.org/wiki/doku.php?id=start&do=profile", false, $context);
//echo $content;

$redovi_mail = explode("\n", $content_mail);

if (strpos($content_mail, "<title>Update Profile - LiBRE! Časopis - REDAKCIJA</title>")) {
	$loginuspeh = true;} else {$loginuspeh = false;}

for ($i = 0; $i < count($redovi_mail); $i++) {
	
	if (strpos($redovi_mail[$i], "E-Mail") !== false) {
		//echo $redovi_mail[$i];
		$start = strpos($redovi_mail[$i], "value=") + 7 ;
		$end = strpos($redovi_mail[$i], "size", $start);
		$length = $end - $start;
		$mejl = substr($redovi_mail[$i], $start, $length - 2);
		//echo $mejl;
		}
	}


?>
