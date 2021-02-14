<?php  
    session_start();    
    include('conn.php');  
    $username = $_POST['uname'];  
    $password = $_POST['pass'];  
      
        //to prevent from mysqli injection  
        $username = stripcslashes($username);  
        $password = stripcslashes($password);  
        $username = mysqli_real_escape_string($con, $username);  
        $password = mysqli_real_escape_string($con, $password);  
        
        $sql = "select id from login where email = '$username' and pwd = '$password'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
        $id = $row['id'];
          
        if($count == 1){  
            //using session to track user id
            $_SESSION['user_id'] = $id;
            header("Location:student_view.php");
            //include 'student_view.php';  
        }  
        else{  
            //put alert msg to re login
            include 'index.html';
              
        }     
?>  