<?php
// QUERIES
$sitename = "SELECT Sitename FROM settings";
$logo ="SELECT Favicon FROM settings";

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
$settings = "SELECT * FROM settings";
$sites = "SELECT * FROM sites INNER JOIN countries ON sites.countryID = countries.countryid";
$countcowcodes = "SELECT COUNT(cowcode) 'amountsites' FROM sites";
$countusers = "SELECT COUNT(Last_Name) 'amountusers' FROM users";
$countitems = "SELECT COUNT(prod_Name) 'amountitems' FROM products";
$countlocations = "SELECT COUNT(Loc_name) 'amountlocations' FROM locations";

session_start();

//session variables
$username = "";
$email = "";

require('config.php');

/*// connect to database
$host = "localhost";
$user = "jerlag";
$password = "VTIkontich.05";
$database = "erp";

$conn = new mysqli($host, $user, $password, $database);
$conn->connect_errno;
print $conn->error;

if (mysqli_connect_error()) {
    echo "Failed to connect to database :$database @ $host" . mysqli_connect_error();
}*/

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
  $userlanguage = mysqli_real_escape_string($conn, $_POST['userlanguage']);
  $userteamid = mysqli_real_escape_string($conn, $_POST['userteamid']);
  $password1 = mysqli_real_escape_string($conn, $_POST['password1']);
  $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

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
  $user_check_query = "SELECT * FROM users WHERE First_Name='$userfirstname' OR Last_Name='$userlastname' OR Email='$useremail' LIMIT 1";
  $result = mysqli_query($conn, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] === $useremail) {
      array_push($errors, "Email is already used by another account.");
    }

    if ($user['First_Name'] === $userfirstname && ['Last_Name'] === $userlastname) {
      array_push($errors, "Person already exists.");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (First_Name, Last_Name, Email, Phone, Active, TeamID, GroupID, Password, User_Language) 
  			  VALUES('$userfirstname', '$userlastname', '$useremail', '$userphone', '$useractive', '$userteamid', '$usergroup', '$password', '$userlanguage')";
  	mysqli_query($conn, $query);
  	$_SESSION['success'] = "$userlastname $userfirstname is now registered";
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

// ADD GROUP
if (isset($_POST['add_group'])) {
    $gname = mysqli_real_escape_string($conn, $_POST['groupname']);
    $gactive = mysqli_real_escape_string($conn, $_POST['groupactive']);

    if (empty($gname)) {
      array_push($errors, "Groupname is required");
    }
  
    if (count($errors) == 0) {
      $groupadd2 = "INSERT INTO groups (Group_Name, Group_Active)" ."VALUES ('$gname', '$gactive')";
      mysqli_query($conn,$groupadd2);      
      add_perm($gname, $conn);

      if (isset($result) && $result == "done") {
        $_SESSION['success'] = "New group created";
      header('location: ./index.php');
      }
    }
  }
  
  //FUNCTION ADD PERMS PER GROUP
  function add_perm($gname, $conn) {
    $newgroupadd = "SELECT GroupID FROM groups WHERE Group_Name = '$gname'";
      $amountperm = "SELECT COUNT(permissionID) as aantalperms FROM permissionslist";

      $getnewgroupadd = mysqli_query($conn, $newgroupadd);
      $getamountperm = mysqli_query($conn, $amountperm);

      while ($row3 = mysqli_fetch_assoc($getnewgroupadd)) {
        $newgroupaddID = htmlspecialchars($row3['GroupID']);
      }

      while ($row4 = mysqli_fetch_assoc($getamountperm)) {
        $getamountperms = htmlspecialchars($row4['aantalperms']);
      }
      
      for ($i = 1; $i <= $getamountperms; $i++) {
        $addgroupperm = "INSERT INTO permissions (setting, groupID, permissionID)" ."VALUES ('false','$newgroupaddID','$i')";
        mysqli_query($conn,$addgroupperm);
      }
      if ($i === $getamountperms) {
        unset($i, $newgroupadd, $getamountperms);
        $result = "done";
        return $result;
      }

  }

  // REMOVE GROUP
  if (isset($_POST['group_remove'])) {
    $groupid2 = mysqli_real_escape_string($conn, $_POST['groupremove']);
    $groupremove = "DELETE FROM groups WHERE GroupID = '$groupid2'";
    mysqli_query($conn, $groupremove);
    $_SESSION['success'] = "Group has been removed";
    header('location: ./index.php');
  }

  //UPDATE GROUP
  if (isset($_POST['edit_group'])) {
    $groupid3 = mysqli_real_escape_string($conn, $_POST['groupid']);
    $newgroupname = mysqli_real_escape_string($conn, $_POST['newgroupname']);
    
    if (empty($newgroupname)) {
      array_push($errors, "Groupname is required to be filled in");
    }

    if (count($errors) == 0) {
      $updategroup = "UPDATE groups SET Group_Name = $newgroupname WHERE GoupID = $groupid3";
      mysqli_query($conn, $updategroup);
      $_SESSION['success'] = "Group $newgroupname has been updated";
      header('location: ./index.php');
    }
  }

  //Update group permission

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
if (isset($_POST['create_team'])) {
  $teamname = mysqli_real_escape_string($conn, $_POST['teamname']);

  if (empty($teamname)) {
    array_push($errors, "Teamname is required");
  }

  if (count($errors) == 0) {
    $addteam = "INSERT INTO teams (Team_Name)" ."VALUES ('$teamname')";
    mysqli_query($conn,$addteam);
    $_SESSION['success'] = "New team created";
    header('location: ./index.php');
  }
}

// update team
if (isset($_POST['update_team'])) {
  $teamid = mysqli_real_escape_string($conn, $_POST['teamid']);
  $teamname = mysqli_real_escape_string($conn, $_POST['teamname']);

  if (empty($teamname)) {
    array_push($errors, "Teamname is required");
  }

  if (count($errors) == 0) {
    $updateteam = "UPDATE teams SET Team_Name = '$teamname' WHERE TeamID = '$teamid'";
    mysqli_query($conn, $updateteam);
    $_SESSION['success'] = "Team has been updated";
    header('location: ./index.php');
  }
}

// remove team
if (isset($_POST['remove_team'])) {
  $teamid = mysqli_real_escape_string($conn, $_POST['teamid']);
  $removeteam = "DELETE FROM teams WHERE TeamID = '$teamid'";
  mysqli_query($conn, $removeteam);
  $_SESSION['success'] = "Team has been removed";
  header('location: ./index.php');
}

// add order

// update order

// remove order

// update footer

// update favicon
if (isset($_POST['update_favicon'])) {
  $target_dir = "img/";
  if(!empty($_FILES["file"]["name"])) {
    $fileName = basename($_FILES["file"]["name"]);
    $targetfilePath = $target_dir . $fileName;
    $fileType = pathinfo($targetfilePath,PATHINFO_EXTENSION);

    // Allow certain file formats
    $allowTypes = array('jpg', 'png');
    if(in_array($fileType, $allowTypes)) {
      //upload file to server
      if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetfilePath)) {
        //insert image file name into database
        $insert = $conn->query("UPDATE settings SET Favicon = '$fileName'");
        if($insert) {
          $_SESSION['success'] = "Favicon has been updated succesfully";
        } else {
          array_push($errors, "File upload failed, please try again");
        }
        } else {
          array_push($errors, "There waas an error uploading the file");
        }
      } else {
        array_push($errors, "Only JPG or PNG files are allowed");
      }
    } else {
      array_push($errors, "Select a file to upload");
    }
  }

// fetch sitename
$sitenamefetch = mysqli_query($conn, $sitename);
if (! $sitenamefetch) {
  die('Could not load sitename: '.mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($sitenamefetch)) {
  $site = htmlspecialchars($row['Sitename']);
}

// fetch favicon
$faviconlogo = mysqli_query($conn, $logo);
  if (! $faviconlogo) {
    die('Logo does not exist: '.mysqli_error($conn));
  }
  while($favlog = mysqli_fetch_assoc($faviconlogo)) {
    $falo = htmlspecialchars($favlog['Favicon']);
  }