<?php 

    require_once '../core/init.php';

    $id = $_POST['id'];
    $id = (int)$id;
    $sql = "SELECT * FROM books WHERE id = '$id'";
    $result = $db->query($sql);
    $book = mysqli_fetch_assoc($result);

    $author_id = $book['author'];
    $sql = "SELECT author FROM author WHERE id = '$author_id'";
    $author_query = $db->query($sql);
    $author = mysqli_fetch_assoc($author_query);

    $category_id = $book['category'];
    $sql = "SELECT category FROM category WHERE id = '$category_id'";
    $category_query = $db->query($sql);
    $category = mysqli_fetch_assoc($category_query);
?> 


<?php ob_start(); ?>

    <!-- Details Modal -->
    <div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" onclick="closeModal()"  aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-center"><?= $book['title']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="center-block">
                                    <img src="<?= $book['image']; ?>" alt="<?= $book['title']; ?>" class="details img-responsive"> 
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4>About Book</h4>
                                <p><?= nl2br($book['about_book']); ?></p>
                                <hr>
                                <p>Author: <?= $author['author']; ?></p>
                                <p>Category: <?= $category['category']; ?></p>
                                <p>Price: <?= 'GH¢ '.$book['price']; ?></p>
                                <hr>
                                <form action="add_cart.php" method="post">
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Quantity</label>
                                            <input type="text" class="form-control" id="quantity" name="quantity">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" onclick="closeModal()">Close</button>
                    <button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function closeModal(){
            jQuery('#details-modal').modal('hide');
            setTimeout(function() {
                jQuery('#details-modal').remove();
                jQuery('.modal-backdrop').remove();
            }, 500);
        }
    </script>

<?php echo ob_get_clean(); ?>