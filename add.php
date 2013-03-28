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

// Include library
include "library.php";

// If there is no ID sent, ID is NULL
if(!$_GET['id']) {
	$id = NULL;
}
else {
	$id = $_GET['id'];
}

// Declare variables
$valid = true;
$itemNameErr = "";
$manufacturerErr = "";
$modelErr = "";
$descriptionErr = "";
$onhandErr = "";
$reorderErr = "";
$costErr = "";
$priceErr = "";

// If user submits
if ($_POST) {
	// Validate inputs
    if (!validate_itemname($_POST['itemname'])) {
        $valid = false;
        $itemNameErr = "&nbsp;Incorrect format";
    }
    if (!validate_manu($_POST['manufacturer'])){
        $valid = false;
        $manufacturerErr = "&nbsp;Incorrect format";
    }
    if (!validate_model($_POST['model'])){
        $valid = false;
        $modelErr = "&nbsp;Incorrect format";
    }
    if (!validate_descrip($_POST['description'])) {
        $valid = false;
        $descriptionErr = "&nbsp;Incorrect format";
    }
    if (!validate_onhands($_POST['numberonhand'])) {
        $valid = false;
        $onhandErr = "&nbsp;Incorrect format";
    }
    if (!validate_reorder($_POST['reorderlevel'])){
        $valid = false;
        $reorderErr = "&nbsp;Incorrect format";
    }
    if (!validate_cost($_POST['cost'])) {
        $valid = false;
        $costErr = "&nbsp;Incorrect format";
    }
    if (!validate_price($_POST['sellingprice'])) {
        $valid = false;
        $priceErr = "&nbsp;Incorrect format";
    }
	
	// If inputs are valid
    if ($valid) {
		// Create a database connection
		$DBname = "int322_123c22";
        $database = new LinkDB($DBname);
		
		// Store variables from post submission
		$deleted = 'n';
        $name = $_POST['itemname'];
        $manufac = $_POST['manufacturer'];
        $model = $_POST['model'];
        $descrip = $_POST['description'];
        $onhand = $_POST['numberonhand'];
        $reorder = $_POST['reorderlevel'];
        $cost = $_POST['cost'];
        $price = $_POST['sellingprice'];
    
        if(isset($_POST['saleitem'])) {
            $sale = 'y';
        }
        else {
            $sale = 'n';
        }
        if(isset($_POST['discontinued'])) {
            $discont = 'y';
        }
        else {
            $discont = 'n';
        }
		// Trim variables for storage in database
        $name = trim($name); 
        $manufac = trim($manufac); 
        $model = trim($model); 
        $descrip = trim($descrip); 
        $onhand = trim($onhand); 
        $reorder = trim($reorder); 
		
		// Check if it is a new add or a modify
		if($_POST['submit'] == 'Modify') {
			$query = "UPDATE inventory SET name ='" . $name . "', manufac='" . $manufac . "', model='" . $model ."', descrip='" . $descrip . "', onhand='" . $onhand . "', reorder='" . $reorder . "', cost='" . $cost . "', price='" . $price ."', sale='" . $sale . "', discont='" . $discont . "', deleted='" . $deleted . "' WHERE id='" . $id . "'"; 
		}
		if($_POST['submit'] == 'Add') {
			$query = "INSERT INTO inventory VALUES ('" . $id . "', '" . $name . "', '" . $manufac . "', '" . $model . "', '" . $descrip . "', '" . $onhand . "', '" . $reorder . "', '" . $cost . "', '" . $price . "', '" . $sale . "', '" . $discont . "', '" . $deleted . "')";
        }
        
		$result = mysql_query($query) or die('Query failed111'.mysql_error());
      
        header("Location: view.php");
    } // End Valid
} // End Post

