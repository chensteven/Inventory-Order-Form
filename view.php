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
    session_start();
     if(!isset($_SESSION['username'])){
       header("Location: login.php");
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="mystyle.css" media="screen" />
    <title>Epicure Music Store</title>
</head>
<body>
<?php 
    include "library.php";
?>
<div class="container">
<h1>&#9834; Epicure Music Store &#9834;</h1>
<?php
    // Variables from Session
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    menuform();
    searchbox();
    logout($username, $role);
?>
<section class="form">
<?php
    $DBname = "int322_123c22";
	$database = new LinkDB($DBname);
    
	// Check if there is a column variable past, if not column is set as ID - Table is ascending by ID
	if(!isset($_GET['column'])) {
		$column = "id";
	}
	else {
		$column = $_GET['column'];	
	}
?>
<?php
	
    // Print table column headers
    print "<table class='dbs'><tr>" . "<th><a href='view.php?column=id'>ID</a></th>" . "<th><a href='view.php?column=name'>Item Name</a></th>" . "<th><a href='view.php?column=manufac'>Manufacturer</a></th>" . "<th><a href='view.php?column=model'>Model</a></th>" . "<th class='width1'><a href='view.php?column=descrip'>Description</a></th>" . "<th><a href='view.php?column=onhand'>Number on Hand</a></th>" . "<th><a href='view.php?column=reorder'>Reorder Level</a></th>" . "<th><a href='view.php?column=cost'>Cost</a></th>" . "<th><a href='view.php?column=price'>Selling Price</a></th>" . "<th>On Sale?</th>" . "<th>Discontinued</th>" . "<th class='width2'>Delete/Restore?</th>" . "</tr>";
    
    // If there is a search, do this query
	if($_POST) {
		$search= $_POST['search'];
		$query = "SELECT * from inventory where descrip like '%$search%' ";
    }
	// If onsale is clicked, do this query
	if($_GET['onsale']) {
        $query = "SELECT * from inventory where sale='y' order by'" . $column ."'";
	}
	// if discont is clicked, do this query
	if ($_GET['discont']) {
		$query = "SELECT * from inventory where discont='y' order by'" . $column ."'";
	} 
	// if none of the above is clicked, do this default query
	if(((!$_GET['discont'] && !$_GET['onsale']) && !$_POST['search'])) {
		$query = "SELECT * from inventory order by '" . $column . "'";
	}
	
	$result = mysql_query($query) or die('Query failed'.mysql_error());
	
	$count = mysql_num_rows($result);
	
	// If no records, print result
	if (!$count) {
		print "</table>";
		print "<p>No records are found</p>";
	}
	
	// Print table data
	while($row = mysql_fetch_assoc($result)) {
		print "<tr><td><a href='add.php?id={$row['id']}'>" . $row['id'] . "</a></td><td>" . $row['name'] . "</td><td>" . $row['manufac'] . "</td><td>" . $row['model'] . "</td><td>" . $row['descrip'] . "</td><td>" . $row['onhand'] . "</td><td>" . $row['reorder'] . "</td><td>" . $row['cost'] . "</td><td>" . $row['price'] . "</td><td>" . $row['sale'] . "</td><td>" . $row['discont'] . "</td>";
		//Conditional statement for showing delete or restore
		if ($row['deleted'] == 'y') {
			print "<td><a href='delete.php?id=" . $row['id'] . '&deleted=' . $row['deleted'] . "'>Restore</a></td></tr>";
		}
		if ($row['deleted'] == 'n') {
			print "<td><a href='delete.php?id=" . $row['id'] . '&deleted=' . $row['deleted'] . "'>Delete</a></td></tr>";
		}
	}
	
	// Close table tag
	print "</table>";
	
	// Free result query
	mysql_free_result($result);
	
	// mysql_close($link);
		
?>
</section>
<?php
    print_footer();
?>
</div>
</body>
</html>
