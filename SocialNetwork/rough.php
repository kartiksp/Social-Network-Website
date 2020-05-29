  <?php
  session_start();
    $message = "";  
	 
    // PDO connection_________________________________________________________________________________________________________________________________
	
      $connect = new PDO('mysql:host=127.0.0.1; dbname=socialnetwork;','root','kartik');
	  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
	$query = "SELECT * FROM users WHERE username = :user_name";  
                $z = $connect->prepare($query);
                $z->execute( array('user_name' => $_SESSION["username"]));  
	
	$z->setFetchMode(PDO::FETCH_ASSOC);
	
	
	$query = "SELECT * FROM posts WHERE user_name = :user_name";  
                $x = $connect->prepare($query);
                $x->execute( array('user_name' => $_SESSION["username"]));  
	
	$x->setFetchMode(PDO::FETCH_ASSOC);
	
	
	
?>
<head><?php echo $_SESSION["username"]  ?></head>
<h1>My Posts</h1>
<?php while ($row = $x->fetch()): ?>
 <?php echo htmlspecialchars($row['user_name']); ?></br>
	 <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="90px" height="90px">
    <figcaption> <?php echo htmlspecialchars($row['body']); ?></figcaption>
	<form action="rough.php" method="post">
	<?php
	$us=$_SESSION["username"];
	$im=$row['image'];
		
	$sql=$connect->prepare("SELECT * FROM likes WHERE user_name=? and image=?");
	$sql->execute(array($us, $im));
	
	
	if($sql->rowCount()==1){
		
	?>
<button type="submit" value="<?php $im ?>">unlike<button/>

<?php  
	}
else{
?>
<input type="submit"  value="<?php $im ?>" name="like">  
<?php  
}

if (isset($_POST['like'])) { 
 $query = "INSERT INTO likes (user_name, image) VALUES ('$us','$im')";
$connect->exec($query);
  }
?>
	</figure>	
	</br>
	</br>
	</br>
	</br>
<?php endwhile; ?>
</form>













 
