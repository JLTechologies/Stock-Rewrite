<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include('../config.php');
  include('../backend.php');
  include('../errors.php');
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../img/<?php echo $falo;?>" type="image/x-icon">
  <?php
  //if (!isset($_SESSION['email'])) {
   // $_SESSION['msg'] = "You must log in first";
    //header('location: ../login.php');
  //}
  //if (isset($_GET['logout'])) {
    //session_destroy();
    //unset($_SESSION['email']);
    //unset($_SESSION['success']);
    //header("location: ../login.php");
  //}

  $locid = htmlspecialchars($_POST['locationedit']);

  $getlocation = "SELECT * FROM locations INNER JOIN countries ON locations.Loc_CountryID = countries.countryid WHERE LocationID = '$locid'";

  if (isset($_GET['logout'])) {
    session_destroy();
  }
  ?>
  <title>Admin | <?php echo $site;?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" href="../">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="./index.php" class="brand-link">
      <img src="../img/<?php echo $falo;?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo $site; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="../" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="./" class="nav-link active">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Locations
              </p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="../locations/cowcodes.php" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Cow-Codes
              </p>
            </a>
          </li>
          <li class="nav-item">
			<a href="../categories/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Categories
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="../brands/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Brands
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="../brands/contact/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Contacts
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="../measurements/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Measurements
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="../sorting/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Sorting
				</p>
			</a>
			</li>
      <li class="nav-item menu-closed">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tree"></i>
            <p>
              Items
              <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
          <?php
          $getroot = mysqli_query($conn, $rootcat);

          if (! $getroot) {
            die('Could not fetch data: '.mysqli_error($conn));
          }

          while ($row2 = mysqli_fetch_assoc($getroot)) {
            ?>
            <li class="nav-item">
              <a href="../items/list.php?id=<?php echo htmlspecialchars($row2['categoryid']);?>" class="nav-link"><?php echo htmlspecialchars($row2['name']);?></a>
            </li>
          <?php };
          ?>
        </ul>
      </li>
		  <li class="nav-item">
			<a href="./users/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Users
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="./groups/" class="nav-link">
				<i class="nav-icon fas fa-users"></i>
				<p>
					Groups
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="../teams/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Teams
				</p>
			</a>
			</li>
			<li class="nav-item">
			<a href="../settings.php" class="nav-link">
				<i class="nav-icon fas fa-cog"></i>
				<p>
					Settings
				</p>
			</a>
			</li>
      <?php if (isset($_SESSION['email'])): ?>
      <li class="nav-item">
			<a href="./index.php?logout='1'" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Logout
				</p>
			</a>
			</li>
      <?php endif ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./">Admin</a></li>
              <li class="breadcrumb-item"><a href="../">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="./">Locations</a></li>
                <li class="breadcrumb-item active">Edit Location</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <?php include ('../../errors.php'); ?>
        <div class="row">
          <!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success'];
            unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Stock Location</h3>
              </div>
              <?php 
              $getlocationinfo = mysqli_query($conn, $getlocation);
              if (! $getlocationinfo) {
                die('Could not fetch data: '.mysqli_error($conn));
              }

              while($fetchinfo = mysqli_fetch_assoc($getlocationinfo)) {
                $oldlocationname = htmlspecialchars($fetchinfo['Loc_name']);
                $oldlocationstreet = htmlspecialchars($fetchinfo['Loc_Street']);
                $oldlocationnumber = htmlspecialchars($fetchinfo['Loc_Number']);
                $oldlocationaddition = htmlspecialchars($fetchinfo['Loc_addition']);
                if (isset($oldlocationaddition)) {
                  $oldlocationaddition = "";
                }
                $oldlocationzipcode = htmlspecialchars($fetchinfo['Loc_zipcode']);
                $oldlocationcity = htmlspecialchars($fetchinfo['Loc_city']);
                $oldlocationstate = htmlspecialchars($fetchinfo['Loc_state']);
                $oldlocationcountry = htmlspecialchars($fetchinfo['nicename']);
              }
              ?>
              <form name="edit_location" action="./edit.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="locationname">Name</label>
                    <input type="hidden" name="locationid" value="<?php echo $locid;?>">
                    <input type="text" class="form-control" id="locationname" placeholder="<?php echo $oldlocationname;?>" value="<?php echo $oldlocationname;?>">
                  </div>
                  <div class="form-group">
                    <label for="locationstreet">Street</label>
                    <input type="text" class="form-control" id="locationstreet" placeholder="<?php echo $oldlocationstreet;?>" value="<?php echo $oldlocationstreet;?>">
                  </div>
                  <div class="form-group">
                    <label for="locationnumber">Number</label>
                    <input type="text" class="form-control" id="locationnumber" placeholder="<?php echo $oldlocationnumber;?>" value="<?php echo $oldlocationnumber;?>">
                  </div> 
                  <div class="form-group">
                    <label for="locationaddition">Addition</label>
                    <input type="text" class="form-control" id="locationaddition" placeholder="<?php echo $oldlocationaddition;?>" value="<?php echo $oldlocationaddition;?>">
                  </div> 
                  <div class="form-group">
                    <label for="locationzipcode">Zipcode</label>
                    <input type="text" class="form-control" id="locationzipcode" placeholder="<?php echo $oldlocationzipcode;?>" value="<?php echo $oldlocationzipcode;?>">
                  </div> 
                  <div class="form-group">
                    <label for="locationcity">City</label>
                    <input type="text" class="form-control" id="locationcity" placeholder="<?php echo $oldlocationcity;?>" value="<?php echo $oldlocationcity;?>">
                  </div> 
                  <div class="form-group">
                    <label for="locationstate">State</label>
                    <input type="text" class="form-control" id="locationstate" placeholder="<?php echo $oldlocationstate;?>" value="<?php echo $oldlocationstate;?>">
                  </div>
                  <div class="form-group">
                    <label for="locationcountry">Country</label>
                    <select class="custom-select form-control border border-width-2" id="locationcountry">
                      <?php
                        $getcountries = mysqli_query($conn, $countries);

                        if (! $getcountries) {
                          die('Could not fetch data: '.mysqli_error($conn));
                        }
                        while ($row1 = mysqli_fetch_assoc($getcountries)) {?>
                          <option value="<?php echo htmlspecialchars($row1['countryid']) ;?>"><?php echo htmlspecialchars($row1['nicename']);?></option>
                        <?php };
                        ?>
                    </select>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Edit Stock Location</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- Default to the left -->
	<?php include('../../footer.php'); ?>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- SweetAlert2 -->
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="../plugins/toastr/toastr.min.js"></script>
<!-- Page specific script -->
</body>
</html>
