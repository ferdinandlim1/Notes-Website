<?php

include 'config.php';

error_reporting(0);

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

$profileID = $_GET['id'];

$user = $_SESSION['username'];

$sql = "SELECT * FROM account WHERE email='$user'OR username='$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$userid = $row['id'];

?>




<!DOCTYPE html>
<html lang="en">
<style>
    .row {
        padding: 10px;
        padding-top: 7px;
        padding-bottom: 7px;
    }
</style>

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
                    <a class="nav-link" aria-current="page" href="<?php echo $direktoritoken;?>/welcome.php/?page=1">My Notes</a>
                    <a class="nav-link" aria-current="page" href="<?php echo $direktoritoken;?>/shared.php/?page=1">Shared With Me</a>
                    <a class="nav-link active" aria-current="page" href="<?php echo $direktoritoken;?>/myaccount.php/?id=<?php echo $userid;?>">My account</a>
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
                <h2 class="col" style="font-size: 2rem; font-weight: 800;">My Account</h2>
                <br>
                <?php
                $sql = "SELECT * FROM account WHERE id = '$profileID';";
                $result = mysqli_query($conn, $sql);

                if ($result->num_rows > 0) {
                    while ($account = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="mx-auto" style="width: 800px;">
                            <div class="card" >
                                <div class="card-header">
                                    Account Details
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <h5>
                                            Name : <?= $account['nama']; ?>
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <h5>
                                            Username : <?= $account['username']; ?>
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <h5>
                                            Email : <?= $account['email']; ?>
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <h6>
                                            Chance to edit : <?= $account['coba_edit']; ?>
                                        </h6>
                                    </div>
                                    <?php
                                    if($userid == $profileID){
                                    ?>
                                    <a href="<?php echo $direktoritoken;?>/editaccount.php/?id=<?=$account['id']?>" class="btn btn-primary" style="margin-top :10px">Edit account</a>
                                    <a href="<?php echo $direktoritoken;?>/deleteaccount.php/?id=<?=$account['id']?>" class="btn btn-primary" style="margin-top :10px">Delete account</a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                <?php
                    }
                }
                ?>
            </div>


        </div>
    </div>
    </div>
</body>

</html>