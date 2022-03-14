<?php

include 'config.php';

error_reporting(0);

session_start();


$user = $_SESSION['username'];

$sql = "SELECT * FROM account WHERE email='$user'OR username='$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$userid = $row['id'];

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $public="";
    $shared="";
    $judul = $_POST['title'];
    $isi = $_POST['isi'];
    $today = date("Y/m/d");
    $countfiles = count($_FILES['file']['name']);
    $filenamedb = implode(", ", $_FILES['file']['name']);
    
    for($i=0;$i<$countfiles;$i++){
      $filename = $_FILES['file']['name'][$i];
      
      move_uploaded_file($_FILES['file']['tmp_name'][$i],'upload/'.$filename);
    
    }
    $shared = $_POST['shared'];
    $public = $_POST['public'];
    switch($_POST['access']){
      case "0": $access = 0; break;
      case "1": $access = 1; break;
      case "2": $access = 2; break;
      case "3": $access = 3; break;
    }

    $userShared = explode(", ", $shared);
    $jmlUser = count($userShared);

    if($shared=="" && $public == ""){
      $jenis = "Private Note";
    }
    elseif($shared!="" && $public == ""){
      $jenis = "Shared Note";
    }
    else{
      $jenis = "Public Note";
    }

    if($jenis == "Public Note"){
      $result3 = 1;
      $token = bin2hex(random_bytes(5));
      $sql = "INSERT INTO notes (user_id, judul, isi, sharedwith, jenis_note, token, date, attachment)
            VALUES ('$userid', '$judul', '$isi', '$shared', '$jenis', '$token', '$today', '$filenamedb')";
      $result = mysqli_query($conn, $sql);
      if($shared!=NULL){
            for($i=0; $i<$jmlUser; $i++){
              $sql = "SELECT * FROM account WHERE username='$userShared[$i]'";
              $result = mysqli_query($conn, $sql);
              if($result->num_rows > 0){
                $row = mysqli_fetch_assoc($result);
                $userid_penerima = $row['id'];

                $sql = "SELECT * FROM notes WHERE user_id='$userid' AND judul='$judul' AND isi ='$isi'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $note_id = $row['note_id'];

                $sql = "INSERT INTO shared (user_id_pemilik, note_id, user_id_penerima, access)
                        VALUES ('$userid', '$note_id', '$userid_penerima', '$access')";
                $result2 = mysqli_query($conn, $sql);
              }
              else{
                $result3 = 0;
              }
            }
            if($result && $result2 && $result3 == 1){
              echo "<script>alert('Note Saved!')</script>";
              $judul = "";
              $isi = "";
              $shared = "";
              $public = "";
              $jenis = "";
              $token = "";

            }
            else{
              $sql = "DELETE FROM notes WHERE note_id = '$note_id'";
              $result = mysqli_query($conn, $sql);
              echo "<script>alert('No one with that username! ')</script>";
            }
          }
          else{
            if($result && $result3 == 1){
              echo "<script>alert('Note Saved!')</script>";
              $judul = "";
              $isi = "";
              $shared = "";
              $public = "";
              $jenis = "";
              $token = "";

            }
            else{
              $sql = "DELETE FROM notes WHERE note_id = '$note_id'";
              $result = mysqli_query($conn, $sql);
              echo "<script>alert('Something went wrong! ')</script>";
            }
      }
    }
    else{
      $result3 = 1;
      $sql = "INSERT INTO notes (user_id, judul, isi, sharedwith, jenis_note, attachment, date)
            VALUES ('$userid', '$judul', '$isi', '$shared', '$jenis', '$filenamedb', '$today')";
      $result = mysqli_query($conn, $sql);
      if($shared != NULL){
        for($i=0; $i<$jmlUser; $i++){
          $sql = "SELECT * FROM account WHERE username='$userShared[$i]'";
          $result = mysqli_query($conn, $sql);
          if($result->num_rows > 0){
            $row = mysqli_fetch_assoc($result);
            $userid_penerima = $row['id'];
  
            $sql = "SELECT * FROM notes WHERE user_id='$userid' AND judul='$judul' AND isi ='$isi'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $note_id = $row['note_id'];
  
            $sql = "INSERT INTO shared (user_id_pemilik, note_id, user_id_penerima, access)
                    VALUES ('$userid', '$note_id', '$userid_penerima', '$access')";
            $result2 = mysqli_query($conn, $sql);
          }
          else{
            $result3 = 0;
          }
        }
      if($result && $result2 && $result3 == 1){
        echo "<script>alert('Note Saved!')</script>";
        $judul = "";
        $isi = "";
        $shared = "";
        $public = "";
        $jenis = "";

      }
      else{
        $sql = "DELETE FROM notes WHERE note_id = '$note_id'";
        $result = mysqli_query($conn, $sql);
        echo "<script>alert('No one with that username! ')</script>";
      }
    }
    else{
      if($result && $result3 == 1){
        echo "<script>alert('Note Saved!')</script>";
        $judul = "";
        $isi = "";
        $shared = "";
        $public = "";
        $jenis = "";

      }
      else{
        $sql = "DELETE FROM notes WHERE note_id = '$note_id'";
        $result = mysqli_query($conn, $sql);
        echo "<script>alert('Something went wrong! ')</script>";
      }
    }
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
    <a class="navbar-brand" href="<?php echo $direktori;?>/welcome.php/?page=1">IF330 Notes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="<?php echo $direktori;?>/welcome.php/?page=1">My Notes</a>
        <a class="nav-link" href="<?php echo $direktori;?>/shared.php/?page=1">Shared With Me</a>
        <a class="nav-link" href="<?php echo $direktori;?>/myaccount.php/?id=<?php echo $userid;?>">My account</a>
      </div>
    </div>
    <a type="button" class="btn btn-light navright" role="button" href="logout.php">Log Out</a>
  </div>
</nav>
</head>
<body>
    <br>
    <div class="col-12">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <form action="" method="POST" enctype='multipart/form-data'>
            <h2 class="col" style="font-size: 2rem; font-weight: 800;">New Notes</h2>
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Title" name="title" value="<?php echo $judul; ?>" required>
              </div>
              <div class="mb-3">
                <label for="isi" class="form-label">Content</label>
                <textarea class="form-control" placeholder="Content" id="isi" name="isi" value="<?php echo $isi; ?>" cols="30" rows="10" required></textarea>
              </div>
              <div class="mb-3">
                <label for="attachment" class="form-label">Attachment</label>
                <input class="form-control form-control-sm" type="file" id="attachment" name="file[]" multiple>
              </div>
              <div class="mb-3">
                <label for="shared" class="form-label">Shared this with: ex(user, admin, gulali)</label>
                <input type="text" class="form-control" placeholder="Shared This Note With" id="shared" name="shared" value="<?php echo $shared; ?>">
              </div>
              <div class="mb-3">
              <select class="form-select" name="access" aria-label="Default select example">
                <option selected>Choose shared user access</option>
                <option value="1">All shared user can edit and delete</option>
                <option value="2">All shared user can edit</option>
                <option value="3">All shared user can delete</option>
                <option value="0">All shared user can only read</option>
              </select>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="public" name="public" value="public">
                <label class="form-check-label" for="public">Set as public notes</label>
              </div>
    
              <button type="submit" name="submit" class="btn btn-dark">Save Note</button>
              
            </form>
        </div>
      </div>
    </div>
</body>
</html>