<?php
require("core.php");
head();

if (isset($_GET['ip'])) {
    $ip = $_GET["ip"];
    
    if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
        echo '<meta http-equiv="refresh" content="0; url=dashboard.php">';
        exit();
    }
    
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$api_useragent = $_SERVER['HTTP_USER_AGENT'];
	} else {
		$api_useragent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131';
	}
	
    $url = 'https://ipapi.co/' . $ip . '/json/';
    $ch  = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_USERAGENT, $api_useragent);
    curl_setopt($ch, CURLOPT_REFERER, "https://ipapi.co");
    $ipcontent = curl_exec($ch);
    curl_close($ch);
    
    $ip_data = @json_decode($ipcontent);
    if ($ip_data && !isset($ip_data->{'error'})) {
        $country     = $ip_data->{'country_name'};
        $countrycode = $ip_data->{'country_code'};
        $region      = $ip_data->{'region'};
        $city        = $ip_data->{'city'};
        $latitude    = $ip_data->{'latitude'};
        $longitude   = $ip_data->{'longitude'};
        $isp         = $ip_data->{'org'};
    } else {
        $country     = "Unknown";
        $countrycode = "XX";
        $region      = "Unknown";
        $city        = "Unknown";
        $latitude    = "0";
        $longitude   = "0";
        $isp         = "Unknown";
    }
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div id="content-container">
				
				<section class="content-header">
    			  <h1><i class="fa fa-search"></i> IP Lookup</h1>
    			  <ol class="breadcrumb">
   			         <li><a href="dashboard.php"><i class="fa fa-home"></i> Admin Panel</a></li>
    			     <li class="active">IP Lookup</li>
    			  </ol>
    			</section>


				<!--Page content-->
				<!--===================================================-->
				<section class="content">

                <div class="row">
				<div class="col-md-12">
                    
                <div class="box box-primary">
						<div class="box-header">
                            <h3 class="box-title">IP Details - <?php
    echo $ip;
?></h3>
						</div>
						<div class="box-body">

						                <div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">
                                                         <i class="fa fa-map"></i> Country
                                                    </label>
                                                    <div class="input-group mar-btm">
											            <span class="input-group-addon">
                                                            <img src="assets/plugins/flags/blank.png" class="flag flag-<?php
    echo strtolower($countrycode);
?>" alt="<?php
    echo $country;
?>" />
                                                        </span>
                                                        <input type="text" class="form-control" value="<?php
    echo $country;
?>" readonly>
                                                    </div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">
                                                         <i class="fa fa-map-pin"></i> Region
                                                    </label>
													<input type="text" class="form-control" value="<?php
    echo $region;
?>" readonly>
												</div>
											</div>
										</div>
                                        <div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">
                                                         <i class="fa fa-map-o"></i> City
                                                    </label>
													<input type="text" class="form-control" value="<?php
    echo $city;
?>" readonly>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">
                                                         <i class="fa fa-cloud"></i> Internet Service Provider
                                                    </label>
													<input type="text" class="form-control" value="<?php
    echo $isp;
?>" readonly>
												</div>
											</div>
										</div>
										
								        <hr>

                                        <label class="control-label">
                                            <i class="fa fa-location-arrow"></i> Possible Location
                                        </label>
									    <center><div id="mapdiv" style="width: 99%; height:450px"></div></center>
						            
                        </div>
                </div> 
				
				<div class="box box-primary">
						<div class="box-header">
                            <h3 class="box-title">Ban Search</h3>
						</div>
						<div class="box-body">
