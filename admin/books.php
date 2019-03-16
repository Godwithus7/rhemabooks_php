<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/rhemabooks/core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';

    $dbpath = '';

    if(isset($_GET['add']) || isset($_GET['edit'])){
        $categoryQuery = $db->query("SELECT * FROM category");
        $authorQuery = $db->query("SELECT * FROM author");

        $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
        $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
        $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
        $category = ((isset($_POST['category']) && $_POST['category'] != '')?sanitize($_POST['category']):'');
        $author = ((isset($_POST['author']) && $_POST['author'] != '')?sanitize($_POST['author']):'');
        $quantity = ((isset($_POST['quantity']) && $_POST['quantity'] != '')?sanitize($_POST['quantity']):'');
        $about_book = ((isset($_POST['about_book']) && $_POST['about_book'] != '')?sanitize($_POST['about_book']):'');
        $saved_image = '';

        if (isset($_GET['edit'])) {
            $edit_id = (int)$_GET['edit'];
            $book_results = $db->query("SELECT * FROM books WHERE id = '$edit_id'");
            $book = mysqli_fetch_assoc($book_results);
            if((isset($_GET['delete_image']))){
                $image_url = $_SERVER['DOCUMENT_ROOT'].$book['image'];echo $image_url;
                unlink($image_url);
                $db->query("UPDATE books SET image = '' WHERE id = '$edit_id'");
                header('Location: books.php?edit='.$edit_id);
            }
            $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$book['title']);
            $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$book['price']);
            $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$book['list_price']);
            $category = ((isset($_POST['category']) && $_POST['category'] != '')?sanitize($_POST['category']):$book['category']);
            $author = ((isset($_POST['author']) && $_POST['author'] != '')?sanitize($_POST['author']):$book['author']);
            $quantity = ((isset($_POST['quantity']) && $_POST['quantity'] != '')?sanitize($_POST['quantity']):$book['quantity']);
            $about_book = ((isset($_POST['about_book']) && $_POST['about_book'] != '')?sanitize($_POST['about_book']):$book['about_book']);
            $saved_image = (($book['image'] != '')?$book['image']:'');
            $dbpath = $saved_image;
        }

        if($_POST){
            $required = array('title', 'price', 'category', 'author', 'quantity', 'about_book');
            foreach($required as $field){
                if($_POST[$field]==''){
                    $errors[] = 'All fields with asterisk(*) are required!';
                    break;
                }
            }
            if(!empty($_FILES)){
                var_dump($_FILES);
                $image = $_FILES['image'];
                $name = $image['name'];
                $nameArray = explode('.',$name);
                $fileName = $nameArray[0];
                $fileExt = $nameArray[1];
                $mime = explode('/',$image['type']);
                $mimeType = $mime[0];
                $mimeExt = $mime[1];
                $tmpLoc = $image['tmp_name'];
                $fileSize = $image['size'];
                $allowed = array('png', 'jpg', 'jpeg', 'gif');
                $uploadName = md5(microtime()).'.'.$fileExt;
                $uploadPath = BASEURL.'images/uploads/'.$uploadName;
                $dbpath = '/rhemabooks/images/uploads/'.$uploadName;

                // Check if the file is a image
                if($mimeType != 'image'){
                    $errors[] = 'The file must be an image!';
                } //Cheking file extension
                if(!in_array($fileExt, $allowed)){
                    $errors[] = 'The file extension must be a png, jpg, jpeg or gif!';
                } //Checking file size
                if($fileSize > 15000000){
                    $errors[] = 'THe file size must be under 15MB.';
                }
                if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
                    $errors[] = 'File extension does not match the file.';
                }
            }
            if(!empty($errors)){
                echo display_errors($errors);
            }else{
                // upload file and insert into database
                move_uploaded_file($tmpLoc, $uploadPath);
                $insertSql = "INSERT INTO books (`title`, `price`, `list_price`, `category`, `author`, `quantity`, `about_book`, `image`) 
                VALUES ('$title', '$price', '$list_price', '$category', '$author', '$quantity', '$about_book', '$dbpath')";
                if(isset($_GET['edit'])){
                    $insertSql = "UPDATE books SET `title` = '$title', `price` = '$price', `list_price` = '$list_price', `category` = '$category', `author` = '$author', 
                    `quantity` = '$quantity', `about_book` = '$about_book', `image` = '$dbpath' WHERE id = '$edit_id'";
                }
                $db->query($insertSql);
                header('Location: books.php');
            }
        } 
