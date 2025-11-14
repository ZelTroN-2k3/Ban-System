<?php

/**
 * Ban System - Usage Example
 * 
 * This file demonstrates how to use the Ban System in your website
 */

// Include the Ban System class
require_once 'BanSystem.php';

// Load configuration
$config = require 'config.php';

// Initialize the Ban System
$banSystem = new BanSystem($config);

// Check if visitor should be banned
// This will automatically block access if any ban rule matches
$banSystem->check();

// If execution reaches here, the visitor is allowed
// Continue with your website's normal operation

// Optional: Get visitor information for logging
$visitorInfo = $banSystem->getVisitorInfo();
// You can log this information if needed
// error_log('Visitor allowed: ' . json_encode($visitorInfo));

?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome - Ban System Active</title>
</head>
<body>
    <h1>Welcome!</h1>
    <p>You have access to this website.</p>
    <p>The Ban System is protecting this site.</p>
</body>
</html>
