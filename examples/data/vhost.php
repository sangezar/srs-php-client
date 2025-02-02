<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get list of all virtual hosts
    $vhosts = $client->getVhosts();
    
    echo "Virtual Hosts: " . count($vhosts) . "\n";
    echo "==============\n\n";

    foreach ($vhosts as $vhost) {
        echo "VHost Information:\n";
        echo "ID: " . $vhost->getId() . "\n";
        echo "Name: " . $vhost->getName() . "\n";
        echo "Status: " . ($vhost->isEnabled() ? 'Enabled' : 'Disabled') . "\n";
        
        echo "\nTraffic Statistics:\n";
        echo "Connected Clients: " . $vhost->getClients() . "\n";
        echo "Active Streams: " . $vhost->getStreams() . "\n";
        echo "Received: " . number_format($vhost->getRecvBytes()) . " bytes\n";
        echo "Sent: " . number_format($vhost->getSendBytes()) . " bytes\n";
        echo "Total Bandwidth: " . number_format($vhost->getTotalBytes()) . " bytes\n";
        
        echo "\nBandwidth Usage (30s average):\n";
        echo "Receive Rate: " . $vhost->getRecvKbps() . " Kbps\n";
        echo "Send Rate: " . $vhost->getSendKbps() . " Kbps\n";
        echo "Total Rate: " . $vhost->getTotalKbps() . " Kbps\n";
        
        echo "\nHLS Configuration:\n";
        echo "HLS Enabled: " . ($vhost->isHlsEnabled() ? 'Yes' : 'No') . "\n";
        if ($vhost->isHlsEnabled()) {
            echo "HLS Fragment: " . $vhost->getHlsFragment() . " seconds\n";
        }
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }

    // Get information about specific virtual host
    if (count($vhosts) > 0) {
        $vhostName = $vhosts[0]->getName();
        echo "Detailed information for vhost '{$vhostName}':\n";
        $detailedVhost = $client->getVhost($vhostName);
        if ($detailedVhost) {
            echo "Total Clients: " . $detailedVhost->getClients() . "\n";
            echo "Total Streams: " . $detailedVhost->getStreams() . "\n";
            echo "Current Bandwidth: " . $detailedVhost->getTotalKbps() . " Kbps\n";
        } else {
            echo "VHost not found\n";
        }
    }

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 