<?php 
/*INT322
STEVEN SZU HAN CHEN
10/19/12

Student Declaration

I/we declare that the attached assignment is my/our own work in accordance with Seneca Academic Policy. No part of this assignment has been copied manually or electronically from any other source (including web sites) or distributed to other students.

Name: STEVEN SZU HAN CHEN

Student: ID 064344112
*/
include "library.php";

// Start new connection
$DBname = "int322_123c22";
$database = new LinkDB($DBname);

$id = (int)$_GET['id'];

$deleted = $_GET['deleted'];

$tbl_name = 'inventory';

if ($_GET['deleted'] == 'y') {
   $query= "UPDATE {$tbl_name} SET deleted='n' WHERE id ='{$id}'";
   $result = mysql_query($query) or die('Query failed'.mysql_error());  
} 
if ($_GET['deleted'] == 'n') {
   $query= "UPDATE {$tbl_name} SET deleted='y' WHERE id ='{$id}'";
   $result = mysql_query($query) or die('Query failed'.mysql_error());      
}

header('Location: view.php'); 	
?>