<?php declare(strict_types=1);
    session_start();

    require_once '../connection.php';
    require_once '../App/Class/Subscriber.php';
    $subscriberClass = new Subscriber();
    
    try {
        $productId = (int)($_GET['product_id'] ?? 0);
        if (!$productId) {
            throw new \Exception('No product id provided');
        }

        $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
        
        $action = $_GET['action'] ?? null;

        
        if ($action === 'delete') {

            // Delete
            $entityId = (int)($_GET['id'] ?? 0);
            if (!$entityId) {
                throw new \Exception('No entity id provided');
            }
           
            // Class delete subscriber.
            $subscriberClass->delete($entityId);

            $_SESSION['message'] = 'Subscriber eliminated';
            $_SESSION['success'] = 'danger';

        } else {

            // Insert
            $email = $_POST['email'] ?? null;
            
            // TODO validate email
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            if (!$email) {
                throw new \Exception('Invalid email provided');
            }

            $query = $pdo->prepare('SELECT * FROM subscribers WHERE email=? AND product_id=?');
            $query->execute([$email, $productId]);
            $subscribers = $query->fetchAll(PDO::FETCH_ASSOC);

            if(count($subscribers)) {
                $_SESSION['message'] = 'Email already present';
                $_SESSION['success'] = 'danger';

            } else {

                // Class save subscriber.
                $subscriberClass->save($productId, $email);

                $_SESSION['message'] = 'Subscriber added';
                $_SESSION['success'] = 'success';
            }
        }

        header("location: ../view/product.php?id=$productId");
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
