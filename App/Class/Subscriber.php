<?php declare(strict_types=1);

require_once '../vendor/autoload.php';
class Subscriber{
    
    private $dsn;
    private $options;
    private $pdo;
    
    function __construct() {
        // Load env vars
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../../.secrets');
        $dotenv->load();
    
        // Connect to the Database
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $charset = $_ENV['DB_CHARSET'] ?: 'utf8mb4';
    
        $this->dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($this->dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $this->options);
    }

    public function save($productId, $email) {

        $query = $this->pdo->prepare('INSERT INTO subscribers (product_id, email) VALUES (?, ?)');
        $query->execute([$productId, $email]);
    }

    public function delete($entityId) {

        $query = $this->pdo->prepare('DELETE FROM subscribers WHERE entity_id=?');
        $query->execute([$entityId]);
    }

    public function deleteSubProduct($productId) {

        $query = $this->pdo->prepare('DELETE FROM subscribers WHERE product_id=?');
        $query->execute([$productId]);
    }
}
