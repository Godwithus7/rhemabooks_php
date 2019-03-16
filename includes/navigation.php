 <?php
    $sql = "SELECT * FROM category";
    $cquery = $db->query($sql);

    $sql2 = "SELECT * FROM author";
    $aquery = $db->query($sql2);
 ?>
 
 <!-- Top Nav Bar -->
 <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <a href="index.php" class="navbar-brand">RhemaBooks</a>
            <ul class="nav navbar-nav">
                <li>
                    <a href="index.php" class="nav-link-1" aria-haspopup="true" aria-expanded="false">Home</a>
                </li>
            </ul>
            <ul class="nav navbar-nav">
              

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Category<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                    <?php while ($cat = mysqli_fetch_assoc($cquery)): ?>
                        <li><a href=""><?= $cat['category'];?></a></li>
                    <?php endwhile; ?>
                    </ul>
                </li>
                   
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Authors<span class="caret"></span></a>
                    <ul class="dropdown-menu pre-scrollable" role="menu">
                    <?php while ($auth = mysqli_fetch_assoc($aquery)): ?>
                        <li><a href=""><?= $auth['author'];?></a></li>
                    <?php endwhile; ?>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li>
                    <a href="#" class="nav-link-1" aria-haspopup="true" aria-expanded="false">Contact Us</a>
                </li>
            </ul>
            <!-- <form class="form-inline" action="">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" name="keyword">
                <button class="btn btn-primary">Search</button>
            </form> -->
        </div>
    </nav>
