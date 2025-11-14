# Ban System - Block Unwanted Visitors

A comprehensive PHP-based system to block access to your website for specific IP Addresses, Countries, IP Ranges, Internet Service Providers (ISP), Browsers, Operating Systems (OS), and Referrers. Banned visitors can be redirected to an optional page or website.

## Features

- **IP Address Blocking**: Block specific IP addresses from accessing your website
- **Country Blocking**: Block entire countries using ISO country codes
- **IP Range Blocking**: Block IP ranges using CIDR notation (e.g., 192.168.1.0/24)
- **ISP Blocking**: Block specific Internet Service Providers
- **Browser Blocking**: Block specific web browsers (Chrome, Firefox, Safari, Edge, IE, Opera)
- **Operating System Blocking**: Block specific operating systems (Windows, Mac OS X, Linux, Android, iOS)
- **Referrer Blocking**: Block traffic from specific referrer domains
- **Flexible Redirect**: Redirect banned visitors to a custom page or show a 403 error message
- **Easy Integration**: Simple to integrate into existing PHP websites
- **Visitor Information**: Get detailed visitor information for logging and analytics

## Requirements

- PHP 5.6 or higher
- Web server (Apache, Nginx, etc.)

## Installation

1. Download or clone this repository
2. Copy the files to your web server
3. Include `BanSystem.php` in your website's entry point

```bash
git clone https://github.com/ZelTroN-2k3/Ban-System.git
cd Ban-System
```

## Quick Start

### Basic Usage

```php
<?php
require_once 'BanSystem.php';

// Configure ban rules
$config = [
    'banned_ips' => ['192.168.1.100', '10.0.0.50'],
    'banned_countries' => ['CN', 'RU'],
    'banned_ip_ranges' => ['192.168.1.0/24'],
    'banned_browsers' => ['Internet Explorer'],
    'banned_os' => [],
    'banned_referrers' => ['spam-site.com'],
    'redirect_url' => 'https://example.com/blocked.html',
];

// Initialize and check
$banSystem = new BanSystem($config);
$banSystem->check();

// If execution reaches here, visitor is allowed
?>
```

### Using Configuration File

1. Copy `config.example.php` to `config.php`
2. Edit `config.php` with your ban rules
3. Use in your code:

```php
<?php
require_once 'BanSystem.php';

$config = require 'config.php';
$banSystem = new BanSystem($config);
$banSystem->check();

// Your website code continues here...
?>
```

## Configuration Options

### banned_ips
Array of IP addresses to block.

```php
'banned_ips' => [
    '192.168.1.100',
    '10.0.0.50',
    '203.0.113.25',
]
```

### banned_countries
Array of ISO 3166-1 alpha-2 country codes to block.

```php
'banned_countries' => [
    'CN', // China
    'RU', // Russia
    'KP', // North Korea
]
```

**Note**: Country detection requires a GeoIP database or API. See "Country Detection Setup" below.

### banned_ip_ranges
Array of IP ranges in CIDR notation to block.

```php
'banned_ip_ranges' => [
    '192.168.1.0/24',   // Blocks 192.168.1.0 - 192.168.1.255
    '10.0.0.0/8',       // Blocks 10.0.0.0 - 10.255.255.255
]
```

### banned_isps
Array of ISP names to block (partial match).

```php
'banned_isps' => [
    'Spam Network',
    'Bad ISP',
]
```

**Note**: ISP detection requires a GeoIP database or API.

### banned_browsers
Array of browser names to block (partial match).

```php
'banned_browsers' => [
    'Internet Explorer',
    'IE',
    'Chrome',
    'Firefox',
    'Safari',
    'Edge',
    'Opera',
]
```

### banned_os
Array of operating system names to block (partial match).

```php
'banned_os' => [
    'Windows XP',
    'Windows Vista',
    'Windows 7',
    'Android',
    'iOS',
]
```

### banned_referrers
Array of referrer domains to block (partial match).

```php
'banned_referrers' => [
    'spam-site.com',
    'malicious-domain.com',
    'bad-referrer.net',
]
```

### redirect_url
Optional URL to redirect banned visitors. If `null`, shows a 403 error with the block message.

```php
'redirect_url' => 'https://example.com/blocked.html',
// or
'redirect_url' => null, // Shows 403 error message
```

### block_message
Message to display when blocking access (if not redirecting).

```php
'block_message' => 'Access Denied - Your access has been blocked'
```

## Integration Examples

