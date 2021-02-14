<?php
// connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));

// Uploads files
if (isset($_POST['submit'])) { // if save button on the form is clicked

    $countfiles = count(array_filter($_FILES['file']['name']));


    //echo $countfiles;

    // get the task name
    $task_name = $_POST['task_title'];

    // get the description
    $task_description = trim($_POST['description']);

    if($task_description == ""){
        $task_description = "--no-description--";
    }

    // update just taskname and description
    $sql = "INSERT INTO tasks (task_name, task_description, no_of_files) VALUES ('$task_name', '$task_description', '$countfiles')";

    mysqli_query($conn, $sql);

    //using loop to get multiple files
    for($i=0; $i<$countfiles; $i++){
        //get file name
        $filename = $_FILES['file']['name'][$i];

        // destination of the file on the server
        $destination = 'uploads/' . $filename;

        // the physical file on a temporary uploads directory on the server
        //$file = $_FILES['file']['tmp_name'][$i];

        if ($_FILES['file']['size'][$i] > 10000000) { // file shouldn't be larger than 10Megabyte
            //change the code here to alert the user about failure
            echo "File too large!";
        } else {
            // move the uploaded (temporary) file to the specified destination
            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $destination)) {
                $sql = "UPDATE tasks SET file_name_$i = '$filename' WHERE task_name = '$task_name'";
                if (mysqli_query($conn, $sql)) {
                    //database successfully updated, so we used header to get info
                }
            } 
            else {
                //change the code here to alert the user about failure
                echo "Failed to upload files.";
            }
        }

    }
    unset($_FILES['file']);
} 

?>

<?php
if(isset($_POST['delete'])) { //if delete of task occurs
    //get the id of the task
    $id_deleted = $_POST['id_of_deleted'];

    //delete the record from database
    $delete_stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $delete_stmt->bind_param("s",$id_deleted);
    $delete_stmt->execute();
    $delete_stmt->close();

}
?>





<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" href="coolcss.css">
    </head>
    <body>
        <header>

        </header>
        <main>
            <div id="add_task">
                <form name="task_adder_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                    <div id="title_div">
                        <label for="task_title">
                            Task Title
                        </label>
                        <input type="text" id="task_title" name="task_title" required>
                    </div>
                    <div class="desc_div">
                        <label for="description">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="10" cols="100"></textarea>
                    </div>
                    <div class="notice">
                        <p>Upload not more than three files...</p>
                    </div>
                    <div id="upload_buttons">
                    <label for="file" class="button_color button shadow">Upload</label>
                    <input type="file" name="file[]" id="file" multiple >
                    <input type="submit" name="submit" value="Finish and upload" class="button_color button shadow" >
                    </div>
                </form>
            </div>
            <div id="display-added-tasks">
            <?php
                //code to dynamically change the html after sql success
                if(true){   //just incase
                    $ret_stmt = $conn->prepare("SELECT * FROM tasks") ;
                    $ret_stmt->execute();
                    $result = $ret_stmt->get_result();
                    $k =0 ;
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {?>
                            <div class="display_task">
                                <div class="display_task_name"><h3 class="text-back"><?php echo $row['task_name'];?></h3></div>
                                <div class="display_description"><?php echo $row['task_description'];?></div>
                                <div class="delete_button">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                                        <input type="hidden" value="<?php echo $row['id'];?>" name="id_of_deleted">
                                        <input type="submit" value="Delete" name="delete" class="delete_button_color button shadow delete_button">
                                    </form>
                                </div>
                            </div>
                        <?php }
                    }
                    }

                ?>
            </div>
        </main>
        <footer>

        </footer>
        <script type="">

        </script>
    </body>
</html>

<?php $conn->close(); ?>