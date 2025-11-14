<?php

/**
 * Ban System - Simple Integration
 * 
 * Add this code at the top of your website's entry point (e.g., index.php)
 * to protect your entire website with the Ban System
 */

// Include the Ban System
require_once __DIR__ . '/BanSystem.php';

// Simple configuration (you can also load from config.php)
$config = [
    'banned_ips' => [
        // Add IPs to ban here
    ],
    'banned_countries' => [
        // Add country codes to ban here
    ],
    'banned_ip_ranges' => [
        // Add IP ranges to ban here
    ],
    'banned_isps' => [],
    'banned_browsers' => [],
    'banned_os' => [],
    'banned_referrers' => [],
    'redirect_url' => null,
];

// Initialize and check
$banSystem = new BanSystem($config);
$banSystem->check();

// If execution continues, visitor is allowed
// Your website code continues here...
