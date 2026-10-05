<?php
// QUERIES
$sitename = "SELECT Sitename FROM settings";
$logo ="SELECT Favicon FROM settings";

// All Listing
$users = "SELECT * FROM users";
$groups = "SELECT * FROM groups";
$products = "SELECT * FROM products";
$locations = "SELECT * FROM locations INNER JOIN countries ON locations.Loc_CountryID = countries.countryid";
$rootcat = "SELECT * FROM rootcategories";
$childcat = "SELECT * FROM childcategories";
$teams = "SELECT * FROM teams";
$cowcodes = "SELECT * FROM sites";
$measurements = "SELECT * FROM measurement";
$fleet = "SELECT * FROM fleet";
$countries = "SELECT * FROM countries";
$brands = "SELECT * FROM brands";
$contacts = "SELECT * FROM contacts";
$contactpersons = "SELECT * FROM sitecontact;";
$sitetypes = "SELECT * FROM cowcodetype";
//$assets = "SELECT * FROM assets";
$sorting = "SELECT * FROM sorting";
$settings = "SELECT * FROM settings";
$sites = "SELECT * FROM sites INNER JOIN countries ON sites.countryID = countries.countryid INNER JOIN sitecontact ON sites.site_contactID = sitecontact.sites_contactID INNER JOIN cowcodetype ON sites.site_type_ID = cowcodetype.cowcodetype_ID";
$countcowcodes = "SELECT COUNT(cowcode) 'amountsites' FROM sites";
$countusers = "SELECT COUNT(Last_Name) 'amountusers' FROM users";
$countitems = "SELECT COUNT(prod_Name) 'amountitems' FROM products";
$countlocations = "SELECT COUNT(Loc_name) 'amountlocations' FROM locations";
$userlist = "SELECT * FROM users INNER JOIN teams ON users.TeamID = teams.TeamID INNER JOIN groups ON users.GroupID = groups.GroupID";

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
  $userteamid = mysqli_real_escape_string($conn, $_POST['userteam']);
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
  if (empty($userteamid)) { array_push($errors, "Team selection is required"); }
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
if (isset($_POST['create_group'])) {
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
      $amountperm = "SELECT COUNT(permlistID) as aantalperms FROM permissionslist";

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
  if (isset($_POST['remove_group'])) {
    $groupid2 = mysqli_real_escape_string($conn, $_POST['group_id']);
    $groupremove = "DELETE FROM groups WHERE GroupID = '$groupid2'";

    if (empty($groupid2)) {
      array_push($errors, "GroupID is required to be filled in");
    }

    if (count($errors) == 0) {
        mysqli_query($conn, $groupremove);
        $_SESSION['success'] = "Group has been removed";
        header('location: ./index.php');
    }
  }

  //UPDATE GROUPNAME
  if (isset($_POST['update_groupname'])) {
    $groupid3 = mysqli_real_escape_string($conn, $_POST['groupid']);
    $newgroupname = mysqli_real_escape_string($conn, $_POST['groupname']);
    
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
    if (isset($_POST['edit_perm'])) {
      $permid = mysqli_real_escape_string($conn, $_POST['permid']);
      $newstatus = mysqli_real_escape_string($conn, $_POST['new_status']);
      $currentgroupid = mysqli_real_escape_string($conn, $_POST['permsedit2']);

      $updateperm = "UPDATE permissions SET setting = '$newstatus' WHERE permissionID = $permid";
      mysqli_query($conn, $updateperm);
      $_SESSION['success'] = "Permission has been updated";
      return $currentgroupid;
      header("location: ./perms.php");
    }

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
if (isset($_POST['create_location'])) {
  $locationname = mysqli_real_escape_string($conn, $_POST['locationname']);
  $locationstreet = mysqli_real_escape_string($conn, $_POST['locationstreet']);
  $locationnumber = mysqli_real_escape_string($conn, $_POST['locationnumber']);
  $locationaddition = mysqli_real_escape_string($conn, $_POST['locationaddition']);
  $locationzipcode = mysqli_real_escape_string($conn, $_POST['locationzipcode']);
  $locationcity = mysqli_real_escape_string($conn, $_POST['locationcity']);
  $locationstate = mysqli_real_escape_string($conn, $_POST['locationstate']);
  $locationcountryid = mysqli_real_escape_string($conn, $_POST['locationcountryid']);

  if (empty($locationname) || empty($locationstreet) || empty($locationnumber) || empty($locationzipcode) || empty($locationcity) || empty($locationstate) || empty($locationcountryid)) {
    array_push($errors, "All fields are required");
  }

  if (empty($locationaddition)) {
    if (count($errors) == 0) {
    $addlocation = "INSERT INTO locations (Loc_name, Loc_Street, Loc_number, Loc_zipcode, Loc_city, Loc_state, Loc_CountryID)" ."VALUES ('$locationname', '$locationstreet', '$locationnumber', '$locationzipcode', '$locationcity', '$locationstate', '$locationcountryid')";
    mysqli_query($conn,$addlocation);
    $_SESSION['success'] = "New location created";
    header('location: ./index.php');
    }
  }
  elseif (count($errors) == 0) {
    $addlocation = "INSERT INTO locations (Loc_name, Loc_Street, Loc_number, Loc_addition, Loc_zipcode, Loc_city, Loc_state, Loc_CountryID)" ."VALUES ('$locationname', '$locationstreet', '$locationnumber', '$locationaddition', '$locationzipcode', '$locationcity', '$locationnicename', '$locationcountryid')";
    mysqli_query($conn,$addlocation);
    $_SESSION['success'] = "New location created";
    header('location: ./index.php');
  }
}

// update location
if (isset($_POST['edit_location'])) {
  $newlocationid = mysqli_real_escape_string($conn, $_POST['locationid']);
  $newlocationname = mysqli_real_escape_string($conn, $_POST['locationname']);
  $newlocationstreet = mysqli_real_escape_string($conn, $_POST['locationstreet']);
  $newlocationnumber = mysqli_real_escape_string($conn, $_POST['locationnumber']);
  $newlocationaddition = mysqli_real_escape_string($conn, $_POST['locationaddition']);
  $newlocationzipcode = mysqli_real_escape_string($conn, $_POST['locationzipcode']);
  $newlocationcity = mysqli_real_escape_string($conn, $_POST['locationcity']);
  $newlocationstate = mysqli_real_escape_string($conn, $_POST['locationstate']);
  $newlocationcountryid = mysqli_real_escape_string($conn, $_POST['locationcountryid']);

  if (empty($newlocationname) || empty($newlocationstreet) || empty($newlocationnumber) || empty($newlocationzipcode) || empty($newlocationaddition) || empty($newlocationcity) || empty($newlocationstate) || empty($newlocationcountryid)) {
    array_push($errors, "All fields are required");
  }

  if (count($errors) == 0) {
    $updatelocation = "UPDATE locations SET Loc_name = '$newlocationname', Loc_Street = '$newlocationstreet', Loc_number = '$newlocationnumber', Loc_addition = '$newlocationaddition', Loc_zipcode = '$newlocationzipcode', Loc_city = '$newlocationcity', Loc_state = '$newlocationstate', Loc_CountryID = '$newlocationcountryid' WHERE LocationID = '$newlocationid'";
    mysqli_query($conn, $updatelocation);
    $_SESSION['success'] = "Location has been updated";
    header('location: ./index.php');
  }
}

// remove location
if (isset($_POST['remove_location'])) {
  $locationid = mysqli_real_escape_string($conn, $_POST['locationid']);
  $removelocation = "DELETE FROM locations WHERE LocationID = '$locationid'";
  mysqli_query($conn, $removelocation);
  $_SESSION['success'] = "Location has been removed";
  header('location: ./index.php');
}

// add measurement
if (isset($_POST['create_measurement'])) {
  $measurementname = mysqli_real_escape_string($conn, $_POST['measurementname']);
  $measurementshortcode = mysqli_real_escape_string($conn, $_POST['measurementshortcode']);

  if (empty($measurementname) || empty($measurementshortcode)) {
    array_push($errors, "Both measurement name and shortcode are required");
  }

  if (count($errors) == 0) {
    $addmeasurement = "INSERT INTO measurement (Shortcode, Measure_Name)" ."VALUES ('$measurementshortcode', '$measurementname')";
    mysqli_query($conn,$addmeasurement);
    $_SESSION['success'] = "New measurement created";
    header('location: ./index.php');
  }
}

// update measurement
if (isset($_POST['update_measurement'])) {
  $measurementid = mysqli_real_escape_string($conn, $_POST['measurementid']);
  $measurementname = mysqli_real_escape_string($conn, $_POST['measurementname']);
  $measurementshortcode = mysqli_real_escape_string($conn, $_POST['measurementshortcode']);

  if (empty($measurementname) || empty($measurementshortcode)) {
    array_push($errors, "Both measurement name and shortcode are required");
  }

  if (count($errors) == 0) {
    $updatemeasurement = "UPDATE measurement SET Measure_Name = '$measurementname', Shortcode = '$measurementshortcode' WHERE Measure_ID = '$measurementid'";
    mysqli_query($conn, $updatemeasurement);
    $_SESSION['success'] = "Measurement has been updated";
    header('location: ./index.php');
  }
}
// remove measurement
if (isset($_POST['remove_measurement'])) {
  $measurementid = mysqli_real_escape_string($conn, $_POST['measurementid']);
  $removemeasurement = "DELETE FROM measurement WHERE Measure_ID = '$measurementid'";
  mysqli_query($conn, $removemeasurement);
  $_SESSION['success'] = "Measurement has been removed";
  header('location: ./index.php');
}
// add sorting
if (isset($_POST['create_sorting'])) {
  $sortingname = mysqli_real_escape_string($conn, $_POST['sortingname']);
  $sortingshortcode = mysqli_real_escape_string($conn, $_POST['sortingshortcode']);

  if (empty($sortingname) || empty($sortingshortcode)) {
    array_push($errors, "Both sorting name and shortcode are required");
  }

  if (count($errors) == 0) {
    $addsorting = "INSERT INTO sorting (peramount, peramount_desc)" ."VALUES ('$sortingshortcode', '$sortingname')";
    mysqli_query($conn,$addsorting);
    $_SESSION['success'] = "New sorting index created";
    header('location: ./index.php');
  }
}

// update sorting
if (isset($_POST['update_sorting'])) {
  $sortingid = mysqli_real_escape_string($conn, $_POST['sortingid']);
  $sortingname = mysqli_real_escape_string($conn, $_POST['sortingname']);
  $sortingshortcode = mysqli_real_escape_string($conn, $_POST['sortingshortcode']);

  if (empty($sortingname) || empty($sortingshortcode)) {
    array_push($errors, "Both sorting name and shortcode are required");
  }

  if (count($errors) == 0) {
    $updatesorting = "UPDATE sorting SET peramount_desc = '$sortingname', peramount = '$sortingshortcode' WHERE SortID = '$sortingid'";
    mysqli_query($conn, $updatesorting);
    $_SESSION['success'] = "Sorting index has been updated";
    header('location: ./index.php');
  }
}
// remove sorting
if (isset($_POST['remove_sorting'])) {
  $sortingid = mysqli_real_escape_string($conn, $_POST['sortingid']);
  $removesorting = "DELETE FROM sorting WHERE SortID = '$sortingid'";
  mysqli_query($conn, $removesorting);
  $_SESSION['success'] = "Sorting index has been removed";
  header('location: ./index.php');
}

// adjust sitename

// adjust mailsettings

// adjust activestatus site

// add cowcode
if (isset($_POST['site_add'])) {
  $cowcode = mysqli_real_escape_string($conn, $_POST['site_name']);
  $cowcodestreet = mysqli_real_escape_string($conn, $_POST['site_street']);
  $cowcodenumber = mysqli_real_escape_string($conn, $_POST['site_number']);
  $cowcodeaddition = mysqli_real_escape_string($conn, $_POST['site_addition']);
  $cowcodezipcode = mysqli_real_escape_string($conn, $_POST['site_zipcode']);
  $cowcodecity = mysqli_real_escape_string($conn, $_POST['site_city']);
  $cowcodestate = mysqli_real_escape_string($conn, $_POST['site_state']);
  $cowcodecountryid = mysqli_real_escape_string($conn, $_POST['sitecountry']);
  $cowcodesitecontact = mysqli_real_escape_string($conn, $_POST['sitecontact']);

  if (empty($cowcodeaddition)) {
    $cowcodeaddition = "";
  }
  elseif (empty($cowcode) || empty($cowcodestreet) || empty($cowcodenumber) || empty($cowcodezipcode) || empty($cowcodecity) || empty($cowcodestate) || empty($cowcodecountryid) || empty($cowcodesitecontact)) {
    array_push($errors, "All fields are required");
  }

  if (count($errors) == 0) {
    $addcowcode = "INSERT INTO sites (cowcode, site_street, site_number, site_addition, site_zipcode, site_city, site_state, countryID, site_contactID)" ."VALUES ('$cowcode', '$cowcodestreet', '$cowcodenumber', '$cowcodeaddition', '$cowcodezipcode', '$cowcodecity', '$cowcodestate', '$cowcodecountryid', '$cowcodesitecontact')";
    mysqli_query($conn,$addcowcode);
    $_SESSION['success'] = "New cowcode created";
    header('location: ./index.php');
  }
}

// update cowcode

// remove cowcode
if (isset($_POST['remove_cowcode'])) {
  $cowcodeid = mysqli_real_escape_string($conn, $_POST['cowcodeid']);
  $removecowcode = "DELETE FROM sites WHERE siteID = '$cowcodeid'";
  mysqli_query($conn, $removecowcode);
  $_SESSION['success'] = "Cowcode has been removed";
  header('location: ./index.php');
}

//add sitecontact
if (isset($_POST['create_sitecontact'])) {
  $sitecontactfirstname = mysqli_real_escape_string($conn, $_POST['sitecontactfirstname']);
  $sitecontactlastname = mysqli_real_escape_string($conn, $_POST['sitecontactlastname']);
  $sitecontactemail = mysqli_real_escape_string($conn, $_POST['sitecontactemail']);
  $sitecontactphone = mysqli_real_escape_string($conn, $_POST['sitecontactphone']);
  $sitecontactfirm = mysqli_real_escape_string($conn, $_POST['sitecontactfirm']);

  if (empty($sitecontactfirstname) || empty($sitecontactlastname) || empty($sitecontactemail) || empty($sitecontactphone) || empty($sitecontactfirm)) {
    array_push($errors, "All fields are required");
  }

  if (count($errors) == 0) {
    $addsitecontact = "INSERT INTO sitecontact (site_contactfirstname, site_contactlastname, site_contactemail, site_contactphone, site_contactfirm)" ."VALUES ('$sitecontactfirstname', '$sitecontactlastname', '$sitecontactemail', '$sitecontactphone', '$sitecontactfirm')";
    mysqli_query($conn,$addsitecontact);
    $_SESSION['success'] = "New site contact created";
    header('location: ./index.php');
  }
}

//remove sitecontact
if (isset($_POST['remove_contact'])) {
  $contactid = mysqli_real_escape_string($conn, $_POST['contactid']);
  $removecontact = "DELETE FROM sitecontact WHERE site_contactID = '$contactid'";
  mysqli_query($conn, $removecontact);
  $_SESSION['success'] = "Region contact has been removed";
  header('location: ./contacts.php');
}

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