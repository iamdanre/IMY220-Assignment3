<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "root", "dbUser");

	$bemail = isset($_POST["loginName"]) ? $_POST["loginName"] : false;
	$bpass = isset($_POST["loginPassw"]) ? $_POST["loginPassw"] : false;

	$email=$_POST['loginName'];
	$passw=$_POST['loginPassw'];
	$id;

	$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$passw'";
	$res = $mysqli->query($query);
	if($row = mysqli_fetch_array($res)){
		$id=$row['user_id'];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Danré Retief">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($bemail && $bpass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$passw'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

					echo 	"<form action='' method='POST' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple='multiple' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input type='hidden' name='loginName' value='$email'/>
									<input type='hidden' name='loginPassw' value='$passw'/>
								</div>
						  	</form>";

								//Upload the files
								if(isset($_FILES['picToUpload'])){
								$picSum = count($_FILES['picToUpload']['name']);

								for ($i=0; $i < $picSum; $i++) {
									$fname = $_FILES['picToUpload']['name'][$i];
						      $fsize =$_FILES['picToUpload']['size'][$i];
						      $ftmp =$_FILES['picToUpload']['tmp_name'][$i];
						      $ftype=$_FILES['picToUpload']['type'][$i];
									$lol=explode('.',$_FILES['picToUpload']['name'][$i]);
						      $fext=end($lol);
						      if(!($fext=="jpeg" || $fext=="jpg")){
						         echo '<div class="alert alert-danger mt-3" role="alert">
					 	  							File format not supported, please choose .jpeg or .jpg files only!
					 	  						</div>';
						      }
						      else if($fsize > 1048576){
										 echo '<div class="alert alert-danger mt-3" role="alert">
					 	  							File too large, upload pictures < 1MB!
					 	  						</div>';
						      }
									else{
										move_uploaded_file($ftmp,"gallery/".$fname);
										$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$id', '$fname');";
										$mysqli->query($query);
									}
								}
								}
								//now display them
								$query = "SELECT * FROM tbgallery WHERE user_id = '$id'";
								$res = $mysqli->query($query);
								if (mysqli_num_rows($res)!=0){
									echo "<h1>Image Gallery</h1>";
									echo '<div class="row imageGallery">';
									while ($row = mysqli_fetch_assoc($res)){
									  echo "<div class='col-3' style='background-image: url(gallery/".$row['filename'].")'></div>";
									}
									echo '</div>';
								}
								else{}
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			}
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
			//last git test
		?>
	</div>
</body>
</html>
