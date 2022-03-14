<?php

include 'config.php';

$id = $_GET['id'];

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

$dltnote = "";


if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $dltnote = isset($_POST['delete']) ? $_POST['delete'] : "";
    $sql = "SELECT * FROM account WHERE id = '$id';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $usernamedb = $row['username'];

    if($username==$usernamedb){

        if($dltnote != ""){
            $sqlnote = "DELETE FROM notes WHERE user_id = '$id';";
            $result2 = mysqli_query($conn, $sqlnote);

            if($result2){
                echo "<script>alert('Delete Account and Notes Success!')</script>";
                header("Location: $direktoritoken/logout.php");
            }
            else{
                echo "<script>alert('Something Wrong!')</script>";
            }
        }

        $sql = "DELETE FROM notes WHERE user_id = '$id' AND jenis_note = 'Private Note';";
        $result3 = mysqli_query($conn, $sql);

        $sql = "DELETE FROM account WHERE id = '$id';";
        $result = mysqli_query($conn, $sql);
        
        if($result && $result3){
            echo "<script>alert('Delete Success!')</script>";
            header("Location: $direktoritoken/logout.php");
        }
        else{
            echo "<script>alert('Something Wrong!')</script>";
        }
    }
    else{
        echo "<script>alert('Wrong Username!')</script>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Edit Account</title>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $direktoritoken;?>/welcome.php/?page=1">IF330 Notes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" aria-current="page" href="<?php echo $direktoritoken;?>/welcome.php/?page=1">My Notes</a>
                    <a class="nav-link" aria-current="page" href="<?php echo $direktoritoken;?>/shared.php/?page=1">Shared With Me</a>
                    <a class="nav-link active" aria-current="page" href="<?php echo $direktori;?>/myaccount.php/?id=<?php echo $id;?>">My account</a>
                </div>
            </div>
            <a type="button" class="btn btn-light navright" role="button" href="<?php echo $direktoritoken;?>/logout.php">Log Out</a>
        </div>
    </nav>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
    </svg>
</head>

<body>
    <br>
    <form action="" method="POST" class="mx-auto" enctype="multipart/form-data" style="width: 800px; margin-top :10px">
        
        <div class="mb-3">
            <label for="username" class="form-label">Confirmation</label>
            <input type="text" class="form-control" id="username" placeholder="Enter your Username" name="username" value="" required>
        </div>
        
        <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="delete" name="delete" value="delete">
                <label class="form-check-label" for="public">Delete Shared and Public Notes</label>
        </div>
        <div class="mb-3">
            <button name="submit" class="btn btn-primary">Delete Account</button>
        </div>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#info-fill" />
            </svg>
            <div>
                Deleting Account will remove your private notes permanently!
            </div>
        </div>
    </form>
</body>

</html>