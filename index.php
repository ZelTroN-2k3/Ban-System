<?php
$configfile = 'config.php';
if (!file_exists($configfile)) {
    echo '<meta http-equiv="refresh" content="0; url=install" />';
    exit();
}

include "config.php";

if(!isset($_SESSION)) {
    session_start();
}
    
if (isset($_SESSION['sec-username'])) {
    $uname = $_SESSION['sec-username'];
    if ($uname == $settings['username']) {
        echo '<meta http-equiv="refresh" content="0; url=dashboard.php" />';
        exit;
    }
}
    
$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    
$error = "No";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <title>Ban System &rsaquo; Admin Panel</title>

        <!-- CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">

        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.png">
    </head>

    <body class="hold-transition login-page">
        
<div class="login-box">
    <div class="login-logo">
        <a href="index.php"><i class="fa fa-ban"></i> Ban <strong>System</strong></a>
    </div>
    <div class="login-box-body">
<?php
    if (isset($_POST['signin'])) {
        $username = mysqli_real_escape_string($mysqli, $_POST['username']);
		$password = hash('sha256', $_POST['password']);

		if ($username == $settings['username'] && $password == $settings['password']) {
            
			$_SESSION['sec-username'] = $username;
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php">';
        } else {
            echo '<br />
			<div class="callout callout-danger">
				<i class="fa fa-exclamation-circle"></i> The entered <strong>Username</strong> or <strong>Password</strong> is incorrect.
			</div>';
            $error = "Yes";
        }
    }
?> 
        <form action="" method="post">
            <div class="form-group has-feedback <?php
    if ($error == "Yes") {
        echo 'has-error';
    }
?>">
                <input type="username" name="username" class="form-control" placeholder="Username" <?php
    if ($error == "Yes") {
        echo 'autofocus';
    }
?> required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" name="signin" class="btn btn-primary btn-block btn-flat btn-lg"><i class="fa fa-sign-in"></i>
&nbsp;Sign In</button>
                </div>
            </div>
        </form> 
    </div>
</div>

    </body>
</html>