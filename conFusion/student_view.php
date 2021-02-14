<?php

    $conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));
    session_start();
    //get student id
    $student_id = $_SESSION['user_id'];
?>

<?php
// connect to the database
//$conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));

// Uploads files
if (isset($_POST['upload'])) { // if save button on the form is clicked

    $count = count(array_filter($_FILES['file_assign']['name']));

    //echo $count;
        

    //get task_id
    $task_id = $_POST['id_of_task'];

    // get the task name
    $task_name = $_POST['name_of_task'];

    // get the description
    $task_description = trim($_POST['remarks']);

    if($task_description == ""){
        $task_description = "--no-remarks--";
    }

    //
    

    // update just taskname and description
    $sql = "INSERT INTO submissions (task_name, student_remarks, no_of_files, student_id, task_id) VALUES ('$task_name', '$task_description', '$count', '$student_id', '$task_id')";

    if(mysqli_query($conn, $sql)){
        //success
    }

    //using loop to get multiple files
    for($i=0; $i<$count; $i++){
        //get file name
        $filename = $_FILES['file_assign']['name'][$i];

        // destination of the file on the server
        $destination = 'submissions/' . $filename;

        // the physical file on a temporary uploads directory on the server
        //$file = $_FILES['file']['tmp_name'][$i];

        if ($_FILES['file_assign']['size'][$i] > 10000000) { // file shouldn't be larger than 10Megabyte
            //change the code here to alert the user about failure
            echo "File too large!";
        } else {
            // move the uploaded (temporary) file to the specified destination
            if (move_uploaded_file($_FILES['file_assign']['tmp_name'][$i], $destination)) {
                $sql = "UPDATE submissions SET file_name_$i = '$filename' WHERE task_id = '$task_id' and student_id = '$student_id'";
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
    unset($_FILES['file_assign']);
} 

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="coolcss.css">
    </head>
    <body>
        <header>
            <nav>

                <div>
                    <p><?php /*echo $_SESSION['user_id']*/ ?></p>
                </div>  
            </nav>  
        </header>
        <main id="student_main">
        <div id="display-added-tasks">
            <?php
                //code to dynamically change the html after sql success
                if(true){   
                    $ret_stmt = $conn->prepare("SELECT * FROM tasks") ;
                    $ret_stmt->execute();
                    $result = $ret_stmt->get_result();
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $check_stmt = $conn->prepare("SELECT * from submissions WHERE task_id=? and student_id=?");
                            $check_stmt->bind_param('ss', $row['id'], $student_id);
                            $check_stmt->execute();
                            $result_check = $check_stmt->get_result();
                            if($result_check->num_rows > 0){
                                //SKIPPING THIS BCZ THIS TASK WAS SUBMITTED
                                continue;
                            }
                            ?>
                            <div class="display_task">
                                <div class="display_task_name"><h3 class="text-back"><?php echo $row['task_name'];?><h3></div>
                                <div class="display_description"><?php echo $row['task_description'];?></div>
                                <div class="upload_button">
                                    <div class="file_list">
                                            <a href="uploads/<?php echo $row['file_name_0'];?>"><?php echo $row['file_name_0'];?></a>
                                            <a href="uploads/<?php echo $row['file_name_1'];?>"><?php echo $row['file_name_1'];?></a>
                                            <a href="uploads/<?php echo $row['file_name_2'];?>"><?php echo $row['file_name_2'];?></a>
                                    </div>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo $row['id'];?>" name="id_of_task">
                                        <input type="hidden" value="<?php echo $row['task_name'];?>" name="name_of_task">
                                        <div class="desc_div bg-color-override">
                                            <label for="remarks">
                                                Remarks
                                            </label>
                                            <textarea id="remarks" name="remarks" rows="10" cols="100"></textarea>
                                        </div>
                                        <div class="buttons_list">
                                            <label for="file_assign" class="delete_button_color button shadow">Browse</label>
                                            <input type="file" name="file_assign[]" id="file_assign" multiple> 
                                            <input type="submit" value="Upload" name="upload" class="upload_button_color button shadow">
                                        </div>
                                        <div class="notice-for-student">
                                            <p>Upload not more than three files, Zip them incase of too many files...</p>
                                        </div>
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
    </body>
</html>

<?php $conn->close(); ?>