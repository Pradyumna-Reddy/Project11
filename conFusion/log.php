
<?php
$servername = "localhost";
$database = "file_management";
$username = "root";
$password = "";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}
$email = $_POST['email'];
$pass = $_POST['pass'];
$id = $_POST['id'];
$name = $_POST['name'];
$phno = $_POST['phno'];
 
echo "Connected successfully";
 
$sql = "INSERT INTO login VALUES ('".$email."','".$pass."','".$id."','".$name."','".$phno."')";
if (mysqli_query($conn, $sql)) {
      echo "New record created successfully";
} else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
?>