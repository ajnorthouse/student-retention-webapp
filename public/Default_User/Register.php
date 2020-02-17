<?php
    session_destroy();
    require_once( dirname(__FILE__, 3) . "\logic\Default_Users\Login_Methods.php" );
?>



<html>
  <head>
	<title>SRS - Register User</title>
      <link rel="stylesheet" href="../StyleSheets/StyleSheet_User.css">
  </head>
  <body>
	<section class="login">
		<h1>Student Retention Service</h1>
		<h1>Register</h1>
		
		<form action="" method="post">
			<section class="fields">
				<label for="username">Username: </label>
				<input type="text" name="username" required value="<?php if(!empty($_POST['username'])){ echo $username; } else { echo ''; } ?>"/>
				<br>
				
				<label for="uniid">University ID Number: </label>
				<input type="number" name="uniid" required min=1 max=99999999999 value="<?php if(!empty($_POST['uniid'])){ echo $uniID; } else { echo ''; } ?>"/>
				<br>
				
				<label for="password">Password: </label>
				<input type="password" name="password" required />
				<br>
				
				<label for="password2">Re-enter Password: </label>
				<input type="password" name="password2" required />
				<br><br>
				
				<label for="fname">First Name: </label>
				<input type="text" name="fname" required value="<?php if(!empty($_POST['fname'])){ echo $fName; } else { echo ''; } ?>"/><br>
				
				<label for="lname">Last Name: </label>
				<input type="text" name="lname" required value="<?php if(!empty($_POST['lname'])){ echo $lName; } else { echo ''; } ?>"/><br>
			</section>
			
			<span class="error"><?php if(!empty($error)) foreach($error as $e) echo $e . "<br>"; ?></span>
			<span class="success"><?php if(!empty($success)) foreach($success as $s) echo $s . "<br>"; ?></span>
			<br><br>
			
			<section class="horizontalsection">
				<button type="submit" name="createStudent" value="✓">Create Student</button>
				<button type="submit" name="createProfessor" value="✓">Create Professor</button>
			</section>
		</form>
		
		<br>
		<div style="text-align: center;">
			<button style="position:relative" onclick="window.location.href = 'index.php';">Return to Login Page</button>
		</div>
	</section>
  </body>
</html>