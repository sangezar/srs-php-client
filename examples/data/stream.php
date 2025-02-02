<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get list of all streams
    $streams = $client->getStreams();
    
    echo "Active Streams: " . count($streams) . "\n";
    echo "================\n\n";

    foreach ($streams as $stream) {
        echo "Stream Information:\n";
        echo "ID: " . $stream->getId() . "\n";
        echo "Name: " . $stream->getName() . "\n";
        echo "Application: " . $stream->getApp() . "\n";
        echo "URL: " . $stream->getUrl() . "\n";
        echo "Active: " . ($stream->isActive() ? 'Yes' : 'No') . "\n";
        echo "Connected Clients: " . $stream->getClients() . "\n";
        echo "Duration: " . round($stream->getDurationInSeconds()) . " seconds\n";
        
        echo "\nVideo Configuration:\n";
        echo "Codec: " . $stream->getVideoCodec() . "\n";
        echo "Resolution: " . $stream->getVideoWidth() . "x" . $stream->getVideoHeight() . "\n";
        echo "Profile: " . $stream->getVideoProfile() . "\n";
        echo "Level: " . $stream->getVideoLevel() . "\n";
        
        echo "\nAudio Configuration:\n";
        echo "Codec: " . $stream->getAudioCodec() . "\n";
        echo "Sample Rate: " . $stream->getAudioSampleRate() . " Hz\n";
        echo "Channels: " . $stream->getAudioChannel() . "\n";
        echo "Profile: " . $stream->getAudioProfile() . "\n";
        
        echo "\nBandwidth Usage:\n";
        echo "Send Rate: " . $stream->getSendKbps() . " Kbps\n";
        echo "Receive Rate: " . $stream->getRecvKbps() . " Kbps\n";
        echo "Total Bitrate: " . round($stream->getBitrateKbps()) . " Kbps\n";
        echo "Total Sent: " . number_format($stream->getSendBytes()) . " bytes\n";
        echo "Total Received: " . number_format($stream->getRecvBytes()) . " bytes\n";
        
        echo "\nPublisher Information:\n";
        echo "Publisher ID: " . ($stream->getPublishClientId() ?? 'None') . "\n";
        echo "Publishing: " . ($stream->isActive() ? 'Yes' : 'No') . "\n";
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }

    if (count($streams) > 0) {
        // Get detailed information about first stream
        $streamId = $streams[0]->getId();
        echo "Detailed information for stream {$streamId}:\n";
        $stream = $client->getStream($streamId);
        // Display additional information if needed
    }

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 