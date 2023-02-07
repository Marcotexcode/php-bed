<?php declare(strict_types=1);
    require_once '../connection.php';
    session_start();

    $products = [];
    try {
        $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);

        // Get products
        $query = $pdo->query('SELECT p.*, count(s.product_id) as subscribers FROM products p LEFT JOIN subscribers s ON p.entity_id=s.product_id GROUP BY p.entity_id ORDER BY entity_id ASC');

        while ($row = $query->fetch())
        {
            $products[] = $row;
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }