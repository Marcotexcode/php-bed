<?php declare(strict_types=1);

    require_once '../App/Class/Product.php';
    require_once '../App/Class/Subscriber.php';

    $productClass = new Product();
    $subscriberClass = new Subscriber();

    require_once '../connection.php';
    session_start();

    try {
        $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
        $productId = (int)($_GET['id'] ?? 0);
        $notifySubscribers = false;

        // Check post
        $action = $_POST['action'] ?? null;

        switch($action)  {
            case 'save':
                $sku = $_POST['sku'];
                $description = $_POST['description'];
                $name = $_POST['name'];
                $price = (double)$_POST['price'];
                $qty = (int)$_POST['qty'];

                if ($qty > 0) {
                    // Check if this changed from 0
                    if ((int)$_POST['qty_orig'] === 0) {
                        $notifySubscribers = true;
                    }
                }

                $params = [$sku, $name, $description, $price, $qty];
                if ($productId) {

                    $query = $pdo->prepare('SELECT * FROM products WHERE sku=? AND entity_id !=?');
                    $query->execute([$sku, $productId]);

                    if(count($query->fetchAll(PDO::FETCH_ASSOC))) {
                        $_SESSION['message'] = 'Product already inserted';
                        $_SESSION['success'] = 'danger';

                        header("location: ./product.php?id=$productId");
                    } else {

                        //   Chiamo la classe per salvare i dati. 
                        // $productClass->update($params, $productId);
                        $query = $pdo->prepare('UPDATE products SET sku=?, name=?, description=?, price=?, quantity=? WHERE entity_id=?');
                        $params[] = $productId;
                        
                        $_SESSION['message'] = 'Modified product';
                        $_SESSION['success'] = 'success';

                        header("location: ./index.php");
                    }

                } else {

                    $query = $pdo->prepare('SELECT * FROM products WHERE sku=?');
                    $query->execute([$sku]);

                    if(count($query->fetchAll(PDO::FETCH_ASSOC))) {
                        $_SESSION['message'] = 'Product already inserted';
                        $_SESSION['success'] = 'danger';

                        header("location: ./product.php?id=");
                    } else {

                        // Chiamo la classe per salvare i dati. 
                        $productClass->save($params);

                        header("location: ./index.php");
                    }
                }
                $query->execute($params);
                if (!$productId) {
                    $productId = $pdo->lastInsertId();
                }

                break;

            case 'delete':

                // Chiamo la classe per eliminare i dati. 
                $productClass->delete($productId);

                header("location: ./index.php");
                break;

            default:
        }

        $subscribers = [];
        $product = [
            'entity_id' => 0,
            'sku' => '',
            'description' => '',
            'name' => '',
            'price' => 0,
            'quantity' => 0,
        ];

        // Load data if product id is provided
        if ($productId) {
            $query = $pdo->prepare('SELECT * FROM products p WHERE p.entity_id=?');
            $query->execute([$productId]);
            $product = $query->fetch(PDO::FETCH_ASSOC);
            if (!$product) {
                throw new \Exception('Product not found');
            }

            $query = $pdo->prepare('SELECT * FROM subscribers WHERE product_id=?');
            $query->execute([$product['entity_id']]);
            $subscribers = $query->fetchAll(PDO::FETCH_ASSOC);

            if (count($subscribers) && $notifySubscribers) {
                // TODO send an email to notify customers

                // I take all the subscribers of that product. 
                $mailSubscribers = [];
                foreach($subscribers as $subscriber){
                    $mailSubscribers[] = $subscriber['email'];
                }

                // I create message of success.
                $_SESSION['message'] = 'E-mail sent to: ' . implode( ', ',  $mailSubscribers). '.';
                $_SESSION['success'] = 'success';

                // Class delete subscriber;
                $subscriberClass->deleteSubProduct($productId);

                $query = $pdo->prepare('DELETE FROM subscribers WHERE product_id=?');
                $query->execute([$product['entity_id']]);
            }
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }