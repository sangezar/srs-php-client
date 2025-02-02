<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get information about SRS features
    $features = $client->getFeatures();
    
    echo "SRS Features Information:\n";
    echo "=======================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $features->getServer() . "\n";
    echo "Service ID: " . $features->getService() . "\n";
    echo "PID: " . $features->getPid() . "\n";
    echo "Status: " . ($features->getCode() === 0 ? 'OK' : 'Error') . "\n\n";

    echo "Build Information:\n";
    echo "Build Date: " . $features->getBuild() . "\n";
    echo "Build ID: " . $features->getBuild2() . "\n\n";

    echo "Options:\n";
    echo "Primary Options: " . $features->getOptions() . "\n";
    echo "Secondary Options: " . $features->getOptions2() . "\n\n";

    echo "Enabled Features:\n";
    echo "SSL: " . ($features->isSslEnabled() ? 'Yes' : 'No') . "\n";
    echo "HLS: " . ($features->isHlsEnabled() ? 'Yes' : 'No') . "\n";
    echo "HDS: " . ($features->isHdsEnabled() ? 'Yes' : 'No') . "\n";
    echo "Callback: " . ($features->isCallbackEnabled() ? 'Yes' : 'No') . "\n";
    echo "API: " . ($features->isApiEnabled() ? 'Yes' : 'No') . "\n";
    echo "HTTPD: " . ($features->isHttpdEnabled() ? 'Yes' : 'No') . "\n";
    echo "DVR: " . ($features->isDvrEnabled() ? 'Yes' : 'No') . "\n";
    echo "Transcode: " . ($features->isTranscodeEnabled() ? 'Yes' : 'No') . "\n";
    echo "Ingest: " . ($features->isIngestEnabled() ? 'Yes' : 'No') . "\n";
    echo "Statistics: " . ($features->isStatEnabled() ? 'Yes' : 'No') . "\n";
    echo "Caster: " . ($features->isCasterEnabled() ? 'Yes' : 'No') . "\n";
    echo "Complex Send: " . ($features->isComplexSendEnabled() ? 'Yes' : 'No') . "\n";
    echo "TCP No Delay: " . ($features->isTcpNodelayEnabled() ? 'Yes' : 'No') . "\n";
    echo "SO Send Buffer: " . ($features->isSoSendbufEnabled() ? 'Yes' : 'No') . "\n";
    echo "MR: " . ($features->isMrEnabled() ? 'Yes' : 'No') . "\n";

    // Output all features as is
    echo "\nRaw Features:\n";
    print_r($features->getFeatures());

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 