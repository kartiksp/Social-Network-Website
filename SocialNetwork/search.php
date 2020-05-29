<?php
session_start();
$var1 = $_SESSION['var1'];


//PDO connection_________________________________________________________________________________________________________________________
try{
              $connect = new PDO('mysql:host=127.0.0.1; dbname=socialnetwork;','root','kartik');
		      $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
			   $username=$_SESSION["username"];
}	   
	 catch (PDOException $e) {
echo 'Connection failed: ' . $e->getMessage();
}


$sql = 'SELECT * FROM users WHERE username LIKE :keyword ORDER BY id DESC ';
$pdo_statement = $connect->prepare($sql);
$pdo_statement->bindValue(':keyword', '%' . $var1 . '%', PDO::PARAM_STR);
$pdo_statement->execute();
if(!$pdo_statement->rowCount()){
	$message = '<label>No result found</label>';
}
else{

$result = $pdo_statement->fetchAll();

}

if(isset($_POST["search"])) 
        {
			$_SESSION['var1']= $_POST["var1"];
			header('Location: search.php');

}
		

?>
 <html>
     <head>
	<style>
<?php include 'log.css'; ?>
</style>
        <title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
		<div class="header">
		
		<form name="frmSearch" method="post" action="search.php">
   
      <input name="var1" type="text" id="var1" value="<?php  echo $var1 ?>">
      <input type="submit" value="Search" name="search">
     

</form>
</div>
    </head>
    <body>
	
        <div id="container">
            <h1>Your search results</h1>
            <table class="table table-bordered table-condensed">
                <thead>
<hr style="border:solid 1px">

   <?php
    if(empty($result))
        {
       	echo"<label>No result found</label>";
	}
        
    		
else{
	foreach( $result as $row ) {
   	?><a href="fprofile.php" style="text-decoration:none; color:black;"><h3>
   <?php echo $row["username"]; 
    $_SESSION['fa']= $row["username"]; echo "</br>";
    echo $row["email"]; 
	echo '<hr>';
}
}
   
	?>
</h3>
		</a>

                </tbody>
            </table>
    </body>