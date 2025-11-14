<?php
require("core.php");
head();

// Delete outdated cache files
$now   = time();

$files = glob("modules/cache" . "/*");
foreach ($files as $file) {
    if (is_file($file)) {
		if (($now - filemtime($file)) >= (1 * 24 * 60 * 60)) { // 1 day
			unlink($file);
		}
    }
}
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div id="content-container">
				
				<section class="content-header">
    			  <h1><i class="fa fa-home"></i> Dashboard</h1>
    			  <ol class="breadcrumb">
   			         <li><a href="dashboard.php"><i class="fa fa-home"></i> Admin Panel</a></li>
    			     <li class="active">Dashboard</li>
    			  </ol>
    			</section>


				<!--Page content-->
				<!--===================================================-->
				<section class="content">
				
				<center>
					<div class="well">
						<h2><span class="fa-stack fa-lg">
							<i class="fa fa-user fa-stack-1x"></i>
							<i class="fa fa-ban fa-stack-2x text-danger"></i>
							</span> Ban System
						</h2>
					</div>
				</center>
				
				<h3 class="text-thin">Statistics</h3>
<?php
$query  = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans`");
$count  = mysqli_num_rows($query);

$query2 = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans-country`");
$count2 = mysqli_num_rows($query2);

$query3 = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans-other`");
$count3 = mysqli_num_rows($query3);

$query4 = mysqli_query($mysqli, "SELECT * FROM `bansystem_ip-whitelist`");
$count4 = mysqli_num_rows($query4);
?>
				   
				   <div class="row">
				   
				        <div class="col-sm-6 col-lg-3">
                            <div class="small-box bg-red">
                               <div class="inner">
                                   <h3><?php
echo $count;
?></h3>
                                   <p>IP Bans</p>
                               </div>
                               <div class="icon">
                                   <i class="fa fa-user"></i>
                               </div>
                               <a href="bans-ip.php" class="small-box-footer">View IP Bans <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
					    </div>
						<div class="col-sm-6 col-lg-3">
                            <div class="small-box bg-aqua">
                               <div class="inner">
                                   <h3><?php
echo $count2;
?></h3>
                                   <p>Country Bans</p>
                               </div>
                               <div class="icon">
                                   <i class="fa fa-globe"></i>
                               </div>
                               <a href="bans-country.php" class="small-box-footer">View Country Bans <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
					    </div>
						<div class="col-sm-6 col-lg-3">
                            <div class="small-box bg-green">
                               <div class="inner">
                                   <h3><?php
echo $count3;
?></h3>
                                   <p>Other Bans</p>
                               </div>
                               <div class="icon">
                                   <i class="far fa-flag"></i>
                               </div>
                               <a href="bans-other.php" class="small-box-footer">View Other Bans <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
					    </div>
<?php
if (isset($_SERVER['HTTP_USER_AGENT'])) {
	$api_useragent = $_SERVER['HTTP_USER_AGENT'];
} else {
	$api_useragent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118';
}

$url = 'https://ipapi.co/8.8.8.8/json/';
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
    $gstatus = '<font>Online</font>';
} else {
    $gstatus = '<font>Offline</font>';
}
?>
                        <div class="col-sm-6 col-lg-3">
                            <div class="small-box bg-yellow">
                               <div class="inner">
                                   <h3><?php
echo $gstatus;
?></h3>
                                   <p>GeoIP API Status</p>
                               </div>
                               <div class="icon">
                                   <i class="fa fa-server"></i>
                               </div>
							   <a href="#" class="small-box-footer"></a>
                            </div>
					    </div>

				   </div>
				   
				   <div class="row">
				   
				    <div class="col-md-6">
				    <div class="box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-clock-o"></i> Time</h3>
						</div>
						<div class="box-body">
                            <center><h4 id="clock"></h4></center>
                        </div>
                    </div>
                    </div>
					
					<div class="col-md-6">
				    <div class="box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-calendar-o"></i> Date</h3>
						</div>
						<div class="box-body">
                            <center><div class="datepicker-here" data-language='en'></div></center>
                        </div>
                    </div>
                    </div>
					
				</div>
                    
				</div>
				<!--===================================================-->
				<!--End page content-->


			</div>
			<!--===================================================-->
			<!--END CONTENT CONTAINER-->

<!--Time-->
<script>
	function startTime() {
	   var today = new Date();
	   var h = today.getHours();
 	   var m = today.getMinutes();
 	   var s = today.getSeconds();
 	   m = checkTime(m);
 	   s = checkTime(s);
 	   document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
 	   var t = setTimeout(startTime, 500);
	}
	
	function checkTime(i) {
 	   if (i < 10) {i = "0" + i};
 	   return i;
	}
</script>
<?php
footer();
?>