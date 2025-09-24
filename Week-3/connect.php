<?php
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "[REMOVED]";
    $db_name = "web_week3";

    $connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($connection->connect_error) {
        die("Error connecting to database: ". $connection->connect_error);
    }

    $create_table_sql = "
    CREATE TABLE IF NOT EXISTS `users` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";

    if ($connection->query($create_table_sql) === TRUE) {
        // table ready
    } else {
        die("Error creating users table: " . $connection->error);
    }
?>