?>
<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Book</h2><hr>
<div class="container">
	<div class="row">
        <div class="col-md-3"></div>
			<div class="col-md-6">
                <form action="books.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label for="title">Title*</label>
                        <input type="text" name="title" class="form-control" id="title" value="<?=$title?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="price">Price*</label>
                                <input type="text" name="price" class="form-control" id="price" value="<?=$price?>">
                            </div>
                        </div>

                        <div class="col-md-5 pull-right">
                            <div class="form-group">
                                <label for="list_price">List Price</label>
                                <input type="text" name="list_price" class="form-control" id="list_price" value="<?=$list_price?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="category">Category*</label>
                                <select name="category" id="category" class="form-control">
                                    <option value=""<?=(($category == '')?' selected':'');?>></option>
                                    <?php while($cat = mysqli_fetch_assoc($categoryQuery)):?>
                                        <option value="<?=$cat['id']?>"<?=(($category == $cat['id'])?' selected':'');?>><?=$cat['category'];?></option>
                                    <?php endwhile;?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5 pull-right">
                            <div class="form-group">
                                <label for="author">Author*</label>
                                <select name="author" id="author" class="form-control">
                                <option value=""<?=(($author == '')?' selected':'');?>></option>
                                    <?php while($auth = mysqli_fetch_assoc($authorQuery)):?>
                                        <option value="<?=$auth['id']?>"<?=(($author == $auth['id'])?' selected':'');?>><?=$auth['author'];?></option>
                                    <?php endwhile;?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php if($saved_image != ''):?>
                                    <div class="saved_image">
                                        <img src="<?=$saved_image;?>" alt="saved_image"><br>
                                        <a href="books.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
                                    </div>
                                    <?php else: ?>
                                    <label for="image">Book Image*</label>
                                    <input type="file" name="image" class="form-control" id="image">
                                <?php endif;?>
                            </div>
                        </div>

                        <div class="col-md-5 pull-right">
                            <div class="form-group">
                                <label for="quantity">Quantity*</label><br>
                                <input type="text" name="quantity" class="form-control" id="quantity" value="<?=$quantity?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="about_book">About Book*</label>
                        <textarea name="about_book" class="form-control" rows="5" id="about_book"><?=$about_book?></textarea>
                    </div>
                    
                    <div class="form-group col-md-3 pull-right">
                        <a href="books.php" class="form-control btn btn-danger">Cancel</a>
                    </div>
                    
                    <div class="form-group col-md-3 pull-right">
                        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Book" class="form-control btn btn-success">
                    </div>
                    
                </form>
            </div>
		<div class="col-md-3">	</div>
	</div>	
</div>

<?php }else{

    $sql = "SELECT * FROM books WHERE deleted = 0";
    $bresults = $db->query($sql);

    if(isset($_GET['instock'])){
        $id = (int)$_GET['id'];
        $instock = (int)$_GET['instock'];
        $instockSql = "UPDATE books SET instock = '$instock' WHERE id = '$id'";
        $db->query($instockSql);
        header('Location: books.php');
    }
?>

<h2 class="text-center">Books</h2>
<a href="books.php?add=1" class="btn btn-success pull-right" id="add-books-btn">Add Books</a><br>
<hr>

<table class="table table-bordered table-striped table-auto table-condensed"> 
    <thead>
        <th></th>
        <th>Book Title</th>
        <th>Price</th>
        <th>Category</th>
        <th>Author</th>
        <th>In Stock</th>
        <th>Sold</th>
    </thead>
    <tbody>
        <?php while ($books = mysqli_fetch_assoc($bresults)): 
            $categoryID = $books['category'];
            $categorySql = "SELECT * FROM category WHERE id = '$categoryID'";
            $results = $db->query($categorySql);
            $category = mysqli_fetch_assoc($results);
            
            $authorID = $books['author'];
            $authorSql = "SELECT * FROM author WHERE id = '$authorID'";
            $results = $db->query($authorSql);
            $author = mysqli_fetch_assoc($results);
        ?>
            <tr>
                <td>
                    <a href="books.php?edit=<?=$books['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="books.php?delete=<?=$books['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td><?=$books['title']?></td>
                <td><?=money($books['price'])?></td>
                <td><?=$category['category']?></td>
                <td><?=$author['author']?></td>
                <td><a href="books.php?instock=<?=(($books['instock'] == 0)?'1':'0');?> & id=<?=$books['id'];?>" class="btn btn-xs btn-default">
                    <span class="glyphicon glyphicon-<?=(($books['instock']==1)?'minus':'plus');?>"></span>
                    </a> &nbsp <?=(($books['instock']==1)?'In Stock' : 'Out Of Stock')?>
                </td>
                <td>0</td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php } include 'includes/footer.php'; ?> 