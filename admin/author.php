<?php
    require_once '../core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';

    // get catergories from database
    $sql = "SELECT * FROM author ORDER BY id";
    $results = $db->query($sql);
    $errors = array();

    // Edit author
    if(isset($_GET['edit']) && !empty($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id); 
        $sql = "SELECT * FROM author WHERE id = '$edit_id'";
        $edit_result = $db->query($sql);
        $eauthor = mysqli_fetch_assoc($edit_result);
    }

    // Delete author
    if(isset($_GET['delete']) && !empty($_GET['delete'])){
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id); 
        $sql = "DELETE FROM author WHERE id = '$delete_id'";
        $db->query($sql);
        header('Location: author.php');
    }

    // if add form is submitted
    if(isset($_POST['add_submit'])){
        $author = sanitize($_POST['author']);
        // Check ig author is blank
        if($_POST['author'] == ''){
            $errors[] .= 'You must enter a author!';
        }
        // Check if author exit in database
        $sql ="SELECT * FROM author WHERE author = '$author'";
        if(isset($_GET['edit'])){
            $sql ="SELECT * FROM author WHERE author = '$author' AND id != '$edit_id'";
        }
        $result = $db->query($sql);
        $count = mysqli_num_rows($result);
        if($count > 0){
            $errors[] .= $author.' already exist! Please input another author!';
        }

        // display errors
        if(!empty($errors)){
            echo display_errors($errors);
        }else{
            // add author to database
            $sql = "INSERT INTO author(author) VALUES ('$author')";
            if(isset($_GET['edit'])){
                $sql ="UPDATE author SET author = '$author' WHERE id = '$edit_id'";
            }
            $db->query($sql);
            header('Location: author.php');
        }
    }
?>

<h2 class="text-center">Authors</h2><hr>

<!-- author Form -->
<div class="text-center">
    <form action="author.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" class="form-inline" method="post">
        <div class="form-group">
            <?php 
            $author_value = '';
            if(isset($_GET['edit'])){
                $author_value = $eauthor['author'];
            }else{
                if(isset($_POST['author'])){
                    $author_value = sanitize($_POST['author']);
                }
            }?>
            <label for="author"><?=((isset($_GET['edit']))?'Edit':'Add An');?> Author</label>
            <input type="text" name="author" id="author" class="form-control" value="<?=$author_value;?>">
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Author" class="btn btn-success">
            <?php if(isset($_GET['edit'])):?>
                <a href="author.php" class="btn btn-danger">Cancel</a>
            <?php endif;?>
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed"> 
    <thead>
        <th></th>
        <th class="text-center">Authors</th>
        <th></th>
    </thead>
    <tbody>
        <?php while ($cat = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td><a href="author.php?edit=<?=$cat['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td><?= $cat['author'];?></td>
                <td><a href="author.php?delete=<?=$cat['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


<?php include 'includes/footer.php'; ?> 