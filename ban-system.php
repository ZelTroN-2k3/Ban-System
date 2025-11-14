<?php
include "config.php";
include "modules/core.php";

//Checking if the visitor is in the Whitelist
$wquery = mysqli_query($mysqli, "SELECT ip FROM `bansystem_ip-whitelist` WHERE ip='$ip' LIMIT 1");
if (!mysqli_num_rows($wquery)){
    
    //Ban System
    include "modules/ban-system.php";

}
?>