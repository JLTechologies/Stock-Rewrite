<?php
// QUERIES
$sitename = "SELECT sitename FROM settings";

// All Listing
$users = "SELECT * FROM users";
$groups = "SELECT * FROM groups";
$products = "SELECT * FROM products";
$locations = "SELECT * FROM locations";
$rootcat = "SELECT * FROM rootcategories";
$childcat = "SELECT * FROM childcategories";
$teams = "SELECT * FROM teams";
$cowcodes = "SELECT * FROM sites";
$measurements = "SELECT * FROM measurement";
$fleet = "SELECT * FROM fleet";
$countries = "SELECT * FROM countries";
$brands = "SELECT * FROM brands";
$contacts = "SELECT * FROM contacts";
//$assets = "SELECT * FROM assets";
$sorting = "SELECT * FROM sorting";
//$sites = "SELECT * FROM sites INNER JOIN countries ON sites.countryID = countries.countryid";
$countcowcodes = "SELECT COUNT(cowcode) 'amountsites' FROM sites";
$countusers = "SELECT COUNT(name) 'amountusers' FROM users";
$countitems = "SELECT COUNT(prod_Name) 'amountitems' FROM products";
$countlocations = "SELECT COUNT(Loc_name) 'amountlocations' FROM locations";

session_start();

//session variables
$username = "";
$email = "";

// connect to database
include('./config.php');
$errors = array();

// Add User from backend
if (isset($_POST['admin_reg_user'])) {
  // receive all input values from the form
  $userfirstname = mysqli_real_escape_string($conn, $_POST['userfirstname']);
  $userlastname = mysqli_real_escape_string($conn, $_POST['userlastname']);
  $useremail = mysqli_real_escape_string($conn, $_POST['useremail']);
  $userphone = mysqli_real_escape_string($conn, $_POST['userphone']);
  $useractive = mysqli_real_escape_string($conn, $_POST['useractive']);
  $usergroup = mysqli_real_escape_string($conn, $_POST['usergroup']);
  $password1 = mysqli_real_escape_string($conn, $_POST['password1']);
  $password2 = mysqli_real_escape_string($conn, $_POST['password2']);
  $created_on = new DateTime('now');

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($userfirstname)) { array_push($errors, "First name is required"); }
  if (empty($userlastname)) { array_push($errors, "Last name is required"); }
  if (empty($useremail)) { array_push($errors, "Phone number is required"); }
  if (empty($userphone)) { array_push($errors, "Email is required"); }
  if (empty($useractive)) { array_push($errors, "Active status is required"); }
  if (empty($usergroup)) { array_push($errors, "Group selection is required"); }
  if (empty($password1)) { array_push($errors, "Password is required"); }
  if ($password1 != $password2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE first_name='$userfirstname' OR last_name='$userlastname' OR email='$useremail' LIMIT 1";
  $result = mysqli_query($conn, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] === $useremail) {
      array_push($errors, "Email is already used by another account.");
    }

    if ($user['first_name'] === $userfirstname && ['last_name'] === $userlastname) {
      array_push($errors, "Person already exists.");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (first_name, last_name, groupID email, phone, active, created_on, password) 
  			  VALUES('$userfirstname', '$userlastname', '$usergroup', '$useremail', '$userphone', '$useractive', '$created_on', '$password')";
  	mysqli_query($conn, $query);
  	$_SESSION['success'] = "$userlastname $userfirtname is now registered";
  	header('location: ./index.php');
  }
}

// Update User

// Remove User

// Login User
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
  
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $hashed_password = md5($password);
        $query = "SELECT * FROM users WHERE email='$email' AND password='$hashed_password'";
        $results = mysqli_query($conn, $query);
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['email'] = $email;
          $_SESSION['success'] = "Welcome $email.";
          header('location: ./index.php');
        }else {
            array_push($errors, "Wrong email or password combination");
        }
    }
  }

// Add group

// Update group

// Remove group

// Function add perms per group

// Add Product

// Update product 

// remove product

// Function add minimumamount per location that is registered at that time

// Add rootcategory

// update rootcategory

// remove category

// adjust root active state

// add child

// update child

// remove child

// add brand

// update brand

// remove brand

// add contact

// update contact

// remove contact

// add location

// update location

// remove location

// add measurement

// update measurement

// remove measurement

// add sorting

// update sorting

// remove sorting

// adjust sitename

// adjust mailsettings

// adjust activestatus site

// add cowcode

// update cowcode

// remove cowcode

// update group perm

// add fleet

// update fleet

// remove fleet

// add asset

// update asset

// remove asset

// add team

// update team

// remove team

// add order

// update order

// remove order

// update footer