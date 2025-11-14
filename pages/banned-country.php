<?php
include "header.php";

$query = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Banned_Country'");
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
  <i class="fa fa-globe fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x text-danger"></i>
</span></p>
<?php
$cid = 0;
@$cid = (int) $_GET['c_id'];

if ($cid > 0) {
    $querybanned = $mysqli->query("SELECT * FROM `bansystem_bans-country` WHERE id='$cid'");
    $banned      = mysqli_num_rows($querybanned);
    $rowcb       = mysqli_fetch_array($querybanned);
    $redirect    = $rowcb['redirect'];
    $url         = $rowcb['url'];
    if ($redirect == 'Yes') {
        echo '<br /><center>You will be redirected</center><br />
		<meta http-equiv="refresh" content="4;url=' . $url . '">';
    }
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