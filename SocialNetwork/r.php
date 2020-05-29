 <?php
  session_start();
    $message = "";  
	 
    // PDO connection_________________________________________________________________________________________________________________________________
	
      $connect = new PDO('mysql:host=127.0.0.1; dbname=socialnetwork;','root','kartik');
	  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	  $usn=$_SESSION["username"];
	  
if (isset($_POST['liked'])) {
  $postid = $_POST['image'];
  $result = $connect->query("SELECT * FROM posts WHERE image=$image");
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $n = $row['likes'];
  $connect->query("UPDATE posts SET likes=$n+1 WHERE image=$image");
  $connect->query("INSERT INTO likes(user_name, image) VALUE( $usn, $image)");
  exit();
 }
 if (isset($_POST['unliked'])) {
  $postid = $_POST['image'];
  $result = $connect->query("SELECT * FROM posts WHERE image=$image");
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $n = $row['likes'];
  //delete from the likes before updation posts
  $connect->query("DELETE FROM likes WHERE image=$image AND user_name= $usn");
  $connect->query("UPDATE articles SET likes=$n-1 WHERE image=$image");
  exit();
 }
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Document</title>
 <style type="text/css">
  .content {
   width: 50%;
   margin: 100px auto;
   border: 1px solid #cbcbcb;
  }
  .post {
   width: 80%;
   margin: 10px auto;
   border: 1px solid #cbcbcb;
   padding: 10px;
  }
 </style>
</head>
<body>

<div class="content">
 <!-- Get data from the DB and display on the page -->
<?php 
 // $query= mysql_query("SELECT * FROM articles");
 $query= $connect->query("SELECT * FROM posts");
 //while ($row = mysql_fetch_array($query)) { ?/>
 while ($row = $query->fetch(PDO::FETCH_ASSOC)) { ?>
  <div class="post">
   <?php echo $row['image']; ?><br>
   <?php 
    // determine if user has already like this post
	
     $query = "SELECT * FROM likes WHERE user_name = :username AND image=:image " ; 
			  
			  $statement = $connect->prepare($query);
			   $pdoExec = $statement->execute(array(":username"=>$usn,":image"=>$row['image']));
   

    if ($pdoExec->rowCount() == 1) { ?>
     <!-- user already likes post -->
     <span><a href="" class="unlike" id="<?php echo $row['id']; ?>">unlike</a></span>
    <?php } else { ?>
     <!-- user has not yet liked post -->
     <span><a href="" class="like" id="<?php echo $row['id']; ?>">like</a></span>
   <?php } ?>
  </div>
 <?php } ?>
</div>

<!-- Add JQuery -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
  // when the user clicks on like
  $('.like').click(function() {
   var image = $(this).attr('id');
   // alert('You clicked on ' + image);
   $.ajax({
    url: 'index.php',
    type: 'post',
    async: false,
    data: {
     'liked': 1,
     'image': image
    },
    success: function() {
    }
   });
  });
  // when the user clicks on unlike
  $('.unlike').click(function() {
   var image = $(this).attr('id');
   // alert('You clicked on ' + image);
   $.ajax({
    url: 'r.php',
    type: 'post',
    async: false,
    data: {
     'unliked': 1,
     'image': image
    },
    success: function() {
    }
   });
  });
 });
</script>
</body>
</html>﻿