<?php  require_once '../Controller/product.php'; ?>

<?php require_once 'partials/htmlHead.php'; ?>
    <body>
        <?php require_once 'partials/top_bar.php'; ?>
        <div class="container-fluid my-5">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            Actions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 my-4 d-grid gap-2">
                                    <a class="btn btn-outline-secondary" href="index.php">Return</a>
                                </div>
                                <?php if ($product['entity_id']): ?>
                                    <form class="d-grid gap-2" method="post">
                                        <input type="hidden" name="action" value="delete">
                                        <button onclick="return confirm('Delete user?')" type="submit" class="btn btn-outline-danger">Delete</button>
                                    </form>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <!-- Add message -->
                    <?php if (!empty($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['success'] ?> alert-dismissible">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <?php echo $_SESSION['message'] ?>
                        </div>
                    <?php endif ?>
                    <div class="card">
                        <div class="card-header">
                            <h2>Product: <?= $product['entity_id'] ?></h2>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3" >
                                    <label for="sku" class="form-label">SKU</label>
                                    <input required type="text" class="form-control" id="sku"  name="sku" value="<?= $product['sku'] ?>">
                                </div>
                                <div class="mb-3" >
                                    <label for="name" class="form-label">Name</label>
                                    <input required type="text" class="form-control" id="name"  name="name" value="<?= $product['name'] ?>">
                                </div>
                                <div class="mb-3" >
                                    <label for="description" class="form-label">Description</label>
                                    <input required type="text" class="form-control" id="description"  name="description" value="<?= $product['description'] ?>">
                                </div>
                                <div class="mb-3" >
                                    <label for="price" class="form-label">Price</label>
                                    <input required type="number" step="0.01" class="form-control" id="price"  name="price" value="<?= number_format($product['price'], 2) ?>">
                                </div>
                                <div class="mb-3" >
                                    <label for="qty" class="form-label">Qty</label>
                                    <input required type="hidden" id="qty_orig" name="qty_orig" value="<?= $product['quantity'] ?>">
                                    <input required type="number" class="form-control" id="qty" name="qty" value="<?= $product['quantity'] ?>"><br>
                                </div>
                                <input type="hidden" name="action" value="save">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                    <?php if ($product['entity_id']): ?>
                        <div class="card my-2">
                            <div class="card-header">
                                <h2>Subscribers</h2>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <?php if ($subscribers): ?>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subscribers as $subscriber): ?>
                                                <tr>
                                                    <td><?= $subscriber['entity_id'] ?></td>
                                                    <td><?= $subscriber['email']  ?></td>
                                                    <td>
                                                        <a onclick="return confirm('Delete subscriber?')" class="btn btn-danger" href="../Controller/subscriber.php?action=delete&id=<?= $subscriber['entity_id'] ?>&product_id=<?= $product['entity_id'] ?>">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    <?php else: ?>
                                        <div class="text-center">No subscribers</div>
                                    <?php endif ?>
                                </table>
                                <h4 class="mt-5">Add Subscribers</h4>
    
                                <form class="mt-3" method="post" action="../Controller/subscriber.php?product_id=<?= $product['entity_id'] ?>">
                                    <div class="form-group">
                                        <label for="sku">Email</label>
                                        <input required type="email" class="form-control" id="email" name="email">
                                    </div>
                                    <button type="submit" class="btn btn-primary my-3">Submit</button>
                                </form>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </body>
</html>
