<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get memory information
    $meminfo = $client->getMeminfos();
    
    echo "Memory Information:\n";
    echo "==================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $meminfo->getServer() . "\n";
    echo "Service ID: " . $meminfo->getService() . "\n";
    echo "PID: " . $meminfo->getPid() . "\n";
    echo "Status: " . ($meminfo->isOk() ? 'OK' : 'Error') . "\n\n";

    echo "Memory Usage:\n";
    echo "Total Memory: " . number_format($meminfo->getMemTotal()) . " KB\n";
    echo "Free Memory: " . number_format($meminfo->getMemFree()) . " KB\n";
    echo "Active Memory: " . number_format($meminfo->getMemActive()) . " KB\n";
    echo "Real In Use: " . number_format($meminfo->getRealInUse()) . " KB\n";
    echo "Not In Use: " . number_format($meminfo->getNotInUse()) . " KB\n";
    echo "Buffers: " . number_format($meminfo->getBuffers()) . " KB\n";
    echo "Cached: " . number_format($meminfo->getCached()) . " KB\n";
    echo "Memory Usage: " . number_format($meminfo->getMemoryUsagePercent(), 2) . "%\n";
    echo "Real Memory Usage: " . number_format($meminfo->getRealMemoryUsage()) . " KB\n\n";

    echo "Swap Usage:\n";
    echo "Total Swap: " . number_format($meminfo->getSwapTotal()) . " KB\n";
    echo "Free Swap: " . number_format($meminfo->getSwapFree()) . " KB\n";
    echo "Swap Usage: " . number_format($meminfo->getSwapUsagePercent(), 2) . "%\n\n";

    echo "Usage Percentages:\n";
    echo "RAM Usage: " . number_format($meminfo->getPercentRam(), 2) . "%\n";
    echo "Swap Usage: " . number_format($meminfo->getPercentSwap(), 2) . "%\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 