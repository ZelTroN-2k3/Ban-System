<?php

/**
 * Ban System - Block Unwanted Visitors
 * 
 * This system helps block access to your website for specific:
 * - IP Addresses
 * - Countries
 * - IP Ranges
 * - Internet Service Providers (ISP)
 * - Browsers
 * - Operating Systems (OS)
 * - Referrers
 * 
 * Banned IPs and Countries can be redirected to an optional page or website.
 */
class BanSystem {
    
    private $config = [];
    private $visitorIP;
    private $visitorUserAgent;
    private $visitorReferrer;
    private $redirectUrl;
    
    /**
     * Initialize the Ban System
     * 
     * @param array $config Configuration array with ban rules
     */
    public function __construct($config = []) {
        $this->config = array_merge([
            'banned_ips' => [],
            'banned_countries' => [],
            'banned_ip_ranges' => [],
            'banned_isps' => [],
            'banned_browsers' => [],
            'banned_os' => [],
            'banned_referrers' => [],
            'redirect_url' => null,
            'block_message' => 'Access Denied'
        ], $config);
        
        $this->visitorIP = $this->getVisitorIP();
        $this->visitorUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->visitorReferrer = $_SERVER['HTTP_REFERER'] ?? '';
        $this->redirectUrl = $this->config['redirect_url'];
    }
    
