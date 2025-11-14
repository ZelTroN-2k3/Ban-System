<?php

/**
 * Ban System Configuration Example
 * 
 * Copy this file to config.php and customize as needed
 */

return [
    // List of banned IP addresses
    'banned_ips' => [
        '192.168.1.100',
        '10.0.0.50',
        // Add more IPs here
    ],
    
    // List of banned countries (ISO 3166-1 alpha-2 country codes)
    'banned_countries' => [
        'CN', // China
        'RU', // Russia
        // Add more country codes here
    ],
    
    // List of banned IP ranges (CIDR notation)
    'banned_ip_ranges' => [
        '192.168.1.0/24',
        '10.0.0.0/8',
        // Add more IP ranges here
    ],
    
    // List of banned ISPs (partial match)
    'banned_isps' => [
        'Spam ISP',
        'Bad Network',
        // Add more ISP names here
    ],
    
    // List of banned browsers (partial match)
    'banned_browsers' => [
        'IE',
        'Internet Explorer',
        // Add more browser names here
    ],
    
    // List of banned operating systems (partial match)
    'banned_os' => [
        // 'Windows XP',
        // 'Windows Vista',
        // Add OS names to ban here
    ],
    
    // List of banned referrers (partial match)
    'banned_referrers' => [
        'spam-site.com',
        'malicious-domain.com',
        // Add more referrer domains here
    ],
    
    // Optional: URL to redirect banned visitors
    // If null, visitors will see a 403 Forbidden message
    'redirect_url' => null, // e.g., 'https://example.com/banned.html'
    
    // Message to show when blocking (if not redirecting)
    'block_message' => 'Access Denied - Your access has been blocked'
];
