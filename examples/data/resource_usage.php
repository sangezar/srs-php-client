<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get resource usage information
    $usage = $client->getResourceUsage();
    
    echo "SRS Resource Usage Information:\n";
    echo "============================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $usage->getServer() . "\n";
    echo "Service ID: " . $usage->getService() . "\n";
    echo "PID: " . $usage->getPid() . "\n";
    echo "Status: " . ($usage->isOk() ? 'OK' : 'Error') . "\n\n";

    echo "CPU Usage:\n";
    echo "User Time: " . $usage->getUserTime() . " ms\n";
    echo "System Time: " . $usage->getSystemTime() . " ms\n";
    echo "Total CPU Time: " . ($usage->getUserTime() + $usage->getSystemTime()) . " ms\n\n";

    echo "Memory Usage:\n";
    echo "Maximum RSS: " . number_format($usage->getMaxRss()) . " KB\n";
    echo "Shared Memory: " . number_format($usage->getSharedMemory()) . " KB\n";
    echo "Unshared Data: " . number_format($usage->getUnsharedData()) . " KB\n";
    echo "Unshared Stack: " . number_format($usage->getUnsharedStack()) . " KB\n\n";

    echo "Page Faults:\n";
    echo "Minor Faults: " . number_format($usage->getMinorFaults()) . "\n";
    echo "Major Faults: " . number_format($usage->getMajorFaults()) . "\n";
    echo "Swaps: " . number_format($usage->getSwaps()) . "\n\n";

    echo "I/O Operations:\n";
    echo "Input Operations: " . number_format($usage->getInputOperations()) . "\n";
    echo "Output Operations: " . number_format($usage->getOutputOperations()) . "\n\n";

    echo "IPC Statistics:\n";
    echo "Messages Sent: " . number_format($usage->getMessagesSent()) . "\n";
    echo "Messages Received: " . number_format($usage->getMessagesReceived()) . "\n";
    echo "Signals Received: " . number_format($usage->getSignalsReceived()) . "\n\n";

    echo "Context Switches:\n";
    echo "Voluntary: " . number_format($usage->getVoluntaryContextSwitches()) . "\n";
    echo "Involuntary: " . number_format($usage->getInvoluntaryContextSwitches()) . "\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 