    /**
     * Get the visitor's IP address
     * 
     * @return string IP address
     */
    private function getVisitorIP() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return trim($ip);
    }
    
    /**
     * Check if visitor should be banned and take action
     * 
     * @return bool True if banned, false otherwise
     */
    public function check() {
        if ($this->isIPBanned()) {
            $this->blockAccess('Your IP address is banned');
            return true;
        }
        
        if ($this->isIPRangeBanned()) {
            $this->blockAccess('Your IP range is banned');
            return true;
        }
        
        if ($this->isCountryBanned()) {
            $this->blockAccess('Your country is banned');
            return true;
        }
        
        if ($this->isISPBanned()) {
            $this->blockAccess('Your ISP is banned');
            return true;
        }
        
        if ($this->isBrowserBanned()) {
            $this->blockAccess('Your browser is banned');
            return true;
        }
        
        if ($this->isOSBanned()) {
            $this->blockAccess('Your operating system is banned');
            return true;
        }
        
        if ($this->isReferrerBanned()) {
            $this->blockAccess('Your referrer is banned');
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if IP address is banned
     * 
     * @return bool
     */
    private function isIPBanned() {
        return in_array($this->visitorIP, $this->config['banned_ips']);
    }
    
    /**
     * Check if IP is in a banned range (CIDR notation)
     * 
     * @return bool
     */
    private function isIPRangeBanned() {
        foreach ($this->config['banned_ip_ranges'] as $range) {
            if ($this->ipInRange($this->visitorIP, $range)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Check if IP is within a CIDR range
     * 
     * @param string $ip IP address to check
     * @param string $range CIDR range (e.g., 192.168.1.0/24)
     * @return bool
     */
    private function ipInRange($ip, $range) {
        if (strpos($range, '/') === false) {
            return false;
        }
        
        list($subnet, $mask) = explode('/', $range);
        
        $ip_long = ip2long($ip);
        $subnet_long = ip2long($subnet);
        $mask_long = -1 << (32 - (int)$mask);
        $subnet_long &= $mask_long;
        
        return ($ip_long & $mask_long) == $subnet_long;
    }
    
    /**
     * Check if visitor's country is banned
     * 
     * @return bool
     */
    private function isCountryBanned() {
        if (empty($this->config['banned_countries'])) {
            return false;
        }
        
        $country = $this->getCountryFromIP($this->visitorIP);
        return in_array($country, $this->config['banned_countries']);
    }
    
    /**
     * Get country code from IP address
     * This is a basic implementation. For production, use a GeoIP database or API
     * 
     * @param string $ip
     * @return string Country code or empty string
     */
    private function getCountryFromIP($ip) {
        // This is a placeholder. In production, you would use:
        // - MaxMind GeoIP2 database
        // - IP geolocation API (ip-api.com, ipapi.co, etc.)
        // For demo purposes, this returns empty
        
        // Example implementation with ip-api.com (free, rate-limited):
        // $data = @file_get_contents("http://ip-api.com/json/{$ip}");
        // if ($data) {
        //     $json = json_decode($data, true);
        //     return $json['countryCode'] ?? '';
        // }
        
        return '';
    }
    
    /**
     * Check if visitor's ISP is banned
     * 
     * @return bool
     */
    private function isISPBanned() {
        if (empty($this->config['banned_isps'])) {
            return false;
        }
        
        $isp = $this->getISPFromIP($this->visitorIP);
        
        foreach ($this->config['banned_isps'] as $bannedISP) {
            if (stripos($isp, $bannedISP) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get ISP from IP address
     * This is a placeholder. In production, use a GeoIP database or API
     * 
     * @param string $ip
     * @return string ISP name or empty string
     */
    private function getISPFromIP($ip) {
        // Placeholder for ISP detection
        // In production, use GeoIP2 or similar service
        return '';
    }
    
    /**
     * Check if visitor's browser is banned
     * 
     * @return bool
     */
    private function isBrowserBanned() {
        if (empty($this->config['banned_browsers'])) {
            return false;
        }
        
        $browser = $this->detectBrowser();
        
        foreach ($this->config['banned_browsers'] as $bannedBrowser) {
            if (stripos($browser, $bannedBrowser) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Detect browser from user agent
     * 
     * @return string Browser name
     */
    private function detectBrowser() {
        $ua = $this->visitorUserAgent;
        
        if (stripos($ua, 'Edg/') !== false || stripos($ua, 'Edge/') !== false) {
            return 'Edge';
        } elseif (stripos($ua, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($ua, 'Safari') !== false) {
            return 'Safari';
        } elseif (stripos($ua, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident') !== false) {
            return 'Internet Explorer';
        } elseif (stripos($ua, 'Opera') !== false || stripos($ua, 'OPR') !== false) {
            return 'Opera';
        }
        
        return 'Unknown';
    }
    
    /**
     * Check if visitor's OS is banned
     * 
     * @return bool
     */
    private function isOSBanned() {
        if (empty($this->config['banned_os'])) {
            return false;
        }
        
        $os = $this->detectOS();
        
        foreach ($this->config['banned_os'] as $bannedOS) {
            if (stripos($os, $bannedOS) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Detect operating system from user agent
     * 
     * @return string OS name
     */
    private function detectOS() {
        $ua = $this->visitorUserAgent;
        
        if (stripos($ua, 'Windows NT 10.0') !== false) {
            return 'Windows 10';
        } elseif (stripos($ua, 'Windows NT 6.3') !== false) {
            return 'Windows 8.1';
        } elseif (stripos($ua, 'Windows NT 6.2') !== false) {
            return 'Windows 8';
        } elseif (stripos($ua, 'Windows NT 6.1') !== false) {
            return 'Windows 7';
        } elseif (stripos($ua, 'Windows') !== false) {
            return 'Windows';
        } elseif (stripos($ua, 'Android') !== false) {
            return 'Android';
        } elseif (stripos($ua, 'iOS') !== false || stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false) {
            return 'iOS';
        } elseif (stripos($ua, 'Mac OS X') !== false) {
            return 'Mac OS X';
        } elseif (stripos($ua, 'Linux') !== false) {
            return 'Linux';
        }
        
        return 'Unknown';
    }
    
    /**
     * Check if referrer is banned
     * 
     * @return bool
     */
    private function isReferrerBanned() {
        if (empty($this->config['banned_referrers']) || empty($this->visitorReferrer)) {
            return false;
        }
        
        foreach ($this->config['banned_referrers'] as $bannedReferrer) {
            if (stripos($this->visitorReferrer, $bannedReferrer) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Block access and redirect or show message
     * 
     * @param string $reason Reason for blocking
     */
    private function blockAccess($reason = '') {
        if ($this->redirectUrl) {
            header("Location: {$this->redirectUrl}");
            exit;
        } else {
            http_response_code(403);
            $message = !empty($reason) ? $reason : $this->config['block_message'];
            die($message);
        }
    }
    
    /**
     * Get visitor information (for debugging/logging)
     * 
     * @return array
     */
    public function getVisitorInfo() {
        return [
            'ip' => $this->visitorIP,
            'user_agent' => $this->visitorUserAgent,
            'referrer' => $this->visitorReferrer,
            'browser' => $this->detectBrowser(),
            'os' => $this->detectOS(),
        ];
    }
}
