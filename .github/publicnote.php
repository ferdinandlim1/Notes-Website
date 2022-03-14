<?php
    include 'config.php';

    $token = $_GET['token'];

    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
    }

    $user = $_SESSION['username'];

    $sql = "SELECT * FROM account WHERE email='$user'OR username='$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $userid = $row['id'];


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
    <a class="navbar-brand" href="#">IF330 Notes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="<?php echo $direktoritoken;?>/welcome.php/?page=1">My Notes</a>
        <a class="nav-link " aria-current="page" href="<?php echo $direktoritoken;?>/shared.php/?page=1">Shared With Me</a>
        <a class="nav-link " aria-current="page" href="<?php echo $direktoritoken;?>/myaccount.php/?id=<?php echo $userid;?>">My account</a>
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
              <a type="button" class="btn btn-dark col-1" style="float:right;" role="button" href="<?php echo $direktoritoken;?>/newnote.php">New Note</a>
              <h2 class="col" style="font-size: 2rem; font-weight: 800;">Public Notes</h2>
              <br>
              <?php
              $indexacc = 1;
              $sql = "SELECT * FROM notes
                      WHERE token = '$token';";
              $result = mysqli_query($conn, $sql);
              if($result->num_rows >0){
                while($rownotes = mysqli_fetch_assoc($result)){
                  if($rownotes['user_id']!= NULL){
                    $isirow = $rownotes['isi'];
                    $judulrow = $rownotes['judul'];
                    $sql = "SELECT * FROM notes note, account acc WHERE note.user_id = acc.id AND token = '$token';";
                    $resultnote = mysqli_query($conn, $sql);
                    $rownote = mysqli_fetch_assoc($resultnote);
                    ?>
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="<?php echo "panelsStayOpen-headingOne".$indexacc?>">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#panelsStayOpen-collapse".$indexacc?>" aria-expanded="true" aria-controls="<?php echo "panelsStayOpen-collapse".$indexacc?>">
                            <h4><?php echo $rownote['jenis_note'];?></h4>
                          </button>
                        </h2>
                        <div id="<?php echo "panelsStayOpen-collapse".$indexacc?>" class="accordion-collapse collapse" aria-labelledby="<?php echo "panelsStayOpen-headingOne".$indexacc?>">
                          <div class="accordion-body">
                            <h5>Title: <?php echo $rownote['judul'];?></h5>
                            <p>Date created : <?php echo $rownote['date']; ?></p>
                            <p>Last Updated : <?php
                            if($rownote['last_update']==NULL){
                              echo "-";
                            }
                            else{
                              echo $rownote['last_update'];
                            }
                            ?></p>
                            <p>
                              Shared by
                              <a href="<?php echo $direktoritoken;?>/myaccount.php/?id=<?php echo $rownote['id'];?>"><?php echo $rownote['username']; ?></a>
                            </p>
                            <p><?= $rownote['isi']?></p>
                            <?php
                            $atchmnt = explode(", ", $rownote['attachment']);
                            $countfile = count($atchmnt);
                            if($atchmnt[0]!=""){
                            ?>
                            <h6>Attachment: <?php
                            $indexfile=1;
                            for($i=0;$i<$countfile;$i++){
                              echo "<a href='$direktoritoken/upload/{$atchmnt[$i]}'>File$indexfile     </a>";
                              $indexfile++;
                            }
                            ?></h6>
                            <?php
                            }
                            else{
                            ?>
                            <h6>No Attachment</h6>
                            <?php
                            }
                            if($rownote['jenis_note'] == "Public Note"){
                            ?>
                            <h6>Token: <?php echo $rownote['token'];?></h6>
                            <?php }?>

                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                      $indexacc++;
                          }
                          else{
                            $isirow = $rownotes['isi'];
                            $judulrow = $rownotes['judul'];
                            $sql = "SELECT * FROM notes WHERE token = '$token';";
                            $resultnote = mysqli_query($conn, $sql);
                            $rownote = mysqli_fetch_assoc($resultnote);
                            ?>
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="<?php echo "panelsStayOpen-headingOne".$indexacc?>">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#panelsStayOpen-collapse".$indexacc?>" aria-expanded="true" aria-controls="<?php echo "panelsStayOpen-collapse".$indexacc?>">
                            <h4><?php echo $rownote['jenis_note'];?></h4>
                          </button>
                        </h2>
                        <div id="<?php echo "panelsStayOpen-collapse".$indexacc?>" class="accordion-collapse collapse" aria-labelledby="<?php echo "panelsStayOpen-headingOne".$indexacc?>">
                          <div class="accordion-body">
                            <h5>Title: <?php echo $rownote['judul'];?></h5>
                            <p>Date created : <?php echo $rownote['date']; ?></p>
                            <p>Last Updated : <?php
                            if($rownote['last_update']==NULL){
                              echo "-";
                            }
                            else{
                              echo $rownote['last_update'];
                            }
                            ?></p>
                            <p>
                              Shared by Anonymous
                            </p>
                            <p><?= $rownote['isi']?></p>
                            <?php
                            $atchmnt = explode(", ", $rownote['attachment']);
                            $countfile = count($atchmnt);
                            if($atchmnt[0]!=""){
                            ?>
                            <h6>Attachment: <?php
                            $indexfile=1;
                            for($i=0;$i<$countfile;$i++){
                              echo "<a href='$direktoritoken/upload/{$atchmnt[$i]}'>File$indexfile     </a>";
                              $indexfile++;
                            }
                            ?></h6>
                            <?php
                            }
                            else{
                            ?>
                            <h6>No Attachment</h6>
                            <?php
                            }
                            if($rownote['jenis_note'] == "Public Note"){
                            ?>
                            <h6>Token: <?php echo $rownote['token'];?></h6>
                            <?php }?>

                          </div>
                        </div>
                      </div>
                    </div>
                            <?php
                          }
                      }
                      ?>
    
              <?php
              }
              else{
                  echo "<h5 class='col' style='font-size: 1.3rem; font-weight: 800;'>There isn't any note with that token!</h5>";
              }
              ?>

            </div>
         
          
        </div>
    </div>
</body>
</html>