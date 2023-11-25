<?php 
/*******w******** 
    
    Name: Khoi Dong
    Date: Sep 25
    Description: Module 3 Assignment - SCRUD

****************/
require('connect.php');

if (isset($_GET['id'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
    // Sanitize the id. Like above but this time from INPUT_GET.
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM cars WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $cars = $statement->fetch();
} else {
    $id = false; // False if we are not UPDATING or SELECTING.
}

?>

<?php if ($id): ?>
        <div class="container">
        <?php include('nav.php') ?>
            <div class="w-50 p-3">
                <h1 class="title-link"><?=$cars['title']?></h1>
                <p>Posted at <?=$cars['datepost']?> <a class="card-link" href="edit.php?id=<?=$cars['id']?>">edit</a></p>
                <?php if($cars['image']){ ?>
                    <img src="uploads/<?=$cars['image']?>" alt="">
                <?php } else { ?>
                    <img class="card-img-top" src="images/no_image.png" />
                <?php } ?>
                <p>Make: <?=$cars['make']?></p>
                <p>Model: <?=$cars['model']?></p>
                <p>Year: <?=$cars['year_made']?></p>
                <p>Odometer: <?=$cars['odometer']?></p>
                <p>Price: <?=$cars['price']?> $CAN</p>
                <div id="description">Description: <?=$cars['description']?></div><br>
                <?php else: ?>
                <p>No car selected. <a href="?id=1">Try this link</a>.</p>
            </div> 
        </div>
    <?php endif ?>
<?php include('footer.php') ?>
