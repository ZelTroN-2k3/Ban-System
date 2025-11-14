<?php

/**
 * Ban System - Test Suite
 * 
 * Run with: php test.php
 */

require_once 'BanSystem.php';

class BanSystemTest {
    
    private $passed = 0;
    private $failed = 0;
    
    public function run() {
        echo "Running Ban System Tests...\n\n";
        
        $this->testIPBanning();
        $this->testIPRangeBanning();
        $this->testBrowserDetection();
        $this->testOSDetection();
        $this->testReferrerBanning();
        $this->testVisitorInfo();
        
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "Tests completed: {$this->passed} passed, {$this->failed} failed\n";
        
        return $this->failed === 0;
    }
    
    private function assert($condition, $message) {
        if ($condition) {
            echo "✓ {$message}\n";
            $this->passed++;
        } else {
            echo "✗ {$message}\n";
            $this->failed++;
        }
    }
    
    private function testIPBanning() {
        echo "Testing IP Banning:\n";
        
        // Mock server variables
        $_SERVER['REMOTE_ADDR'] = '192.168.1.100';
        unset($_SERVER['HTTP_CLIENT_IP']);
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        
        $config = [
            'banned_ips' => ['192.168.1.100'],
            'redirect_url' => null,
        ];
        
        $banSystem = new BanSystem($config);
        
        // We can't actually test the blocking (it would exit), 
        // but we can verify the IP is detected correctly
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['ip'] === '192.168.1.100', 'IP address detected correctly');
        
        // Test IP not in ban list
        $_SERVER['REMOTE_ADDR'] = '192.168.1.200';
        $config['banned_ips'] = ['192.168.1.100'];
        $banSystem = new BanSystem($config);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['ip'] === '192.168.1.200', 'Different IP detected correctly');
        
        echo "\n";
    }
    
    private function testIPRangeBanning() {
        echo "Testing IP Range Banning:\n";
        
        // Test CIDR range detection
        $_SERVER['REMOTE_ADDR'] = '192.168.1.50';
        
        $config = [
            'banned_ip_ranges' => ['192.168.1.0/24'],
        ];
        
        $banSystem = new BanSystem($config);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['ip'] === '192.168.1.50', 'IP in range detected correctly');
        
        // Test IP outside range
        $_SERVER['REMOTE_ADDR'] = '192.168.2.50';
        $banSystem = new BanSystem($config);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['ip'] === '192.168.2.50', 'IP outside range detected correctly');
        
        echo "\n";
    }
    
    private function testBrowserDetection() {
        echo "Testing Browser Detection:\n";
        
        // Test Chrome detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['browser'] === 'Chrome', 'Chrome browser detected');
        
        // Test Firefox detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['browser'] === 'Firefox', 'Firefox browser detected');
        
        // Test Safari detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['browser'] === 'Safari', 'Safari browser detected');
        
        // Test Edge detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36 Edg/91.0.864.59';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['browser'] === 'Edge', 'Edge browser detected');
        
        echo "\n";
    }
    
    private function testOSDetection() {
        echo "Testing OS Detection:\n";
        
        // Test Windows 10 detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['os'] === 'Windows 10', 'Windows 10 detected');
        
        // Test Mac OS X detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['os'] === 'Mac OS X', 'Mac OS X detected');
        
        // Test Linux detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['os'] === 'Linux', 'Linux detected');
        
        // Test Android detection
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36';
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['os'] === 'Android', 'Android detected');
        
        echo "\n";
    }
    
    private function testReferrerBanning() {
        echo "Testing Referrer Banning:\n";
        
        // Test referrer detection
        $_SERVER['HTTP_REFERER'] = 'https://spam-site.com/page';
        $config = [
            'banned_referrers' => ['spam-site.com'],
        ];
        
        $banSystem = new BanSystem($config);
        $info = $banSystem->getVisitorInfo();
        $this->assert($info['referrer'] === 'https://spam-site.com/page', 'Referrer detected correctly');
        
        // Test empty referrer
        unset($_SERVER['HTTP_REFERER']);
        $banSystem = new BanSystem($config);
        $info = $banSystem->getVisitorInfo();
        $this->assert(empty($info['referrer']), 'Empty referrer handled correctly');
        
        echo "\n";
    }
    
    private function testVisitorInfo() {
        echo "Testing Visitor Info:\n";
        
        // Setup test environment
        $_SERVER['REMOTE_ADDR'] = '203.0.113.50';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';
        $_SERVER['HTTP_REFERER'] = 'https://example.com/';
        
        $banSystem = new BanSystem([]);
        $info = $banSystem->getVisitorInfo();
        
        $this->assert(isset($info['ip']), 'Visitor info contains IP');
        $this->assert(isset($info['user_agent']), 'Visitor info contains user agent');
        $this->assert(isset($info['referrer']), 'Visitor info contains referrer');
        $this->assert(isset($info['browser']), 'Visitor info contains browser');
        $this->assert(isset($info['os']), 'Visitor info contains OS');
        
        $this->assert($info['ip'] === '203.0.113.50', 'Visitor IP is correct');
        $this->assert($info['browser'] === 'Chrome', 'Visitor browser is correct');
        $this->assert($info['os'] === 'Windows 10', 'Visitor OS is correct');
        
        echo "\n";
    }
}

// Run tests
$test = new BanSystemTest();
$success = $test->run();

exit($success ? 0 : 1);
