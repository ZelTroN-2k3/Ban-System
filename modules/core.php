<?php
//Getting visitor's real IP Address
$ip      = '';
$ip_type = '';

if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    // When website is behind CloudFlare
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP']; 
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED'];
} elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_FORWARDED'])) {
    $ip = $_SERVER['HTTP_FORWARDED'];
} elseif (isset($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
}

//Getting Browser and Operating System
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $useragent = $_SERVER['HTTP_USER_AGENT'];
} else {
    $useragent = '';
}
include dirname(__FILE__) . '/lib/useragent.class.php';
$useragent_data = UserAgentFactory::analyze($_SERVER['HTTP_USER_AGENT']);

//Getting Visitor Information
if ($ip == "::1") {
    $ip = "127.0.0.1";
}
$ip        = strtok($ip, ',');
//$ip       = str_replace(',', '', $ip);
//$ip       = str_replace(' ', '', $ip);

if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {    
    $ip_type = "v4";
}
else if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) { 
    $ip_type = "v6";
}

if ($ip_type == "v4") {
	$ipnums       = explode(".", $ip);
	@$ip_range    = $ipnums[0] . "." . $ipnums[1] . "." . $ipnums[2];
}
else if ($ip_type == "v6") {
	$ipnums       = explode(":", $ip);
	@$ip_range    = $ipnums[0] . ":" . $ipnums[1] . ":" . $ipnums[2] . ":" . $ipnums[3];
}

// Browser
$browser = $useragent_data->browser['title'];

// Operating System
$os = $useragent_data->os['title'];

// Referrer
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER["HTTP_REFERER"];
} else {
    $referer = '';
}

function bansys_getcache($cache_file)
{
    global $cache_file;
    
    if (file_exists($cache_file ?? '')) {
        
        $current_time = time();
        //$expire_time  = 1 * 60 * 60; // 1 hour
		$expire_time  = 32 * 24 * 60 * 60; // 1 month
        $file_time    = filemtime($cache_file);
        
        if ($current_time - $expire_time < $file_time) {
            return file_get_contents($cache_file);
        } else {
			return 'BANSYS_NoCache';
		}
    } else {
		return 'BANSYS_NoCache';
	}
}
?>