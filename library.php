<?php 
/*INT322
STEVEN SZU HAN CHEN
10/19/12

Student Declaration

I/we declare that the attached assignment is my/our own work in accordance with Seneca Academic Policy. No part of this assignment has been copied manually or electronically from any other source (including web sites) or distributed to other students.

Name: STEVEN SZU HAN CHEN

Student: ID 064344112
*/

// show navigation
function menuform() {
	$menu_items = array("[ Add ]","[ View All ]", "[ On Sales Only ]", "[ Discontinued Only ]");
	$menu_item_links = array("add.php","view.php", "view.php?onsale=true", "view.php?discont=true");
	$i=0;
	print "<div class='menuform'>";
	while ($i < count($menu_items)) {
		print "<a href='{$menu_item_links[$i]}'>" . $menu_items[$i++] . "</a> ";
	}
	print "</div>";
}
// show search box
function searchbox() {
	print "<div class='searchbox'><form action='view.php' method='post'>Search Item Description: <input type='text' name='search' value='" . $_POST['search'] . "'> &nbsp; <input type='submit' value='Search Database'></form></div>";
}
// show logout menu
function logout($username, $role) {
	print "<div class='logout'><b>User:</b>&nbsp;<u>" . $username . "</u>&nbsp;&nbsp;<b>Role:</b>&nbsp;<u>" . $role . "</u>&nbsp;&nbsp;<a href='logout.php?logout=ok'>Logout</a></div>";
}
// show login menu
function print_login($input_error, $query_error) {
	print "<div id='login'>";
	print "<form action='' method='post'>";
	print "<h3>Log In</h3>";
	print "<input id='username' type='text' name='username' placeholder='Username' autofocus required><br/>";
	print "<input id='password' type='password' name='password' placeholder='Password' required><br /><br />";
	print "<input type='submit' id='submit' value='Submit'>";
	print "</form>";
	print "<p>";
	print $input_error;
	print $query_error;
	print "</p>";
	print "<p>";
	print "<a href='login.php?forget=true'>Forget your password?</a>";
	print "</p>";
	print "</div>";

}
// print email password hint
function print_email($input_error, $query_error) {
	print "<div id='login'>";
	print "<form method='post' action=''>";
	print "<h3>Request Password Hint</h3>";
    print "<input type='text' name='username' placeholder='Email' required /><br /><br />";
	print "<input type='submit' value='Send Hint'>";
	print "</form>";
	print "<p>";
	print $input_error;
	print $query_error;
	print "</p>";
}
// validations for each fields
function validate_itemname($itemname) {
	return preg_match("/^\s*[a-zA-Z][a-zA-Z ]*\s*$/", $itemname); // letters and spaces only
}
function validate_manu($manu) {
	return preg_match("/^\s*[a-zA-Z-][a-zA-Z -]*\s*$/", $manu); // letters, spaces and dash only
}
function validate_model($model) {
	return preg_match("/^\s*([a-zA-Z]|[0-9])([a-zA-Z -]|[0-9])*\s*$/", $model); // digits, letters, spaces and dash only
}
function validate_descrip($descrip) {
	return preg_match("/^\s*([a-zA-Z]|[0-9])([a-zA-Z .,]|[0-9])*(\n)*\s*$/", $descrip); // digits, letters, periods, commas, spaces and new lines only
}
function validate_onhands($onhands) {
	return preg_match("/^\d+$/", $onhands); // digits only
}
function validate_reorder($reorder) {
	return preg_match("/^\d+$/", $reorder); // digits only
}
function validate_cost($cost) {
	return preg_match("/^[\d]{1,}?\.[\d]{2}$/", $cost); // monetary
}
function validate_price($price) {
	return preg_match("/^[\d]{1,}?\.[\d]{2}$/", $price); // monetary with two decimal
}
// print footer
function print_footer() {
	print "<br />";
	print "<footer>This page - Epicure Music Store&copy; was generated on " . date("F j, Y, g:i a") . "</footer>";
}
// database connection class
class LinkDB {
	private $link;
     public 
        function __construct($DBname){
			$lines = file('/home/int322_123c22/public_html/secret/topsecret.txt');
			$user = trim($lines[0]);
			$pw = trim($lines[1]);
			$host = trim($lines[2]);
			$database = trim($lines[3]);
           	$link = mysql_connect($database, $user, $pw) or die('Could not connect: ' . mysql_error());
			mysql_select_db($DBname) or die('can not access database: '.mysql_error());
        }
}
?>