<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get server status information
    $summary = $client->getSummaries();
    
    echo "SRS Server Summary:\n";
    echo "==================\n";
    echo "Version: " . $summary->getVersion() . "\n";
    echo "Uptime: " . $summary->getUptimeDuration() . "\n\n";

    echo "Process Information:\n";
    echo "PID: " . $summary->getPid() . "\n";
    echo "Working Directory: " . $summary->getCwd() . "\n";
    echo "Command Line: " . $summary->getArgv() . "\n\n";

    echo "Resource Usage:\n";
    echo "Memory: " . $summary->getMemoryUsageFormatted() . "\n";
    echo "CPU Usage: " . $summary->getCpuPercent() . "%\n";
    echo "System Memory: " . $summary->getSystemMemoryFormatted() . "\n";
    echo "Load Average: " . $summary->getLoadAverageFormatted() . "\n\n";

    echo "CPU Information:\n";
    echo "Total CPUs: " . $summary->getCpuCount() . "\n";
    echo "Online CPUs: " . $summary->getCpusOnline() . "\n";
    echo "System CPU Usage: " . $summary->getSystemCpuPercent() . "%\n\n";

    echo "Disk Usage:\n";
    echo "Read Speed: " . $summary->getDiskReadKBps() . " KB/s\n";
    echo "Write Speed: " . $summary->getDiskWriteKBps() . " KB/s\n";
    echo "Disk Busy: " . $summary->getDiskBusyPercent() . "%\n\n";

    echo "Network Statistics:\n";
    echo $summary->getNetworkStatsFormatted() . "\n";
    echo "SRS Received: " . number_format($summary->getSrsRecvBytes()) . " bytes\n";
    echo "SRS Sent: " . number_format($summary->getSrsSendBytes()) . " bytes\n\n";

    echo "Connections:\n";
    echo "SRS Connections: " . $summary->getConnectionsSrs() . "\n";
    echo "System Connections: " . $summary->getConnectionsSys() . "\n";
    echo "- Established: " . $summary->getConnectionsSysEt() . "\n";
    echo "- Time Wait: " . $summary->getConnectionsSysTw() . "\n";
    echo "- UDP: " . $summary->getConnectionsSysUdp() . "\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 