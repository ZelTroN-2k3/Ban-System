<?php
require("core.php");
head();

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];

    $query = mysqli_query($mysqli, "DELETE FROM `bansystem_ip-whitelist` WHERE id='$id'");
}
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div id="content-container">
				
				<section class="content-header">
    			  <h1><i class="fa fa-flag-o"></i> IP Whitelist</h1>
    			  <ol class="breadcrumb">
   			         <li><a href="dashboard.php"><i class="fa fa-home"></i> Admin Panel</a></li>
    			     <li class="active">IP Whitelist</li>
    			  </ol>
    			</section>


				<!--Page content-->
				<!--===================================================-->
				<section class="content">

<?php
if (isset($_POST['add'])) {

    $ip = addslashes(htmlspecialchars($_POST['ip']));
	
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        echo '<br />
			<div class="callout callout-danger">
                <p><i class="fa fa-exclamation-triangle"></i> The entered <strong>IP Address</strong> is invalid.</p>
			</div>';
    } else {
        $queryvalid = mysqli_query($mysqli, "SELECT * FROM `bansystem_ip-whitelist` WHERE ip='$ip' LIMIT 1");
        $validator  = mysqli_num_rows($queryvalid);
        if ($validator > "0") {
            echo '<br />
			<div class="callout callout-info">
                <p><i class="fa fa-info-circle"></i> This <strong>IP Address</strong> is already whitelisted.</p>
			</div>';
        } else {
            $query = mysqli_query($mysqli, "INSERT INTO `bansystem_ip-whitelist` (ip) VALUES('$ip')");
        }
    }
}
?>
                    
                <div class="row">
				<div class="col-md-8">
				    <div class="box">
						<div class="box-header">
							<h3 class="box-title">IP Whitelist</h3>
						</div>
						<div class="box-body">
							<table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>IP Address</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
$query = mysqli_query($mysqli, "SELECT * FROM `bansystem_ip-whitelist` ORDER BY ip ASC");
while ($row = mysqli_fetch_assoc($query)) {
    echo '
										<tr>
                                            <td>' . $row['ip'] . '</td>
											<td>
                                            <a href="?edit-id=' . $row['id'] . '" class="btn btn-flat btn-flat btn-primary"><i class="fa fa-pen"></i> Edit</a>
                                            <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-flat btn-danger"><i class="fa fa-trash"></i> Delete</a>
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
                    
				<div class="col-md-4">
<form class="form-horizontal" action="" method="post">
				     <div class="box">
						<div class="box-header">
							<h3 class="box-title">Add IP Address</h3>
						</div>
				        <div class="box-body">
								<div class="form-group">
											<label class="col-sm-4 control-label">IP Address: </label>
											<div class="col-sm-8">
												<input type="text" name="ip" class="form-control" required>
											</div>
								</div>	
                        </div>
                        <div class="panel-footer">
							<button class="btn btn-flat btn-primary btn-block" name="add" type="submit">Add</button>
				        </div>
				     </div>
</form>
<?php
if (isset($_GET['edit-id'])) {
    $id    = (int) $_GET["edit-id"];

    $sql   = mysqli_query($mysqli, "SELECT * FROM `bansystem_ip-whitelist` WHERE id = '$id'");
    $row   = mysqli_fetch_assoc($sql);
	
    if (empty($id) || mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=ip-whitelist.php">';
		exit();
    }
	
	if (isset($_POST['edit'])) {

        $ip    = addslashes(htmlspecialchars($_POST['ip']));
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            echo '<br />
				<div class="callout callout-danger">
					<p><i class="fa fa-exclamation-triangle"></i> The entered <strong>IP Address</strong> is invalid.</p>
				</div>';
        } else {
            $queryvalid = mysqli_query($mysqli, "SELECT * FROM `bansystem_ip-whitelist` WHERE ip='$ip' LIMIT 1");
            $validator  = mysqli_num_rows($queryvalid);
            if ($validator > "0") {
                echo '<br />
				<div class="callout callout-info">
					<p><i class="fa fa-info-circle"></i> This <strong>IP Address</strong> is already whitelisted.</p>
				</div>';
            } else {
                $query = mysqli_query($mysqli, "UPDATE `bansystem_ip-whitelist` SET ip='$ip' WHERE id='$id'");
                echo '<meta http-equiv="refresh" content="0;url=ip-whitelist.php">';
            }
        }
    }
?>
<form class="form-horizontal" action="" method="post">
                     <div class="box">
						<div class="box-header">
							<h3 class="box-title">Edit IP Address</h3>
						</div>
				        <div class="box-body">
								<div class="form-group">
											<label class="col-sm-4 control-label">IP Address: </label>
											<div class="col-sm-8">
												<input type="text" name="ip" class="form-control" value="<?php
    echo $row['ip'];
?>" required>
											</div>
								</div>	
                        </div>
                        <div class="panel-footer">
							<button class="btn btn-flat btn-success btn-block" name="edit" type="submit">Save</button>
				        </div>
				     </div>
</form>
<?php 
}
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