<?php
include "header.php";

$query = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Banned'");
$row   = mysqli_fetch_assoc($query);
?>

	  <div class="page-header">
        <div class="row">
          <div class="col-lg-12">
            <div class="bs-example">
              <div class="jumbotron">
                <center>
				<div class="well" style="background-color: #d9534f; color: white;">
                    <h3><?php
echo $row['text'];
?></h3>
                </div>
                    <p style="font-size: 50px;">
<span class="fa-stack fa-lg">
  <i class="fa fa-user fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x text-danger"></i>
</span></p>
<?php
//Getting the Real IP Address
function get_realip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'Unknown';
    return $ipaddress;
}

$ip = get_realip();
if ($ip == "::1") {
    $ip = "127.0.0.1";
}
$querybanned = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans` WHERE ip='$ip'");
$banned      = mysqli_num_rows($querybanned);
$row         = mysqli_fetch_array($querybanned);
$reason      = $row['reason'];
$redirect    = $row['redirect'];
$url         = $row['url'];
if ($banned > "0") {
    echo '<p>Reason: <strong>' . $reason . '</strong></p>';
}
if ($redirect == "Yes") {
    echo '<br /><center>You will be redirected</center><br />
<meta http-equiv="refresh" content="4;url=' . $url . '">';
}
?>
                <h4>Please contact with the webmaster of the website if you think something is wrong.</h4>
               </center>
              </div>
            </div>
          </div>
        </div>
      </div>

<?php
include "footer.php";
?>