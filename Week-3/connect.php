<?php
    require_once "logger.php";

    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "toko_buku";


    // connect
    $connection = new mysqli($db_host, $db_user, $db_pass);
    if ($connection->connect_error) {
        die("Error connecting to database server: " . $connection->connect_error);
    }

    // helper: run and check a statement
    function run_stmt($conn, $sql)
    {
        // show first 200 chars of SQL on error for easy debugging
        if ($conn->query($sql) !== TRUE) {
            $snippet = substr($sql, 0, 200);
            die("Error executing statement: " . $conn->error . "\nSQL (snippet): " . $snippet . "...\n");
        }
    }

    // statements to run in order
    $stmts = [];

    // create database and select it
    $stmts[] = "CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
    $stmts[] = "USE `{$db_name}`;";

    // authors
    $stmts[] = "CREATE TABLE IF NOT EXISTS `author` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL,
        `bio` TEXT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        INDEX (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // categories
    $stmts[] = "CREATE TABLE IF NOT EXISTS `category` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `slug` VARCHAR(100) NOT NULL,
        `name` VARCHAR(120) NOT NULL,
        `description` TEXT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // books
    $stmts[] = "CREATE TABLE IF NOT EXISTS `book` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `isbn` VARCHAR(20) NULL,
        `title` VARCHAR(255) NOT NULL,
        `subtitle` VARCHAR(255) NULL,
        `description` TEXT NULL,
        `author_id` INT UNSIGNED NULL,
        `category_id` INT UNSIGNED NULL,
        `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `currency` CHAR(3) NOT NULL DEFAULT 'IDR',
        `cover_image` VARCHAR(1024) NULL,
        `published_at` DATE NULL,
        `pages` INT UNSIGNED NULL,
        `language` VARCHAR(50) DEFAULT 'en',
        `slug` VARCHAR(255) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_isbn` (`isbn`),
        FOREIGN KEY (`author_id`) REFERENCES `author`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
        FOREIGN KEY (`category_id`) REFERENCES `category`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
        INDEX (`title`),
        INDEX (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // inventory
    $stmts[] = "CREATE TABLE IF NOT EXISTS `inventory` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `book_id` INT UNSIGNED NOT NULL,
        `sku` VARCHAR(100) NULL,
        `stock` INT NOT NULL DEFAULT 0,
        `warehouse` VARCHAR(255) NULL,
        `is_digital` TINYINT(1) NOT NULL DEFAULT 0,
        `price_override` DECIMAL(10,2) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`book_id`) REFERENCES `book`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX (`sku`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // users table (if not exists) — mirrors your earlier definition but guarded so we don't clobber an existing users table
    $stmts[] = "CREATE TABLE IF NOT EXISTS `users` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // ownership (user_book)
    $stmts[] = "CREATE TABLE IF NOT EXISTS `user_book` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NOT NULL,
        `book_id` INT UNSIGNED NOT NULL,
        `inventory_id` INT UNSIGNED NULL,
        `purchased_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `price_paid` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `currency` CHAR(3) NOT NULL DEFAULT 'IDR',
        `format` VARCHAR(50) NULL,
        `license_expires_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`book_id`) REFERENCES `book`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`inventory_id`) REFERENCES `inventory`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
        UNIQUE KEY `uniq_user_book` (`user_id`, `book_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // orders
    $stmts[] = "CREATE TABLE IF NOT EXISTS `orders` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NULL,
        `order_number` VARCHAR(50) NOT NULL,
        `status` ENUM('pending','paid','shipped','completed','cancelled','refunded') NOT NULL DEFAULT 'pending',
        `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        `currency` CHAR(3) NOT NULL DEFAULT 'IDR',
        `shipping_address` TEXT NULL,
        `billing_address` TEXT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_order_number` (`order_number`),
        CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";


    // order items
    $stmts[] = "CREATE TABLE IF NOT EXISTS `order_item` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `order_id` BIGINT UNSIGNED NOT NULL,
        `book_id` INT UNSIGNED NULL,
        `inventory_id` INT UNSIGNED NULL,
        `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
        `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_orderitem_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_orderitem_book` FOREIGN KEY (`book_id`) REFERENCES `book`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_orderitem_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";


    // payments
    $stmts[] = "CREATE TABLE IF NOT EXISTS `payment` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `order_id` BIGINT UNSIGNED NULL,
        `user_id` INT UNSIGNED NULL,
        `provider` VARCHAR(100) NULL,
        `provider_payment_id` VARCHAR(255) NULL,
        `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        `currency` CHAR(3) NOT NULL DEFAULT 'USD',
        `status` ENUM('pending','succeeded','failed','refunded') NOT NULL DEFAULT 'pending',
        `paid_at` TIMESTAMP NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        INDEX (`order_id`),
        INDEX (`user_id`),
        CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_payment_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";


    // reviews
    $stmts[] = "CREATE TABLE IF NOT EXISTS `review` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NOT NULL,
        `book_id` INT UNSIGNED NOT NULL,
        `rating` TINYINT UNSIGNED NOT NULL,
        `title` VARCHAR(255) NULL,
        `body` TEXT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`book_id`) REFERENCES `book`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        UNIQUE KEY `uniq_user_book_review` (`user_id`, `book_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // promotions
    $stmts[] = "CREATE TABLE IF NOT EXISTS `promotion` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `code` VARCHAR(50) NOT NULL,
        `description` TEXT NULL,
        `discount_percent` INT UNSIGNED NULL,
        `discount_amount` DECIMAL(10,2) NULL,
        `active_from` DATETIME NULL,
        `active_until` DATETIME NULL,
        `active` TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_code` (`code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // run all statements
    foreach ($stmts as $sql) {
        run_stmt($connection, $sql);
    }

    log_msg('INFO', 'Bookstore schema created / verified successfully.');

?>