<?php
    $query = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans` WHERE ip = '$ip'");
    
    if (mysqli_num_rows($query) == 0) {
        echo '<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>No results found for this IP Address</strong>
			  </div>';
    } else {
?>
                                <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
						                  <th><i class="fa fa-user"></i> IP Address</th>
										  <th><i class="far fa-calendar-alt"></i> Banned On</th>
										  <th><i class="fa fa-share"></i> Redirect</th>
										  <th><i class="fa fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
        while ($row = mysqli_fetch_assoc($query)) {
            echo '
										<tr>
						                    <td>' . $row['ip'] . '</td>
										    <td>' . $row['date'] . '</td>
										    <td>' . $row['redirect'] . '</td>
											<td>
                                            <a href="bans-ip.php?edit-id=' . $row['id'] . '" class="btn btn-flat btn-primary"><i class="fa fa-pen"></i> Edit</a>
                                            <a href="bans-ip.php?delete-id=' . $row['id'] . '" class="btn btn-flat btn-success"><i class="fa fa-trash"></i> Unban</a>
											</td>
										</tr>
';
        }
?>
									</tbody>
								</table>
<?php
    }
?>
						
                        </div>
                </div>	
				
<?php
@set_time_limit(360);
ini_set('max_execution_time', 300); //300 Seconds = 5 Minutes
ini_set('memory_limit', '512M');
        
$dnsbl_lookup = array(
            "all.s5h.net",
			"b.barracudacentral.org",
			"bl.0spam.org",
			"bl.spamcop.net",
			"blacklist.woody.ch",
			"bogons.cymru.com",
			"combined.abuse.ch",
			"db.wpbl.info",
			"dnsbl-1.uceprotect.net",
			"dnsbl-2.uceprotect.net",
			"dnsbl-3.uceprotect.net",
			"dnsbl.dronebl.org",
			"drone.abuse.ch",
			"duinv.aupads.org",
			"dyna.spamrats.com",
			"ips.backscatterer.org",
			"ix.dnsbl.manitu.net",
			"korea.services.net",
			"noptr.spamrats.com",
			"orvedb.aupads.org",
			"proxy.bl.gweep.ca",
			"psbl.surriel.com",
			"rbl.0spam.org",
			"relays.bl.gweep.ca",
			"relays.nether.net",
			"singular.ttk.pte.hu",
			"spam.abuse.ch",
			"spam.dnsbl.anonmails.de",
			"spam.spamrats.com",
			"spambot.bls.digibase.ca",
			"spamrbl.imp.ch",
			"spamsources.fabel.dk",
			"ubl.lashback.com",
			"ubl.unsubscore.com",
			"virus.rbl.jp",
			"wormrbl.imp.ch",
			"z.mailspike.net"
);
        
$AllCount = count($dnsbl_lookup);
$BadCount = 0;
        
$reverse_ip = implode(".", array_reverse(explode(".", $ip)));
        
echo '<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Blacklist Check</b></h3>
			</div>
			<div class="box-body">';
        
echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th><i class="fa fa-database"></i> DNSBL</th>
        <th><i class="fa fa-cogs"></i> Reverse IP</th>
        <th><i class="fa fa-info-circle"></i> Status</th>
      </tr>
    </thead>
    <tbody>';
        
foreach ($dnsbl_lookup as $host) {
    echo '<tr><td>' . $host . '</td><td>' . $reverse_ip . '.' . $host . '</td>';
    if (@checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
        echo '<td><font class="label bg-red" style="font-size: 13px;"><i class="fa fa-times-circle"></i> Listed</font></td></tr>';
        $BadCount++;
    } else {
        echo '<td><font class="label bg-green" style="font-size: 13px;"><i class="fa fa-check-circle"></i> Not Listed</font></td></tr>';
    }
}
        
echo '</tbody>
    </table><br />';
        
echo "This IP Address is listed in " . $BadCount . " blacklists of " . $AllCount . " total<br/>";
echo '</div></div></div>';
?>

                </div>
                    
				</div>
                    
				</div>
				<!--===================================================-->
				<!--End page content-->


			</div>
			<!--===================================================-->
			<!--END CONTENT CONTAINER-->

<script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat(<?php
    echo $longitude;
?>,<?php
    echo $latitude;
?>)
          .transform(
            new OpenLayers.Projection("EPSG:4326"),
            map.getProjectionObject()
          );
          
    var zoom=18;
    var markers = new OpenLayers.Layer.Markers( "Markers" );
	
    map.addLayer(markers);
    markers.addMarker(new OpenLayers.Marker(lonLat));
    map.setCenter (lonLat, zoom);
</script>
<?php
    footer();
} else {
    echo '<meta http-equiv="refresh" content="0; url=dashboard.php">';
    exit();
}
?>