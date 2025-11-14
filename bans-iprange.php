<?php
require("core.php");
head();

if (isset($_GET['delete-all'])) {

    $query = mysqli_query($mysqli, "TRUNCATE TABLE `bansystem_bans-ranges`");
}

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];

    $query = mysqli_query($mysqli, "DELETE FROM `bansystem_bans-ranges` WHERE id='$id'");
}
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div id="content-container">
				
				<section class="content-header">
    			  <h1><i class="fas fa-grip-horizontal"></i> IP Range Bans</h1>
    			  <ol class="breadcrumb">
   			         <li><a href="dashboard.php"><i class="fa fa-home"></i> Admin Panel</a></li>
    			     <li class="active">IP Range Bans</li>
    			  </ol>
    			</section>


				<!--Page content-->
				<!--===================================================-->
				<section class="content">
				
<?php
if (isset($_POST['ban-iprange'])) {

    $ip_range = addslashes(htmlspecialchars($_POST['ip_range']));
    
    $queryvalid = $mysqli->query("SELECT * FROM `bansystem_bans-ranges` WHERE ip_range='$ip_range' LIMIT 1");
    $validator  = mysqli_num_rows($queryvalid);
    if ($validator > "0") {
        echo '<br />
			<div class="alert alert-info">
                <p><i class="fas fa-info-circle"></i> This <strong>IP Range</strong> is already banned.</p>
			</div>';
    } else {
        $query = $mysqli->query("INSERT INTO `bansystem_bans-ranges` (`ip_range`) VALUES ('$ip_range')");
    }
}
?>
                    
                <div class="row">
                    
				<div class="col-md-8">
<?php
if (isset($_GET['edit-id'])) {
    $id    = (int) $_GET["edit-id"];

    $result = $mysqli->query("SELECT * FROM `bansystem_bans-ranges` WHERE id = '$id'");
    $row    = mysqli_fetch_assoc($result);
    
	if (empty($id) || mysqli_num_rows($result) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=bans-iprange.php">';
        exit();
    }
	
	if (isset($_POST['edit-ban'])) {
        $ip_range = addslashes(htmlspecialchars($_POST['ip_range']));
        
        $update = $mysqli->query("UPDATE `bansystem_bans-ranges` SET ip_range='$ip_range' WHERE id='$id'");
    }
?>        
<form class="form-horizontal" action="" method="post">
                    <div class="box">
						<div class="box-header">
							<h3 class="box-title">Edit - IP Range Ban</h3>
						</div>
						<div class="box-body">
										<div class="form-group">
											<label class="col-sm-2 control-label">IP Address: </label>
											<div class="col-sm-10">
												<input name="ip_range" class="form-control" type="text" maxlength="19" value="<?php
    echo $row['ip_range'];
?>" required>
											</div>
										</div>
                        </div>
                        <div class="panel-footer">
							<button class="btn btn-flat btn-success btn-block" name="edit-ban" type="submit">Save</button>
				        </div>
                     </div>
				</form>
<?php
}
?>
				    <div class="box">
						<div class="box-header">
							<h3 class="box-title">IP Range Bans</h3>
						</div>
						<div class="box-body">
						
						<center><a href="?delete-all" class="btn btn-flat btn-danger" title="Delete all IP Bans"><i class="fa fa-trash"></i> Delete All</a></center>
						
<table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
						                  <th><i class="fas fa-grip-horizontal"></i> IP Range</th>
										  <th><i class="fa fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
$query = mysqli_query($mysqli, "SELECT * FROM `bansystem_bans-ranges` ORDER BY ip_range ASC");
while ($row = mysqli_fetch_assoc($query)) {
    echo '
										<tr>
						                    <td>' . $row['ip_range'] . '</td>
											<td>
                                            <a href="?edit-id=' . $row['id'] . '" class="btn btn-flat btn-primary"><i class="fa fa-pen"></i> Edit</a>
                                            <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i> Unban</a>
											</td>
										</tr>
';
}
?>
									</tbody>
								</table>
                        </div>
                     </div>
                </div>

<form class="form-horizontal" action="" method="post">
				<div class="col-md-4">
				     <div class="box">
						<div class="box-header">
							<h3 class="box-title">Ban IP Range</h3>
						</div>
				        <div class="box-body">
										<div class="form-group">
											<label class="col-sm-4 control-label">IP Range: </label>
											<div class="col-sm-8">
												<input name="ip_range" class="form-control" type="text" placeholder="Format: 12.34.56 or 1111:db8:3333:4444" maxlength="19" value="" required>
											</div>
										</div><br />
                        </div>
                        <div class="panel-footer">
							<button class="btn btn-flat btn-danger btn-block" name="ban-iprange" type="submit">Ban</button>
				        </div>
				     </div>
				</div>
</form>
				</div>
                    
				</div>
				<!--===================================================-->
				<!--End page content-->


			</div>
			<!--===================================================-->
			<!--END CONTENT CONTAINER-->

<script>
$(document).ready(function() {

	$('#dt-basic').dataTable( {
		"responsive": true,
		"language": {
			"paginate": {
			  "previous": '<i class="fa fa-angle-left"></i>',
			  "next": '<i class="fa fa-angle-right"></i>'
			}
		}
	} );
} );
</script>
<?php
footer();
?>