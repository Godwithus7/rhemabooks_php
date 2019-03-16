<?php 
    require_once 'core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';
    include 'includes/headerfull.php';
    include 'includes/leftbar.php';

    $sql = "SELECT * FROM books WHERE instock = 1";
    $bkquery = $db->query($sql);
?>
   
        <!-- Main content -->
        <div class="col-md-8">
            <div class="row">
                <h2 class="text-center">Available Books</h2>
                <?php while($books = mysqli_fetch_assoc($bkquery )) : ?>
                <div class="col-md-2 text-center">
                    <img src="<?= $books['image']; ?>" alt="<?= $books['title']; ?>" class="img-thumb"/>
                    <p class="list-price text-danger">List Price: <s><?= $books['list_price']; ?></s></p>
                    <p class="price">Our Price: <?= $books['price']; ?></p>
                    <button type="button" class="btn btn-sm btn-success" onclick = "detailsmodal(<?= $books['id']; ?>)">Details</button>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

<?php
    include 'includes/rightbar.php';
    include 'includes/footer.php';
?>