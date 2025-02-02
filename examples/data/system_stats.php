<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get system statistics
    $stats = $client->getSystemProcStats();
    
    echo "System CPU Statistics:\n";
    echo "====================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $stats->getServer() . "\n";
    echo "Service ID: " . $stats->getService() . "\n";
    echo "PID: " . $stats->getPid() . "\n";
    echo "Status: " . ($stats->isOk() ? 'OK' : 'Error') . "\n\n";

    echo "CPU Time Distribution:\n";
    echo "User Time: " . number_format($stats->getUserTime()) . " ticks\n";
    echo "Nice Time: " . number_format($stats->getNice()) . " ticks\n";
    echo "System Time: " . number_format($stats->getSystemTime()) . " ticks\n";
    echo "Idle Time: " . number_format($stats->getIdle()) . " ticks\n";
    echo "I/O Wait: " . number_format($stats->getIoWait()) . " ticks\n";
    echo "IRQ: " . number_format($stats->getIrq()) . " ticks\n";
    echo "Soft IRQ: " . number_format($stats->getSoftIrq()) . " ticks\n";
    echo "Steal: " . number_format($stats->getSteal()) . " ticks\n";
    echo "Guest: " . number_format($stats->getGuest()) . " ticks\n\n";

    echo "CPU Usage Summary:\n";
    echo "Total CPU Time: " . number_format($stats->getTotalTime()) . " ticks\n";
    echo "Active CPU Time: " . number_format($stats->getActiveTime()) . " ticks\n";
    echo "CPU Usage: " . number_format($stats->getCpuUsagePercent(), 2) . "%\n";
    echo "Current Sample CPU Usage: " . number_format($stats->getPercent(), 2) . "%\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 