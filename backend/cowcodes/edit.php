<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../favicon.jpg" type="image/x-icon">
  <?php
  include('../config.php');
  include('../backend.php');
  include('../errors.php');

  if (isset($_POST['editcowcode'])) {
    $cowcodeid = htmlspecialchars($_POST['editcowcode']);
  }
  else {
    $cowcodeid = '$currentcowcodeid';
  }

  $getcowcodeinfo = "SELECT * FROM sites INNER JOIN countries ON sites.countryID = countries.countryid INNER JOIN sitecontacts ON sites.site_contactID = sitecontact.sites_contactID WHERE siteID = '$cowcodeid'";
  $getinfo = mysqli_query($conn, $getcowcodeinfo);
  if (! $getinfo) {
    die('Could not fetch data: '.mysqli_error($conn));
  }
  while ($fetchsite = mysqli_fetch_assoc($getinfo)) {
    $cowcode = htmlspecialchars($fetchsite['cowcode']);
    $sitestreet = htmlspecialchars($fetchsite['site_street']);
    $sitenumber = htmlspecialchars($fetchsite['site_number']);
    $siteaddition = htmlspecialchars($fetchsite['site_addition']);
    $sitezipcode = htmlspecialchars($fetchsite['site_zipcode']);
    $sitecity = htmlspecialchars($fetchsite['site_city']);
    $sitestate = htmlspecialchars($fetchsite['site_state']);
    $countryid = htmlspecialchars($fetchsite['countryID']);
    $countryname = htmlspecialchars($fetchsite['nicename']);
    $contactid = htmlspecialchars($fetchsite['sites_contactID']);
    $contactfirstname = htmlspecialchars($fetchsite['site_contactfirstname']);
    $contactlastname = htmlspecialchars($fetchsite['site_contactlastname']);
  }

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
      <li class="nav-item">
        <button class="btn btn-info" href="./contacts.php" >Region contactlist</button>
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
            <a href="../locations" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Locations
              </p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="./" class="nav-link active">
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
              <li class="breadcrumb-item"><a href="../">Admin</a></li>
              <li class="breadcrumb-item"><a href="../">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="./">Cow-Codes</a></li>
              <li class="breadcrumb-item active">Edit COW-Code</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
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
          <div class="col-lg-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Site</h3>
              </div>
              <form action="./index.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="sitename">COW-Code</label>
                    <input type="text" class="form-control" id="sitename" name="site_name" placeholder="Enter Site Name">
                  </div>
                  <div class="form-group">
                    <label for="sitestreet">Street</label>
                    <input type="text" class="form-control" id="sitestreet" name="site_street" placeholder="Enter Site Street">
                  </div>
                  <div class="form-group">
                    <label for="sitenumber">Number</label>
                    <input type="text" class="form-control" id="sitenumber" name="site_number" placeholder="Enter Site Number">
                  </div> 
                  <div class="form-group">
                    <label for="siteaddition">Addition</label>
                    <input type="text" class="form-control" id="siteaddition" name="site_addition" placeholder="Enter Site Addition">
                  </div> 
                  <div class="form-group">
                    <label for="sitezipcode">Zipcode</label>
                    <input type="text" class="form-control" id="sitezipcode" name="site_zipcode" placeholder="Enter Site Zipcode">
                  </div> 
                  <div class="form-group">
                    <label for="sitecity">City</label>
                    <input type="text" class="form-control" id="sitecity" name="site_city" placeholder="Enter Site City">
                  </div> 
                  <div class="form-group">
                    <label for="sitestate">State</label>
                    <input type="text" class="form-control" id="sitestate" name="site_state" placeholder="Enter Site State">
                  </div>
                  <div class="form-group">
                    <label for="sitecountry">Country</label>
                    <select class="custom-select form-control border border-width-2" id="sitecountry" name="site_country">
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
                  <div class="form-group">
                    <label for="sitecontact">Site Contact</label>
                    <select class="custom-select form-control border border-width-2" id="sitecontact" name="site_contact">
                      <?php
                        $getcontactpersons = mysqli_query($conn, $contactpersons);

                        if (! $getcontactpersons) {
                          die('Could not fetch data: '.mysqli_error($conn));
                        }
                        while ($fetchcontacts = mysqli_fetch_assoc($getcontactpersons)) {?>
                          <option value="<?php echo htmlspecialchars($fetchcontacts['sites_contactid']) ;?>"><?php echo htmlspecialchars($fetchcontacts['site_contactfirstname']) . ' ' . htmlspecialchars($fetchcontacts['site_contactlastname']);?></option>
                        <?php };
                        ?>
                    </select>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary btn-block" name="site_add">Add Site</button>
                </div>
              </form>
            </div>
          </div>
          
          <div class="modal fade" id="open-createcontact">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create New Region Contact</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="./index.php" method="post">
                <label for="contactfirstname">Contact First Name</label>
                <input type="text" class="form-control" id="contactfirstname" name="contactfirstname" placeholder="Insert contact first name"></input>
                <label for="contactlastname">Contact Last Name</label>
                <input type="text" class="form-control" id="contactlastname" name="contactlastname" placeholder="Insert contact last name"></input>
                <label for="contactemail">Contact Email</label>
                <input type="email" class="form-control" id="contactemail" name="contactemail" placeholder="Insert contact email"></input>
                <label for="contactphone">Contact Phone</label>
                <input type="text" class="form-control" id="contactphone" name="contactphone" placeholder="Insert contact phone"></input>
                <label for="contactfirm">Contact Firm</label>
                <input type="text" class="form-control" id="contactfirm" name="contactfirm" placeholder="Insert contact firm"></input>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="create_sitecontact" class="btn btn-primary">Create Region Contact</button>
                </div>
              </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
       <div class="modal fade" id="open-removecowcode">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Remove COW-Code</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="./index.php" method="post">
                <label for="cowcodeid">Are you sure that you want to remove this COW-Code?</label>
                <input type="hidden" id="cowcodeid" name="cowcodeid" value=""></input>
              </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="remove_cowcode" class="btn btn-primary">Confirm Removal</button>
                </div>
              </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
        </div>
        <!-- /.row -->
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
<script>
  $(function () {
    $("#main").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "ordering": true, "paging": true,
      "buttons": [""]
    }).buttons().container().appendTo('#main_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  $(document).on("click", ".open-createcontact", function () {
  });

  $(document).on("click", ".open-removecowcode", function () {
    var cowcodeid = $(this).data('id1');
    $(".modal-body #cowcodeid").val(cowcodeid);
  });

</script>
</body>
</html>
