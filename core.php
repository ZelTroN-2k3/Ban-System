<?php
$configfile = 'config.php';
if (!file_exists($configfile)) {
    echo '<meta http-equiv="refresh" content="0; url=install" />';
    exit();
}

require 'config.php';

if(!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['sec-username'])) {
    $uname = $_SESSION['sec-username'];
    if ($uname != $settings['username']) {
        echo '<meta http-equiv="refresh" content="0; url=index.php" />';
        exit;
    }
} else {
    echo '<meta http-equiv="refresh" content="0; url=index.php" />';
    exit;
}

$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

function head()
{
    include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <link rel="shortcut icon" href="assets/img/favicon.png">
    <title>Ban System &rsaquo; Admin Panel</title>

    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Bootstrap Stylesheet-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/skin-red.min.css">

	<!--Font Awesome-->
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
	
    <!--Switchery-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" rel="stylesheet">
        
<?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'bans-country.php') {
        echo '
    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">';
    }
?>
	
	<!--Stylesheet-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css" rel="stylesheet">

    <!--DataTables-->
    <link href="https://cdn.datatables.net/v/bs/dt-1.13.8/r-2.5.0/datatables.min.css" rel="stylesheet">
	
	<!--Flags-->
    <link href="assets/plugins/flags/flags.css" rel="stylesheet">
	
	<!--DatePicker-->
    <link href="assets/plugins/datepicker/datepicker.min.css" rel="stylesheet">
	
    <!--SCRIPT-->
    <!--=================================================-->

    <!--jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
	integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
	crossorigin="anonymous"></script>

<?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'ip-lookup.php') {
        echo '
	
    <!--Map-->
    <script src="https://openlayers.org/api/OpenLayers.js"></script>';
    }
?>
    
</head>

<body class="hold-transition skin-red sidebar-mini fixed" onload="startTime()">
<div class="wrapper">

  <header class="main-header">

    <a href="dashboard.php" class="logo">
      <span class="logo-mini">Ban<strong>SYS</strong></span>
      <span class="logo-lg">Ban <strong>System</strong></span>
    </a>

    <nav class="navbar navbar-static-top">

      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <i class="fas fa-bars"></i>
		<span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
             <a href="<?php
    echo $settings['site_url'];
?>" target="_blank">
			 <span><i class="fa fa-desktop"></i>&nbsp;&nbsp;Visit Site</span>
			 </a>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<i class="fas fa-user fa-fw"></i> Account
            </a>
            <ul class="dropdown-menu">
              <li class="user-footer">
                <div class="pull-left">
                  <a href="account.php" class="btn btn-default btn-flat"><i class="fa fa-user fa-fw fa-lg"></i> Edit Profile</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">

    <section class="sidebar">

      <form action="ip-lookup.php" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="ip" class="form-control" placeholder="IP Lookup" required>
              <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>

      <ul class="sidebar-menu">
        <li class="header">NAVIGATION</li>
        
        <li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'dashboard.php') {
        echo 'class="active"';
    }
?>>
           <a href="dashboard.php">
            <span><i class="fa fa-home"></i> Dashboard</span>
           </a>
        </li>
          
        <li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'ip-whitelist.php') {
        echo 'class="active"';
    }
?>>
           <a href="ip-whitelist.php">
            <span><i class="far fa-flag"></i> IP Whitelist</span>
           </a>
        </li>
          
        <li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'warning-pages.php') {
        echo 'class="active"';
    }
?>>
           <a href="warning-pages.php">
              <span><i class="far fa-file-alt"></i> Warning Pages</span>
           </a>
        </li>

        <li class="header">BANS</li>
          
<?php
    $bquery1 = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans`");
    $bcount1 = mysqli_num_rows($bquery1);

    $bquery2 = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans-country`");
    $bcount2 = mysqli_num_rows($bquery2);

	$bquery3 = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans-ranges`");
    $bcount3 = mysqli_num_rows($bquery3);
	
	$bquery4 = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans-other`");
    $bcount4 = mysqli_num_rows($bquery4);
?>
        <li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'bans-ip.php') {
        echo 'class="active"';
    }
?>>
           <a href="bans-ip.php">
              <span><i class="fa fa-user"></i> IP Bans 
			  <small class="label pull-right bg-red"><?php
    echo $bcount1;
?></small></span>
           </a>
        </li>
		
		<li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'bans-country.php') {
        echo 'class="active"';
    }
?>>
           <a href="bans-country.php">
              <span><i class="fa fa-globe"></i> Country Bans 
			  <small class="label pull-right bg-red"><?php
    echo $bcount2;
?></small></span>
           </a>
        </li>
		
		<li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'bans-iprange.php') {
        echo 'class="active"';
    }
?>>
           <a href="bans-iprange.php">
              <span><i class="fas fa-grip-horizontal"></i> IP Range Bans 
			  <small class="label pull-right bg-red"><?php
    echo $bcount3;
?></small></span>
           </a>
        </li>
		
		<li <?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'bans-other.php') {
        echo 'class="active"';
    }
?>>
           <a href="bans-other.php">
              <span><i class="fa fa-desktop"></i> Other Bans
			  <small class="label pull-right bg-red"><?php
    echo $bcount4;
?></small></span>
           </a>
        </li>
          
      </ul>
    </section>

  </aside>
<?php
}

function footer()
{
    include 'config.php';
?>
<footer class="main-footer">
    <strong>&copy; <?php
    echo date("Y");
?> <a href="https://codecanyon.net/item/ban-system/5553063?ref=Antonov_WEB" target="_blank">Ban System</a></strong>
    
</footer>

</div>

    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
	
	<!--Admin-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/js/adminlte.min.js"></script>

	<!--SlimScroll-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>

    <!--Switchery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    
<?php
    if (basename($_SERVER['SCRIPT_NAME']) == 'bans-country.php') {
        echo '
    <!--Select2-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>';
    }
?>
    
    <!--DataTables-->
    <script src="https://cdn.datatables.net/v/bs/dt-1.13.8/r-2.5.0/datatables.min.js"></script>
	
	<!--DatePicker-->
	<script src="assets/plugins/datepicker/datepicker.min.js"></script>
    <script src="assets/plugins/datepicker/datepicker.en.js"></script>

</body>
</html>
<?php
}
?>