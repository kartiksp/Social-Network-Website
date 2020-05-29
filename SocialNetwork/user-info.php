<?php
	
	session_start();
    $message = "";  
	 
    // PDO connection_________________________________________________________________________________________________________________________________
	
      $connect = new PDO('mysql:host=127.0.0.1; dbname=socialnetwork;','root','kartik');
	  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     $username=$_SESSION["username"];
	 
	 // Button click Post code_____________________________________________________________________________________________________________________________
	 
	 if(isset($_POST['ok'])){
		 
		 //image code_______________
		 
		$folder ="uploads/"; 
         $image = $_FILES['image']['name']; 
         $path = $folder . $image ; 
         $target_file=$folder.basename($_FILES["image"]["name"]);
         $imageFileType=pathinfo($target_file,PATHINFO_EXTENSION);
         $allowed=array('jpeg','png' ,'jpg'); $filename=$_FILES['image']['name']; 
         $ext=pathinfo($filename, PATHINFO_EXTENSION); if(!in_array($ext,$allowed) ) 
	               { 
                       echo "Sorry, only JPG, JPEG, PNG & GIF  files are allowed.";
                   }
                else
				{ 
			
				//caption code_______________
		 $postbody=$_POST['postbody'];
		 move_uploaded_file( $_FILES['image'] ['tmp_name'], $path); 
		 $query = "INSERT INTO posts (body,posted_at,user_name,image) VALUES ('$postbody',NOW(),'$username','$image')";
		 $connect->exec($query);
				}

} 
	 //Profile card code________________________________________________________________________________________________________________________________________
	 
	 $query = "SELECT * FROM users WHERE username = :user_name";  
                $z = $connect->prepare($query);
                $z->execute( array('user_name' => $_SESSION["username"]));  
	
	$z->setFetchMode(PDO::FETCH_ASSOC);
	
	
	 //Post retrieval code________________________________________________________________________________________________________________________________________
	 
	 $query = "SELECT * FROM posts WHERE user_name = :user_name ORDER BY id DESC";  
                $q = $connect->prepare($query);
                $q->execute( array('user_name' => $_SESSION["username"]));  
	
	$q->setFetchMode(PDO::FETCH_ASSOC);
	   
	    //user info update and retrieval code________________________________________________________________________________________________________________________________________
if (isset($_POST['update'])) 
{
        $username = $_SESSION["username"];
        $email = $_POST['email'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $dob = $_POST['dob'];
        $phone = $_POST['phone'];
        $bio = $_POST['bio'];
		
		
			 $query = "SELECT * FROM users_info WHERE username = :username";
  $statement = $connect->prepare($query);
			   $pdoExec = $statement->execute(array(":username"=>$_SESSION["username"]));
			  
    if($pdoExec)
    {   
        if($statement->rowCount()>0)
        {
         $sql = "UPDATE users_info SET email =?, country =?, city =?, dob =?, phone=?, bio=? WHERE username=?";
          $stmt= $connect->prepare($sql);
          $stmt->execute([$email,$country,$city,$dob,$phone,$bio,$username]);
		  	header('Location: pf.php');
        }
		else	
        {	
	   $sql = "INSERT INTO users_info (username,email,country,city,dob,phone,bio) VALUES ('$username','$email','$country','$city','dob','phone','bio') ";
        $connect->exec($sql);
		header('Location: pf.php');
		}
      }        
	
	
		
}		
$e='';
	 try{
	 $query = "SELECT * FROM users_info WHERE username = :user_name ORDER BY id DESC";  
                $x = $connect->prepare($query);
                $x->execute( array('user_name' => $_SESSION["username"]));  
	
	$x->setFetchMode(PDO::FETCH_ASSOC);
	 }
	 catch (Exception $e) {
    // Nothing, this is normal
}

	   
?>			 
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
<?php include 'profilecard.css'; ?>
</style>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
</head>
<script>
$(document).ready(function() {
    var $btnSets = $('#responsive'),
    $btnLinks = $btnSets.find('a');
 
    $btnLinks.click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.user-menu>div.user-menu-content").removeClass("active");
        $("div.user-menu>div.user-menu-content").eq(index).addClass("active");
    });
});

$( document ).ready(function() {
    $("[rel='tooltip']").tooltip();    
 
    $('.view').hover(
        function(){
            $(this).find('.caption').slideDown(250); //.fadeIn(250)
        },
        function(){
            $(this).find('.caption').slideUp(250); //.fadeOut(205)
        }
    ); 
});
</script>
<!-- Header code -->
<div class="header">
     <h1><a href="pf.php" class="logo"><b><?php echo $_SESSION["username"];?>'s Profile</b></a></h1>        
</div>
<!-- Profile card -------------------------------------------------------------------------------------------------------------->
<div class="container"style="padding:20px">
    <div class="row user-menu-container square">
        <div class="col-md-7 user-details">
            <div class="row coralbg white">
                <div class="col-md-6 no-pad">
                    <div class="user-pad">
					<form action="user-info.php" method="post">
					<?php while ($row = $x->fetch()): ?>
                        <h3>Welcome back,<?php echo $_SESSION["username"];?></h3>
						 <input type=email value="<?php echo htmlspecialchars($row['email']); ?>" name=email style="color:black"></br>
                        <input type=text value="<?php echo htmlspecialchars($row['country']); ?>" name=country style="color:black"></br>
                       <input type=text value="<?php echo htmlspecialchars($row['city']); ?>" name=city style="color:black"></br>
					     <input type=date value="<?php echo htmlspecialchars($row['dob']); ?>" name=dob style="color:black"></br>
					   <input type=text value="<?php echo htmlspecialchars($row['phone']); ?>" name=phone style="color:black"></br>
					   <input type=text value="<?php echo htmlspecialchars($row['bio']); ?>" name=bio style="color:black"> 
	                   <?php endwhile; ?>
                        <input type="submit" value="update" name="update" class="btn btn-labeled btn-info">
						</form>
                    </div>
                </div>
                <div class="col-md-6 no-pad">
                    <div class="user-image">
					<?php while ($row = $z->fetch()): ?>
                        <a href='profile.php'><img  width="auto" height="inherit" style=" width: 100%; max-width: 370px "src="Pictures/<?php echo htmlspecialchars($row['profilepic']); ?>" ></a>
						 <?php endwhile; ?>
        
                    </div>
                </div>
            </div>
        </div>
        
     
    </div>
</div>
 