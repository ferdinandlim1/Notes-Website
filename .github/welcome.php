<?php 

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

$user = $_SESSION['username'];

$sql = "SELECT * FROM account WHERE email='$user'OR username='$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$userid = $row['id'];


$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page-1) * $limit;
$sqlPage = "SELECT count(note_id) AS id FROM notes WHERE user_id = '$userid';";
$resultPage = mysqli_query($conn, $sqlPage);
$rowPage = mysqli_fetch_assoc($resultPage);
$totalPage = $rowPage['id'];
$pages = ceil($totalPage/$limit);

$previous = $page - 1;
$next = $page + 1;


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
        <a class="nav-link active" aria-current="page" href="#">My Notes</a>
        <a class="nav-link " aria-current="page" href="<?php echo $direktoritoken;?>/shared.php/?page=1">Shared With Me</a>
        <a class="nav-link " aria-current="page" href="<?php echo $direktoritoken;?>/myaccount.php/?id=<?php echo $userid;?>">My account</a>
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
              <a type="button" class="btn btn-dark col-1" style="float:right;" role="button" href="<?php echo $direktoritoken;?>/newnote.php">New Note</a>
              <h2 class="col" style="font-size: 2rem; font-weight: 800;">My Notes</h2>
              <br>
              <?php
              $indexacc = 1;
              $sql = "SELECT * FROM notes WHERE user_id = '$userid' LIMIT $start, $limit;";
              $result = mysqli_query($conn, $sql);
              if($result->num_rows >0){
                while($rownotes = mysqli_fetch_assoc($result)){
              ?>
              <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="<?php echo "panelsStayOpen-headingOne".$indexacc?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#panelsStayOpen-collapse".$indexacc?>" aria-expanded="true" aria-controls="<?php echo "panelsStayOpen-collapse".$indexacc?>">
                      <h4><?=$rownotes['jenis_note']?></h4>
                    </button>
                  </h2>
                  <div id="<?php echo "panelsStayOpen-collapse".$indexacc?>" class="accordion-collapse collapse" aria-labelledby="<?php echo "panelsStayOpen-headingOne".$indexacc?>">
                    <div class="accordion-body">
                      <h5>Title: <?php echo $rownotes['judul'];?></h5>
                      <p>Date created : <?php echo $rownotes['date']; ?></p>
                      <p>Last Updated : <?php
                      if($rownotes['last_update']==NULL){
                        echo "-";
                      }
                      else{
                        echo $rownotes['last_update'];
                      }
                      ?></p>
                      <p><?= $rownotes['isi']?></p>
                      <?php
                      $atchmnt = explode(", ", $rownotes['attachment']);
                      $countfile = count($atchmnt);
                      if($atchmnt[0]!=""){
                      ?>
                      <h6>Attachment: <?php
                      $indexfile=1;
                      for($i=0;$i<$countfile;$i++){
                        echo "<a href='upload/{$atchmnt[$i]}'>File$indexfile     </a>";
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
                      if($rownotes['jenis_note'] == "Public Note"){
                      ?>
                      <h6>Token: <?php echo $rownotes['token'];?></h6>
                      <?php }?>
                      <a type="button" class="btn btn-dark col-1" role="button" href="<?php echo $direktoritoken;?>/edit.php/?id=<?=$rownotes['note_id']?>">Edit</a>
                      <a type="button" class="btn btn-dark col-1" role="button" href="<?php echo $direktoritoken;?>/delete.php/?id=<?=$rownotes['note_id']?>">Delete</a>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                $indexacc++;
                }
              ?>
              <br>
              <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                  <?php
                  if($page == 1){
                  ?>
                  <li class="page-item disabled">
                    <a class="page-link" href="<?php echo $direktoritoken;?>/welcome.php/?page=<?php echo $previous;?>">Previous</a>
                  </li>
                  <?php
                  }
                  else{
                  ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo $direktoritoken;?>/welcome.php/?page=<?php echo $previous;?>">Previous</a>
                  </li>
                  <?php
                  }
                  ?>
                  <?php
                  for($i=1; $i<=$pages; $i++){
                  ?>
                  <li class="page-item"><a class="page-link" href="<?php echo $direktoritoken;?>/welcome.php/?page=<?php echo $i;?>"><?= $i; ?></a></li>
                  <?php
                  }
                  if($page == $pages){
                  ?>
                  <li class="page-item disabled">
                    <a class="page-link" href="<?php echo $direktoritoken;?>/welcome.php/?page=<?php echo $next;?>">Next</a>
                  </li>
                  <?php
                  }
                  else{
                  ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo $direktoritoken;?>/welcome.php/?page=<?php echo $next;?>">Next</a>
                  </li>
                  <?php
                  }
                  ?>
                </ul>
              </nav>
              <?php
              }
              else{
                  echo "<h5 class='col' style='font-size: 1.3rem; font-weight: 800;'>There isn't any note, please make some</h5>";
              }
              ?>

            </div>
         
          
        </div>
    </div>
</body>
</html>