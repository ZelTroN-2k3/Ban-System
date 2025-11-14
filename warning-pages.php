<?php
require("core.php");
head();

if (isset($_POST['update'])) {
    $text  = addslashes(htmlentities($_POST['text']));

    $text2  = addslashes(htmlentities($_POST['text2']));
    
    $text3  = addslashes(htmlentities($_POST['text3']));
    
    $text4  = addslashes(htmlentities($_POST['text4']));
    
    $text5  = addslashes(htmlentities($_POST['text5']));
	
	$text6  = addslashes(htmlentities($_POST['text6']));
    
    $update_banned = mysqli_query($mysqli, "UPDATE `bansystem_pages-layolt` SET 
`text` = '$text' 
WHERE page='Banned'");
    
    $update_bannedc = mysqli_query($mysqli, "UPDATE `bansystem_pages-layolt` SET 
`text` = '$text2' 
WHERE page='Banned_Country'");
    
    $update_blockedbr = mysqli_query($mysqli, "UPDATE `bansystem_pages-layolt` SET 
`text` = '$text3' 
WHERE page='Blocked_Browser'");
    
    $update_blockedos = mysqli_query($mysqli, "UPDATE `bansystem_pages-layolt` SET 
`text` = '$text4' 
WHERE page='Blocked_OS'");
    
    $update_blockedisp = mysqli_query($mysqli, "UPDATE `bansystem_pages-layolt` SET 
`text` = '$text5' 
WHERE page='Blocked_ISP'");

	$update_blockedrfr = mysqli_query($mysqli, "UPDATE `bansystem_pages-layolt` SET 
`text` = '$text6' 
WHERE page='Blocked_RFR'");

}
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div id="content-container">
				
				<section class="content-header">
    			  <h1><i class="fa fa-file-text-o"></i> Warning Pages</h1>
    			  <ol class="breadcrumb">
   			         <li><a href="dashboard.php"><i class="fa fa-home"></i> Admin Panel</a></li>
    			     <li class="active">Warning Pages</li>
    			  </ol>
    			</section>


				<!--Page content-->
				<!--===================================================-->
				<section class="content">

					<form action="" method="post">
                         <div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active">
										<a data-toggle="tab" href="#banned-layout">Banned</a>
									</li>
									<li>
										<a data-toggle="tab" href="#bannedc-layout">Banned Countries</a>
									</li>
                                    <li>
										<a data-toggle="tab" href="#bannedbr-layout">Blocked Browsers</a>
									</li>
                                    <li>
										<a data-toggle="tab" href="#bannedos-layout">Blocked OS</a>
									</li>
                                    <li>
										<a data-toggle="tab" href="#bannedisp-layout">Blocked ISP</a>
									</li>
									<li>
										<a data-toggle="tab" href="#bannedrfr-layout">Blocked Referrer</a>
									</li>
								</ul>
					
								<!--Tabs Content-->
								<div class="tab-content">

<?php
$sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Banned'");
$row   = mysqli_fetch_assoc($sql);
?>
									<div id="banned-layout" class="tab-pane fade active in">
<fieldset>
	        <center>
            <label>Page Text:</label>
	        <textarea name="text" class="form-control" rows="5" type="text" required><?php
echo $row['text'];
?></textarea>
			</center>
</fieldset>
									</div>
                                    
<?php
$sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Banned_Country'");
$row   = mysqli_fetch_assoc($sql);
?>
									<div id="bannedc-layout" class="tab-pane fade">
<fieldset>
	        <center>
            <label>Page Text:</label>
	        <textarea name="text2" class="form-control" rows="5" type="text" required><?php
echo $row['text'];
?></textarea>
			</center>
</fieldset>
									</div>
                                    
<?php
$sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Blocked_Browser'");
$row   = mysqli_fetch_assoc($sql);
?>
									<div id="bannedbr-layout" class="tab-pane fade">
<fieldset>
	        <center>
            <label>Page Text:</label>
	        <textarea name="text3" class="form-control" rows="5" type="text" required><?php
echo $row['text'];
?></textarea>
			</center>
</fieldset>
									</div>
                                    
<?php
$sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Blocked_OS'");
$row   = mysqli_fetch_assoc($sql);
?>
									<div id="bannedos-layout" class="tab-pane fade">
<fieldset>
	        <center>
            <label>Page Text:</label>
	        <textarea name="text4" class="form-control" rows="5" type="text" required><?php
echo $row['text'];
?></textarea>
			</center>
</fieldset>
									</div>
                                    
<?php
$sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Blocked_ISP'");
$row   = mysqli_fetch_assoc($sql);
?>
									<div id="bannedisp-layout" class="tab-pane fade">
<fieldset>
	        <center>
            <label>Page Text:</label>
	        <textarea name="text5" class="form-control" rows="5" type="text" required><?php
echo $row['text'];
?></textarea>
			</center>
</fieldset>
									</div>
									
<?php
$sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_pages-layolt` WHERE page='Blocked_RFR'");
$row   = mysqli_fetch_assoc($sql);
?>
									<div id="bannedrfr-layout" class="tab-pane fade">
<fieldset>
	        <center>
            <label>Page Text:</label>
	        <textarea name="text6" class="form-control" rows="5" type="text" required><?php
echo $row['text'];
?></textarea>
			</center>
</fieldset>
									</div>
								</div>
							</div>
    
<input type="submit" class="btn btn-flat btn-success btn-md btn-block" name="update" value="Save" />
</form>
                    
				</div>
				<!--===================================================-->
				<!--End page content-->


			</div>
			<!--===================================================-->
			<!--END CONTENT CONTAINER-->
    
<?php
footer();
?>