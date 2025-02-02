<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get SRS version
    $version = $client->getVersion();
    
    echo "SRS Version Information:\n";
    echo "=====================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $version->getServerId() . "\n";
    echo "Service ID: " . $version->getServiceId() . "\n";
    echo "PID: " . $version->getPid() . "\n\n";
    
    echo "Version Information:\n";
    echo "Version: " . $version->getVersion() . "\n";
    echo "Major: " . $version->getMajor() . "\n";
    echo "Minor: " . $version->getMinor() . "\n";
    echo "Revision: " . $version->getRevision() . "\n\n";
    
    // Version checks
    echo "Version Comparison:\n";
    echo "Is 4.0.0 or newer: " . ($version->isNewerThan('4.0.0') ? 'Yes' : 'No') . "\n";
    echo "Is exactly 4.0.0: " . ($version->isVersion('4.0.0') ? 'Yes' : 'No') . "\n";
    echo "Is older than 5.0.0: " . ($version->isOlderThan('5.0.0') ? 'Yes' : 'No') . "\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}