### Protect Entire Website

Add to the top of your `index.php` or main entry point:

```php
<?php
require_once __DIR__ . '/BanSystem.php';

$config = require __DIR__ . '/config.php';
$banSystem = new BanSystem($config);
$banSystem->check();

// Your website code continues...
?>
```

### Protect Specific Pages

Add to specific PHP files you want to protect:

```php
<?php
require_once 'BanSystem.php';

$config = [
    'banned_ips' => ['192.168.1.100'],
    'redirect_url' => '/blocked.html',
];

$banSystem = new BanSystem($config);
$banSystem->check();

// Protected page content...
?>
```

### With .htaccess (Apache)

For WordPress, Joomla, or other PHP applications, create a `auto_prepend.php`:

```php
<?php
require_once __DIR__ . '/BanSystem.php';
$config = require __DIR__ . '/config.php';
$banSystem = new BanSystem($config);
$banSystem->check();
?>
```

Add to `.htaccess`:

```apache
php_value auto_prepend_file "/path/to/auto_prepend.php"
```

## Country Detection Setup

To enable country blocking, you need to implement GeoIP detection. Here are some options:

### Option 1: MaxMind GeoIP2 (Recommended)

1. Sign up for a free account at [MaxMind](https://www.maxmind.com/)
2. Download the GeoLite2 Country database
3. Install the MaxMind GeoIP2 PHP library:

```bash
composer require geoip2/geoip2:~2.0
```

4. Update the `getCountryFromIP()` method in `BanSystem.php`:

```php
private function getCountryFromIP($ip) {
    require_once 'vendor/autoload.php';
    $reader = new \GeoIp2\Database\Reader('/path/to/GeoLite2-Country.mmdb');
    try {
        $record = $reader->country($ip);
        return $record->country->isoCode;
    } catch (\Exception $e) {
        return '';
    }
}
```

### Option 2: IP-API.com (Free with rate limits)

Update the `getCountryFromIP()` method:

```php
private function getCountryFromIP($ip) {
    $data = @file_get_contents("http://ip-api.com/json/{$ip}");
    if ($data) {
        $json = json_decode($data, true);
        return $json['countryCode'] ?? '';
    }
    return '';
}
```

**Note**: Free tier has rate limits (45 requests per minute).

## ISP Detection Setup

Similar to country detection, implement ISP detection:

### Using MaxMind GeoIP2

```php
private function getISPFromIP($ip) {
    require_once 'vendor/autoload.php';
    $reader = new \GeoIp2\Database\Reader('/path/to/GeoLite2-ASN.mmdb');
    try {
        $record = $reader->asn($ip);
        return $record->autonomousSystemOrganization;
    } catch (\Exception $e) {
        return '';
    }
}
```

## Testing

Run the included test suite:

```bash
php test.php
```

The test suite validates:
- IP address detection
- IP range detection (CIDR)
- Browser detection
- Operating system detection
- Referrer detection
- Visitor information gathering

## Examples

See the included example files:
- `example.php` - Complete working example
- `integration.php` - Simple integration template
- `config.example.php` - Configuration template

## How It Works

1. The system captures visitor information (IP, User Agent, Referrer)
2. It checks the visitor against all configured ban rules
3. If any rule matches, the visitor is blocked (redirected or shown an error)
4. If no rules match, execution continues normally

## Security Considerations

- Store sensitive ban lists outside the web root when possible
- Use `.htaccess` or server configuration to protect config files
- Regularly update your GeoIP databases
- Monitor logs for false positives
- Consider using IP whitelist for critical systems
- Be cautious with country blocking to avoid blocking legitimate users

## Browser Detection

Supports detection of:
- Chrome
- Firefox
- Safari
- Edge
- Internet Explorer
- Opera

## Operating System Detection

Supports detection of:
- Windows (10, 8.1, 8, 7, Vista, XP)
- Mac OS X
- Linux
- Android
- iOS

## Visitor Information

Get detailed visitor information:

```php
$banSystem = new BanSystem($config);
$info = $banSystem->getVisitorInfo();

print_r($info);
// Array
// (
//     [ip] => 203.0.113.50
//     [user_agent] => Mozilla/5.0...
//     [referrer] => https://example.com/
//     [browser] => Chrome
//     [os] => Windows 10
// )
```

## License

This project is open source and available under the MIT License.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/ZelTroN-2k3/Ban-System).

## Author

ZelTroN-2k3

## Version

1.0.0
