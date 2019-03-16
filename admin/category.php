<?php
    require_once '../core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';

    // get catergories from database
    $sql = "SELECT * FROM category ORDER BY id";
    $results = $db->query($sql);
    $errors = array();

    // Edit category
    if(isset($_GET['edit']) && !empty($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id); 
        $sql = "SELECT * FROM category WHERE id = '$edit_id'";
        $edit_result = $db->query($sql);
        $eCategory = mysqli_fetch_assoc($edit_result);
    }

    // Delete Category
    if(isset($_GET['delete']) && !empty($_GET['delete'])){
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id); 
        $sql = "DELETE FROM category WHERE id = '$delete_id'";
        $db->query($sql);
        header('Location: category.php');
    }

    // if add form is submitted
    if(isset($_POST['add_submit'])){
        $category = sanitize($_POST['category']);
        // Check ig category is blank
        if($_POST['category'] == ''){
            $errors[] .= 'You must enter a category!';
        }
        // Check if category exit in database
        $sql ="SELECT * FROM category WHERE category = '$category'";
        if(isset($_GET['edit'])){
            $sql ="SELECT * FROM category WHERE category = '$category' AND id != '$edit_id'";
        }
        $result = $db->query($sql);
        $count = mysqli_num_rows($result);
        if($count > 0){
            $errors[] .= $category.' already exist! Please input another category!';
        }

        // display errors
        if(!empty($errors)){
            echo display_errors($errors);
        }else{
            // add category to database
            $sql = "INSERT INTO category(category) VALUES ('$category')";
            if(isset($_GET['edit'])){
                $sql ="UPDATE category SET category = '$category' WHERE id = '$edit_id'";
            }
            $db->query($sql);
            header('Location: category.php');
        }
    }
?>

<h2 class="text-center">Category</h2><hr>

<!-- Category Form -->
<div class="text-center">
    <form action="category.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" class="form-inline" method="post">
        <div class="form-group">
            <?php 
            $category_value = '';
            if(isset($_GET['edit'])){
                $category_value = $eCategory['category'];
            }else{
                if(isset($_POST['category'])){
                    $category_value = sanitize($_POST['category']);
                }
            }?>
            <label for="category"><?=((isset($_GET['edit']))?'Edit':'Add A');?> Category</label>
            <input type="text" name="category" id="category" class="form-control" value="<?=$category_value;?>">
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Category" class="btn btn-success">
            <?php if(isset($_GET['edit'])):?>
                <a href="category.php" class="btn btn-danger">Cancel</a>
            <?php endif;?>
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed"> 
    <thead>
        <th></th>
        <th class="text-center">Category</th>
        <th></th>
    </thead>
    <tbody>
        <?php while ($cat = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td><a href="category.php?edit=<?=$cat['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td><?= $cat['category'];?></td>
                <td><a href="category.php?delete=<?=$cat['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?> 

