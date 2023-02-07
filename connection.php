<?php declare(strict_types=1);
    require_once 'vendor/autoload.php';
    // Load env vars
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.secrets');
    $dotenv->load();

    // Connect to the Database
    $host = $_ENV['DB_HOST'];
    $db = $_ENV['DB_NAME'];
    $charset = $_ENV['DB_CHARSET'] ?: 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
