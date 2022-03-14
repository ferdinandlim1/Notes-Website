<?php

include 'config.php';
$id = $_GET['id'];

$sql = "DELETE FROM notes WHERE note_id ='$id';";
$result = mysqli_query($conn, $sql);
if($result){
    echo "<script>alert('Delete Berhasil!')</script>";
    header("Location: $direktoritoken/welcome.php/?page=1");
}
?>