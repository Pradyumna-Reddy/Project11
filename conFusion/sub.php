<?php
// connect to the database
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));

// Uploads files
if (isset($_POST['upload'])) { // if save button on the form is clicked


    $countfiles = count($_FILES['file_assign']['name']);

    //get task_id
    $task_id = $_POST['id_of_task'];

    // get the task name
    $task_name = $_POST['name_of_task'];

    // get the description
    $task_description = trim($_POST['remarks']);

    if($task_description == ""){
        $task_description = "--no-description--";
    }

    //get student id
    $student_id = $_SESSION['user_id'];

    // update just taskname and description
    $sql = "INSERT INTO submissions (task_name, student_remarks, no_of_files, student_id, task_id) VALUES ('$task_name', '$task_description', '$countfiles', '$student_id', '$task_id')";

    if(mysqli_query($conn, $sql)){
        echo "can insert";
    }

    //using loop to get multiple files
    for($i=0; $i<$countfiles; $i++){
        //get file name
        $filename = $_FILES['file1']['name'][$i];

        // destination of the file on the server
        $destination = 'submissions/' . $filename;

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
                    echo "can file";
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

<?php $conn->close(); ?>