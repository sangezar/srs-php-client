<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get process statistics
    $stats = $client->getSelfProcStats();
    
    echo "SRS Process Statistics:\n";
    echo "=====================\n\n";
    
    echo "Server Information:\n";
    echo "Server ID: " . $stats->getServer() . "\n";
    echo "Service ID: " . $stats->getService() . "\n";
    echo "PID: " . $stats->getPid() . "\n";
    echo "Status: " . ($stats->isOk() ? 'OK' : 'Error') . "\n\n";

    echo "Process Information:\n";
    echo "Command: " . $stats->getCommand() . "\n";
    echo "State: " . $stats->getState() . "\n";
    echo "Process ID: " . $stats->getProcessId() . "\n";
    echo "Parent PID: " . $stats->getParentPid() . "\n";
    echo "Process Group: " . $stats->getProcessGroup() . "\n";
    echo "Session ID: " . $stats->getSession() . "\n";
    echo "TTY: " . $stats->getTtyNr() . "\n";
    echo "TPGID: " . $stats->getTpgid() . "\n\n";

    echo "CPU Usage:\n";
    echo "CPU Percentage: " . number_format($stats->getPercent(), 2) . "%\n";
    echo "User Time: " . $stats->getUserTime() . " ticks\n";
    echo "System Time: " . $stats->getSystemTime() . " ticks\n";
    echo "Children User Time: " . $stats->getChildrenUserTime() . " ticks\n";
    echo "Children System Time: " . $stats->getChildrenSystemTime() . " ticks\n";
    echo "Guest Time: " . $stats->getGuestTime() . " ticks\n";
    echo "Children Guest Time: " . $stats->getCguestTime() . " ticks\n\n";

    echo "Memory Usage:\n";
    echo "Virtual Memory Size: " . number_format($stats->getVirtualMemorySize()) . " bytes\n";
    echo "RSS: " . number_format($stats->getRss()) . " pages\n";
    echo "RSS Limit: " . number_format($stats->getRssLimit()) . "\n\n";

    echo "Faults:\n";
    echo "Minor Faults: " . number_format($stats->getMinorFaults()) . "\n";
    echo "Major Faults: " . number_format($stats->getMajorFaults()) . "\n";
    echo "Children Minor Faults: " . number_format($stats->getChildrenMinorFaults()) . "\n";
    echo "Children Major Faults: " . number_format($stats->getChildrenMajorFaults()) . "\n\n";

    echo "Scheduling:\n";
    echo "Priority: " . $stats->getPriority() . "\n";
    echo "Nice Value: " . $stats->getNice() . "\n";
    echo "Number of Threads: " . $stats->getNumThreads() . "\n";
    echo "RT Priority: " . $stats->getRtPriority() . "\n";
    echo "Policy: " . $stats->getPolicy() . "\n\n";

    echo "Memory Map:\n";
    echo "Start Code: 0x" . dechex($stats->getStartCode()) . "\n";
    echo "End Code: 0x" . dechex($stats->getEndCode()) . "\n";
    echo "Start Stack: 0x" . dechex($stats->getStartStack()) . "\n\n";

    echo "Signals:\n";
    echo "Pending: " . $stats->getSignal() . "\n";
    echo "Blocked: " . $stats->getBlocked() . "\n";
    echo "Ignored: " . $stats->getSigignore() . "\n";
    echo "Caught: " . $stats->getSigcatch() . "\n";
    echo "Exit Signal: " . $stats->getExitSignal() . "\n";

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 