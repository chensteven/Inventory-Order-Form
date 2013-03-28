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

// Inialize session
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
// Delete certain session
if($_GET['logout']) {
    if(isset($_SESSION['username'])) {    
        unset($_SESSION['username']);
        session_destroy();
        setcookie("PHPSESSID","", time()-61200);
        header("Location: login.php");
    }
}
?>