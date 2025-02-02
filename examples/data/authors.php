<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get information about authors
    $authors = $client->getAuthors();
    
    echo "SRS Authors Information:\n";
    echo "=====================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $authors->getServer() . "\n";
    echo "Service ID: " . $authors->getService() . "\n";
    echo "PID: " . $authors->getPid() . "\n\n";

    echo "License Information:\n";
    echo "License: " . $authors->getLicense() . "\n";
    echo "Contributors: " . $authors->getContributorsUrl() . "\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 