// If not valid or submission yet
if (!$valid || !$_POST) {
	// Check if it is a modify of a current item by getting the ID
	if($_GET['id']) {
		$DBname = "int322_123c22";
        $database = new LinkDB($DBname);
		
		$query = "SELECT * from inventory where id =" . $id ;
		$result = mysql_query($query) or die('Query failed'.mysql_error());
		$row = mysql_fetch_assoc($result);
		
		$name = $row['name'];
		$manufac = $row['manufac'];
        $model = $row['model'];
        $descrip = $row['descrip'];
        $onhand = $row['onhand'];
        $reorder = $row['reorder'];
        $cost = $row['cost'];
        $price =$row['price'];
		$sale = $row['sale'];
		$discont = $row['discont'];
		$deleted = $row['deleted'];
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
    <div class="container">
        <h1>&#9834; Epicure Music Store &#9834;</h1>
        <?php
            // Variables from Session
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            menuform();
            logout($username, $role);
        ?>
        <p class="note">All fields mandatory except Sale Item and Discontinued Item</p>
        <section class="form">
            <form method="post" action="add.php?id=<?php print $id; ?>">
                <div class="box1">
                    <table class="box2">
					<?php if($_GET['id']) { ?>
						<tr>
							<td class='right'>Item ID:</td>
                            <td class='left'><input type='text' name='itemname' value="<?php print $id; ?>" readonly="readonly" /></td>
						</tr>
					<?php } ?>
                        <tr>
                            <td class='right'>Item Name:</td>
                            <td class='left'><input type='text' name='itemname' value="<?php if($_GET['id']) {print $name;} else {print $_POST['itemname'];} ?>" /></td>
                            <?php print "<td class='error'>" . $itemNameErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Manufacturer:</td>
                            <td class='left'><input type='text' name='manufacturer' value="<?php if($_GET['id']) {print $manufac;} else {print $_POST['manufacturer'];} ?>" /></td>
                            <?php print "<td class='error'>" . $manufacturerErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Model:</td>
                            <td class='left'><input type='text' name='model' value="<?php if($_GET['id']) {print $model;} else {print $_POST['model'];} ?>" /></td>
                            <?php print "<td class='error'>" . $modelErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Description:</td> 
                            <td class='left'><textarea name='description' rows='4' cols='17'><?php if($_GET['id']) {print $descrip;} else {echo $_POST['description'];} ?></textarea></td>
                            <?php print "<td class='error'>" . $descriptionErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Number on Hand:</td> 
                            <td class='left'><input type='text' name='numberonhand' value="<?php if($_GET['id']) {print $onhand;} else {print $_POST['numberonhand'];} ?>" /></td>
                            <?php print "<td class='error'>" . $onhandErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Reorder Level:</td> 
                            <td class='left'><input type='text' name='reorderlevel' value="<?php if($_GET['id']) {print $reorder;} else {print $_POST['reorderlevel'];} ?>" /></td>
                            <?php print "<td class='error'>" . $reorderErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Cost:</td> 
                            <td class='left'><input type='text' name='cost' value="<?php if($_GET['id']) {print $cost;} else {print $_POST['cost'];} ?>" /></td>
                            <?php print "<td class='error'>" . $costErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Selling Price:</td>
                            <td class='left'><input type='text' name='sellingprice' value="<?php if($_GET['id']) {print $price;} else {print $_POST['sellingprice'];} ?>" /></td>
                            <?php print "<td class='error'>" . $priceErr . "</td>" ?>
                        </tr>
                        <tr>
                            <td class='right'>Sale Item:</td>
                            <td class='left'><input type='checkbox' value='1' <?php  if((isset($_POST['saleitem'])) || ($_GET['id'] && ($sale == 'y'))) echo "checked='checked'"; ?> name='saleitem'></td>
                        </tr>
                        <tr>
                            <td class='right'>Discontinued Item:</td>
                            <td class='left'><input type='checkbox' value='2' <?php  if((isset($_POST['discontinued'])) || ($_GET['id'] && ($discont == 'y'))) echo "checked='checked'"; ?>  name='discontinued'></td>
                        </tr>
                        <tr>
                            <td class='right'><input type='submit' name='submit' value="<?php if($_GET['id']) { echo 'Modify'; } else { echo 'Add'; } ?>" /></td>
                        </tr>
                    </table>
                </div>
            </form>
        </section>
<?php
} // End !Valid or !Post
print_footer();
?>
    </div>
    </body>
    </html>