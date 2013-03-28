<?php 
/*INT322
STEVEN SZU HAN CHEN
10/19/12

Student Declaration

I/we declare that the attached assignment is my/our own work in accordance with Seneca Academic Policy. No part of this assignment has been copied manually or electronically from any other source (including web sites) or distributed to other students.

Name: STEVEN SZU HAN CHEN

Student: ID 064344112
*/
?>
<?php
include "library.php";

// Start session
session_start();

// If logout is clicked
if($_GET['logout']=='ok'){
    if(isset($_SESSION['username'])){
        unset($_SESSION);
        session_destroy();
        setcookie("PHPSESSID","",time()-61200);
        header("Location: login.php");
    }
}

// Get login information
if($_POST) {
    $input_error = "";
    $query_error = "";
    $valid = true;
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
	$pwdHint = "";
	$logged = false;
	
	// validate username
    if(!preg_match("/^([a-zA-Z\d][\w\-\.]*)@((([a-zA-Z][\w\-]*\.)+)([a-zA-Z]{2,4}))$/",$username)){
        $valid = false;
        $input_error = "Please input the correct username or password";
    }
	// if user didn't clicks forget then validate password too
	if(!$_GET['forget']) {
		if(!preg_match("/^[a-zA-Z0-9._=~&@*!#$%^&*\?+-]{6,18}$/", $password)){
			$valid = false;
			$input_error = "Please input the correct username or password";
		}
    }
    if($valid) {
		// Connect to database
        $DBname = "int322_123c22";
		$database = new LinkDB($DBname);
		
		// Check query for passwordHINT
		if($_GET['forget']) {
			if($_POST['username']) {
				$username = trim($_POST['username']);
				$query = "SELECT passwordHint from users where username='" . $username . "'"; 
				$result = mysql_query($query) or die('Query failed'.mysql_error());
				$row = mysql_fetch_assoc($result);
				
				if($row) {
					$pwdHint = $row['passwordHint'];
					if ($pwdHint != "") {
						$wordhint ="Your password hint is:" . $pwdHint;
						$header = "From: Steven <sschen4@myseneca.ca>\nReply-to: Steven <sschen4@myseneca.ca>";
						$send = mail($username,"Password Hint", $wordhint, $header);
						header("Location:login.php");
					}
				}
				else {
					$query_error = "Please input the correct username or password";
					$_GET['forget']=false;
					header("Location:login.php");
				}

				// Free result
				mysql_free_result($result);
			}
		}
		// Check query for a username
		if(!$_GET['forget']) {
			$query = "SELECT * from users where username='" . $username ."'";
			$result = mysql_query($query) or die('Query failed'.mysql_error());
			$row = mysql_fetch_assoc($result);
       
			// Get encrypted password
			$salt = substr($row['password'], 0, 12);
			$password = crypt($password, $salt);
			$logged = false;
        
			// Free result
			mysql_free_result($result);
        
			// Querying for correct username and password
			$query = "SELECT * from users where username='". $username ."' and password='" . $password . "'";
			$result = mysql_query($query) or die('Query failed'.mysql_error());
			$count = mysql_num_rows($result);
			
			if($count == 1) {
				session_start();
				session_regenerate_id();
				$_SESSION['username'] = $username;
				$_SESSION['role'] = $row['role'];
				$logged = true;
				}
			else {
			    $query_error = "Please input the correct username or password";
				$valid = false;
			}
			// Free result
			mysql_free_result($result);
        
			/* Another way of querying for correct username and password
			if($row['password'] == $password && $row['username'] == $username) {
				session_start();
				session_regenerate_id();
				$_SESSION['username'] = $username;
				$_SESSION['role'] = $row['role'];
				$logged = true;
			}
			else {
				$valid = false;
				$query_error = "Please input the correct username or password";
			}
			mysql_free_result($result);
			*/
		}
    }
    if($logged) {
        header("Location: view.php");
    }
}
// If incorrect input format or no submission
if(!$valid||!$_POST) {
	
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="mystyle.css" media="screen" />
        <title>Epicure Music Store</title>
    </head>
    <body>
        <div class="container">
            <h1>&#9834; Epicure Music Store &#9834;</h1>
			
			<?php 
				if($_GET['forget']) {
					print_email($input_error, $query_error);
				}
				else {
					print_login($input_error, $query_error); 
				}	
			?>
        </div>
    </body>
    </html>
<?php
}
?>
