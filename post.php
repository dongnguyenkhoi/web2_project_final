<?php

/*******w******** 
    
    Name: Khoi Dong
    Date: Sep 25
    Description: Module 3 Assignment - SCRUD

****************/

require('connect.php');
require('authenticate.php');
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    
    // Build an array of paths segment names to be joins using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    // The DIRECTORY_SEPARATOR constant is OS specific.
    return join(DIRECTORY_SEPARATOR, $path_segments);
 }
// file_type() - Checks the mime-type & extension of the uploaded file for "image-ness".
function file_type_image($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpg', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];        
    
    $actual_mime_type        = mime_content_type($temporary_path);
    $actual_file_extension   = strtolower(pathinfo($new_path, PATHINFO_EXTENSION));
    
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    
    return $file_extension_is_valid && $mime_type_is_valid;
}

$file_upload_detect = isset($_FILES['file']) && ($_FILES['file']['error'] === 0);
$upload_error_detected = isset($_FILES['file']) && ($_FILES['file']['error'] > 0);

if ($file_upload_detect) { 
    $filename        = $_FILES['file']['name'];
    $temp_path  = $_FILES['file']['tmp_name'];
    $new_path        = file_upload_path($filename);
    if (file_type_image($temp_path, $new_path)) {
        move_uploaded_file($temp_path, $new_path);
    }
}

if ($_POST && !empty($_POST['title']) && !empty($_POST['description'])) {
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $odometer = filter_input(INPUT_POST, 'odometer', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    
    
    if($file_upload_detect){
        $image = $_FILES['file']['name'];
        $query = "INSERT INTO cars(title, make, model, year_made, odometer, price, description, image) VALUES (:title, :make, :model, :year_made, :odometer, :price, :description, :image)";
        $statement = $db -> prepare($query);
        $statement ->bindValue(':image', $image);
    } else {
        $query = "INSERT INTO cars(title, make, model, year_made, odometer, price, description) VALUES (:title, :make, :model, :year_made, :odometer, :price, :description)";
        $statement = $db -> prepare($query);
    }
    
    //  Bind values to the parameters
    $statement ->bindValue(':title', $title);
    $statement ->bindValue(':make', $make);
    $statement ->bindValue(':model', $model);
    $statement ->bindValue(':year_made', $year, PDO::PARAM_INT );
    $statement ->bindValue(':odometer', $odometer, PDO::PARAM_INT);
    $statement ->bindValue(':price', $price);
    $statement ->bindValue(':description', $description);
    
    //  Execute the INSERT.
    //  execute() will check for possible SQL injection and remove if necessary
    if($statement ->execute()){
        echo "Success";
    }
    // Redirect after update.
    header("Location: index.php");
    exit;
}

if($_POST && empty($_POST['title']) || empty($_POST['description'])){
    echo "Please enter title and description for new post";
}

?>

<?php include('nav.php') ?>

<div class="container">
    <h2>Add New Car</h2>

<!-- Remember that alternative syntax is good and html inside php is bad -->
    <form action="post.php" method="post" enctype='multipart/form-data' style="max-width:500px;">
        <div class="mb-3">
            <label class="form-label" for="title" >Title:</label><br>
            <input class="form-control" type="text"  id="title" name="title" value=""><br>
        </div>
        <div class="mb-3">
            <label class="form-label" for="make">Make:</label><br>
            <input class="form-control" type="text" id="make" name="make" value=""><br>
        </div>
        <div class="mb-3">
            <label class="form-label" for="model">Model:</label><br>
            <input class="form-control" type="text" id="model" name="model" value=""><br>
        </div>
        <div class="mb-3">
            <label class="form-label" for="year">Year:</label><br>
            <input class="form-control" type="text" id="year" name="year" value=""><br>
        </div>
        <div class="mb-3">
            <label class="form-label" for="odometer">Odometer:</label><br>
            <input class="form-control" type="text" id="odometer" name="odometer" value=""><br>
        </div>
        <div class="mb-3">
            <label class="form-label" for="price">Price:</label><br>
            <input class="form-control" type="text" id="price" name="price" value=""><br>
        </div>
        <div>
            <label class="form-label" for="description">Description</label><br>
            <textarea class="form-control" name="description" cols="40" rows="10"></textarea><br>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" for="inputImage">Image</label>
            <input type="file" class="form-control" name="file" id="inputImage">
        </div>
        <input class="btn btn-primary" type="submit" name="submit" value="Submit">
    </form>
</div>

<?php include('footer.php') ?>