<?php 

require_once '../vendor/autoload.php';

class Product{
    
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

    public function index(){
        
        try {
            $query = $this->pdo->query('SELECT p.*, count(s.product_id) as subscribers FROM products p LEFT JOIN subscribers s ON p.entity_id=s.product_id GROUP BY p.entity_id ORDER BY entity_id ASC');
    
            while ($row = $query->fetch())
            {
                $products[] = $row;
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $products;
    }

    public function save($params){

        $query = $this->pdo->prepare('INSERT INTO products (sku, name, description, price, quantity) VALUES (?, ?, ?, ?, ?)');
        $query->execute($params);

        $_SESSION['message'] = 'Product added';
        $_SESSION['success'] = 'success';
    }

    public function update($params, $productId) {

        $params[] = $productId;
        $query = $this->pdo->prepare('UPDATE products SET sku=?, name=?, description=?, price=?, quantity=? WHERE entity_id=?');
        $query->execute($params);
    }

    public function delete($productId) {

        $query = $this->pdo->prepare('DELETE FROM products WHERE entity_id=?');
        $query->execute([$productId]);

        $_SESSION['message'] = 'Product eliminated';
        $_SESSION['success'] = 'danger';
    }
}
