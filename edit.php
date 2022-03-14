<?php

include 'config.php';
$id = $_GET['id'];

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

$user = $_SESSION['username'];

$sql = "SELECT * FROM account WHERE email='$user'OR username='$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$userid = $row['id'];

$sql = "SELECT * FROM notes WHERE note_id ='$id';";
$result = mysqli_query($conn, $sql);
$rownote = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $public="";
    $shared="";
    $judul = $_POST['title'];
    $isi = $_POST['isi'];
    $today = date("Y/m/d");
    $attachment = $_FILES[''];

      
      $sql = "UPDATE notes SET judul='$judul', isi='$isi', last_update = '$today'
                WHERE note_id='$id';";
      $result = mysqli_query($conn, $sql);
      
      if($result){
        echo "<script>alert('Note Saved!')</script>";
        $judul = "";
        $isi = "";
        $shared = "";
        $public = "";
        $jenis = "";
        header("Location: $direktoritoken/welcome.php/?page=1");

      }
      else{
        $sql = "DELETE FROM notes WHERE note_id = '$note_id'";
        $result = mysqli_query($conn, $sql);
        echo "<script>alert('Something went wrong! ')</script>";
      }
    

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Notes IF330</title>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $direktoritoken;?>/welcome.php/?page=1">IF330 Notes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="<?php echo $direktoritoken;?>/welcome.php/?page=1">My Notes</a>
        <a class="nav-link" href="<?php echo $direktoritoken;?>/shared.php/?page=1">Shared With Me</a>
        <a class="nav-link" href="<?php echo $direktoritoken;?>/myaccount.php/?id=<?php echo $userid;?>">My account</a>
      </div>
    </div>
    <a type="button" class="btn btn-light navright" role="button" href="<?php echo $direktoritoken;?>/logout.php">Log Out</a>
  </div>
</nav>
</head>
<body>
    <br>
    <div class="col-12">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <form action="" method="POST" enctype="multipart/form-data">
            <h2 class="col" style="font-size: 2rem; font-weight: 800;">Edit Notes</h2>
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Title" name="title" value="<?php echo $rownote['judul']; ?>" required>
              </div>
              <div class="mb-3">
                <label for="isi" class="form-label">Content</label>
                <textarea class="form-control" placeholder="Content" id="isi" name="isi" value="<?php echo $rownote['isi']; ?>" cols="30" rows="10" required></textarea>
              </div>
    
              <button type="submit" name="submit" class="btn btn-dark">Save Note</button>
              
            </form>
        </div>
      </div>
    </div>
</body>
</html>