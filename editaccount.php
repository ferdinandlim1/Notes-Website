<?php

include 'config.php';
$id = $_GET['id'];

$sql = "SELECT * FROM account WHERE id ='$id';";
$result = mysqli_query($conn, $sql);

if ($result !== false) {
    $account = mysqli_fetch_assoc($result);
}


if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $cobaedit = $account['coba_edit'] - 1;

    if ($password == $cpassword) {
        $sql = "SELECT * FROM account WHERE email='$email' OR username='$username'";
        $result = mysqli_query($conn, $sql);
        if (isset($_POST['submit'])) {

            $filename = $_FILES['profileImage']['name'];
            $tmp_file = $_FILES['profileImage']['tmp_name'];

            $file_ext = explode(".", $filename);
            $file_ext = end($file_ext);
            $file_ext = strtolower($file_ext);

            switch ($file_ext) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'svg':
                case 'webp':
                case 'bmp':
                case 'gif':
                    move_uploaded_file($_FILES['profileImage']['tmp_name'], 'upload/' . $filename);
                default:
            }
            if ($result->num_rows < 2) {
                $sql = "UPDATE account SET username = '$username', email ='$email', password ='$password', nama ='$name' ,profile_picture ='$filename', coba_edit = '$cobaedit' WHERE id ='$id' ;";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    echo "<script>alert('Wow! Account Update Completed.')</script>";
                    $username = "";
                    $email = "";
                    $name = "";
                    $_POST['password'] = "";
                    $_POST['cpassword'] = "";
                    header("Location: $direktoritoken/logout.php");
                } else {
                    echo "<script>alert('Woops! Something Went Wrong.')</script>";
                }
            } else {
                echo "<script>alert('Woops! Email or Username Already Exists.')</script>";
            }
        } else {
            echo "<script>alert('Password Not Matched.')</script>";
        }
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
            <a type="button" class="btn btn-light navright" role="button" href="logout.php">Log Out</a>
        </div>
    </nav>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
    </svg>
</head>

<body>
    <?php
    if($account['coba_edit']>0){
    ?>
    <br>
    <form action="" method="POST" class="mx-auto" enctype="multipart/form-data" style="width: 800px; margin-top :10px">
        <div class="mb-3">
            <label for="profileImage" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="profileImage" name="profileImage">
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Name</label>
            <input type="text" class="form-control" id="nama" placeholder="Enter New Name" name="nama" value="<?php echo $account['nama']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Enter New Username" name="username" value="<?php echo $account['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter New Email" name="email" value="<?php echo $account['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter New Password" name="password" value="<?php echo $account['password']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password" placeholder="Confirm New Password" name="cpassword" required>
        </div>
        <div class="mb-3">
            <button name="submit" class="btn btn-primary">Update Account</button>
        </div>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#info-fill" />
            </svg>
            <div>
                After account update, Please login again. 
                <?php
                if($account['coba_edit']==1){
                    echo "Also, This is your last chance to edit!";
                }
                ?>
            </div>
        </div>
    </form>
    <?php
    }
    else{
    ?>
        <br>
        <div class="col-12">
             <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <h5>Sorry, you don't have any chance to edit!</h5>
                    <a href="<?php echo $direktoritoken;?>/myaccount.php/?id=<?=$account['id']?>" class="btn btn-primary" style="margin-top :10px">Go Back!</a>
                </div>
             </div>
        </div>
    <?php
    }
    ?>
</body>

</html>