<?php
    require_once '../App/Class/Product.php';

    $productClass = new Product();

    $products = $productClass->index();
?>

<?php require_once 'partials/htmlHead.php'; ?>
    <body>
        <?php require_once 'partials/top_bar.php'; ?>

        <div class="container my-5">
            <!-- Add message -->
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['success'] ?> alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <?php echo $_SESSION['message'] ?>
                </div>
            <?php endif ?>

            <div class="card">
                <div class="card-header">
                    <a class="btn btn-success" href="product.php">Add product</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <?php if ($products): ?>
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Subscribers</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <th scope="row"><?= $product['entity_id'] ?></th>
                                        <td><?= $product['sku'] ?></td>
                                        <td><?= $product['name'] ?></td>
                                        <td><?= number_format($product['price'], 2) ?> €</td>
                                        <td><?= $product['quantity'] ?></td>
                                        <td><?= $product['subscribers'] ?></td>
                                        <td><a class="btn btn-warning" href="product.php?id=<?= $product['entity_id'] ?>">Edit</a></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        <?php else: ?>
                            <div class="text-center">No product</div>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>

<!-- Clear session -->
<?php unset($_SESSION['message'], $_SESSION['success']); ?>