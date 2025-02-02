<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get list of clients (default 10)
    $clients = $client->getClients();
    
    echo "Active Clients: " . count($clients) . "\n";
    echo "===============\n\n";

    foreach ($clients as $srsClient) {
        echo "Client Information:\n";
        echo "ID: " . $srsClient->getId() . "\n";
        echo "Type: " . $srsClient->getType() . "\n";
        echo "Stream: " . $srsClient->getStream() . "\n";
        echo "IP Address: " . $srsClient->getIp() . "\n";
        
        echo "\nClient Type:\n";
        echo "Publisher: " . ($srsClient->isPublisher() ? 'Yes' : 'No') . "\n";
        echo "Player: " . ($srsClient->isPlayer() ? 'Yes' : 'No') . "\n";
        echo "HLS Player: " . ($srsClient->isHlsPlayer() ? 'Yes' : 'No') . "\n";
        
        echo "\nConnection Details:\n";
        echo "Active Time: " . $srsClient->getAliveDuration() . "\n";
        echo "Page URL: " . $srsClient->getPageUrl() . "\n";
        echo "SWF URL: " . $srsClient->getSwfUrl() . "\n";
        echo "TC URL: " . $srsClient->getTcUrl() . "\n";
        echo "URL: " . $srsClient->getUrl() . "\n";
        
        echo "\nBandwidth Usage:\n";
        echo "Send Rate: " . round($srsClient->getSendBitrateMbps(), 2) . " Mbps\n";
        echo "Receive Rate: " . round($srsClient->getRecvBitrateMbps(), 2) . " Mbps\n";
        echo "Total Bitrate: " . round($srsClient->getTotalBitrateMbps(), 2) . " Mbps\n";
        echo "Total Sent: " . number_format($srsClient->getSendBytes()) . " bytes\n";
        echo "Total Received: " . number_format($srsClient->getRecvBytes()) . " bytes\n";
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }

    // Get detailed information about first client
    if (count($clients) > 0) {
        $clientId = $clients[0]->getId();
        
        // Display additional information if needed
        $detailedClient = $client->getClient($clientId);
    }

    // Example filtering clients by type
    echo "\nPublishers:\n";
    foreach ($clients as $srsClient) {
        if ($srsClient->isPublisher()) {
            echo "- " . $srsClient->getId() . " (Stream: " . $srsClient->getStream() . ")\n";
        }
    }

    echo "\nPlayers:\n";
    foreach ($clients as $srsClient) {
        if ($srsClient->isPlayer()) {
            echo "- " . $srsClient->getId() . " (Stream: " . $srsClient->getStream() . ")\n";
        }
    }

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 