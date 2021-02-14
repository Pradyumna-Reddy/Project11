<?php

    $conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));
    
?>

<?php
if(isset($_POST['delete'])) { //if delete of task occurs
    //get the id of the task
    $id_deleted = $_POST['id_of_deleted'];

    //delete the record from database
    $delete_stmt = $conn->prepare("DELETE FROM submissions WHERE student_id = ? and task_id = ?");
    $delete_stmt->bind_param("ss", $id_deleted, $_POST['id_of_task']);
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
            <nav>

 
            </nav>  
        </header>
        <main id="submissions_main">
        <div id="display-added-submissions">
            <?php
                //code to dynamically change the html after sql success
                if(true){   
                    $ret_stmt = $conn->prepare("SELECT * FROM submissions") ;
                    $ret_stmt->execute();
                    $result = $ret_stmt->get_result();
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="display_task">
                                <div class="display_task_name">
                                    <h3 class="text-back"><?php echo $row['task_name'];?><h3>
                                    <h3 id="roll_no" class="text-back"><?php echo $row['student_id'];?><h3>
                                </div>
                                <div class="display_description"><?php echo $row['student_remarks'];?></div>
                                <?php 
                                if($row['no_of_files'] > 0){?>
                                    <div class="notice-for-student">
                                            <p>The files contain the submissions and assignments</p> 
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="upload_button">
                                    <div class="file_list">
                                            <a href="submissions/<?php echo $row['file_name_0'];?>"><?php echo $row['file_name_0'];?></a>
                                            <a href="submissions/<?php echo $row['file_name_1'];?>"><?php echo $row['file_name_1'];?></a>
                                            <a href="submissions/<?php echo $row['file_name_2'];?>"><?php echo $row['file_name_2'];?></a>
                                    </div>
                                    <form action="" method="POST">
                                        <input type="hidden" value="<?php echo $row['task_id'];?>" name="id_of_task">
                                        <input type="hidden" value="<?php echo $row['student_id'];?>" name="id_of_deleted">
                                        <input type="submit" value="Clear" name="delete" class="delete_button_color button shadow delete_button">
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