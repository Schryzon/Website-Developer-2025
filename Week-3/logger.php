<?php
    // logger.php
    declare(strict_types=1);

    /**
     * Simple logger utility.
     * Levels: info, warning, error
     */
    function log_msg(string $level, string $message): void {
        $timestamp = date('Y-m-d H:i:s');
        $line = "[$timestamp][$level] $message\n";
        
        // append to log file
        file_put_contents(__DIR__ . '/logs/bookstore.log', $line, FILE_APPEND);

        // optional: also write to PHP error log
        error_log($line);
    }

?>