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
     <button class="btn btn-primary open-createmeasurement" data-target="#open-createmeasurement" data-toggle="modal" data-namevalue="" >Create Measurement</button>
    
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
			<a href="./" class="nav-link active">
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
              <li class="breadcrumb-item">Measurements</li>
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
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-borderd table-stripe" id="main">
                  <thead>
                    <tr>
                      <th>Index</th>
                      <th>Name</th>
                      <th>Shortcode</th>
                      <th>Edit</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $getmeasurements = mysqli_query($conn, $measurements);

                      if (! $getmeasurements) {
                        die('Could not fetch data: '.mysqli_error($conn));
                      }

                      while($row = mysqli_fetch_assoc($getmeasurements)) {
                        ?>
                        <tr class="align-middle">
                          <td class="text-center"><?php echo htmlspecialchars($row['Measure_ID']);?></td>
                          <td class="text-center"><?php echo htmlspecialchars($row['Measure_Name']);?></td>
                          <td class="text-center"><?php echo htmlspecialchars($row['Shortcode']);?></td>
                          <td>
                              <button class="btn btn-warning open-editmeasurement" data-target="#open-editmeasurement" data-toggle="modal" data-id1="<?php echo htmlspecialchars($row['Measure_ID']);?>" data-namevalue="<?php echo htmlspecialchars($row['Measure_Name']);?>" >Edit Measurement</button>
                          </td>
                          <td>
                              <button class="btn btn-danger open-removemeasurement" data-target="#open-removemeasurement" data-toggle="modal" data-id1="<?php echo htmlspecialchars($row['Measure_ID']);?>" data-namevalue="<?php echo htmlspecialchars($row['Measure_Name']);?>" >Remove Measurement</button>
                          </td>        
                     <?php };
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="modal fade" id="open-createmeasurement">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create New Measurement</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="./index.php" method="post">
                <label for="measurementname">Measurement Name</label>
                <input type="text" class="form-control" id="measurementname" name="measurementname" placeholder="Insert measurement name"></input>
                <label for="measurementshortcode">Measurement Shortcode</label>
                <input type="text" class="form-control" id="measurementshortcode" name="measurementshortcode" placeholder="Insert measurement shortcode"></input>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="create_measurement" class="btn btn-primary">Create Measurement</button>
                </div>
              </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
       <div class="modal fade" id="open-removemeasurement">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Remove Measurement</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="./index.php" method="post">
                <label for="measurementid">Are you sure that you want to remove this measurement?</label>
                <input type="hidden" id="measurementid" name="measurementid" value=""></input>
              </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="remove_measurement" class="btn btn-primary">Confirm Removal</button>
                </div>
              </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
       <div class="modal fade" id="open-editmeasurement">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Measurement</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="./index.php" method="post">
                <label for="measurementid">Enter the new measurement name:</label>
                <input type="hidden" id="measurementid" name="measurementid" value=""></input>
                <input type="text" class="form-control" id="measurementname" name="measurementname" placeholder="Insert measurement name"></input>
                <label for="measurementshortcode">Measurement Shortcode</label>
                <input type="text" class="form-control" id="measurementshortcode" name="measurementshortcode" placeholder="Insert measurement shortcode"></input>
              </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="update_measurement" class="btn btn-primary">Confirm Update</button>
                </div>
              </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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

  $(document).on("click", ".open-createmeasurement", function () {
  });

  $(document).on("click", ".open-removemeasurement", function () {
    var measurementid = $(this).data('id1');
    $(".modal-body #measurementid").val(measurementid);
  });
  $(document).on("click", ".open-editmeasurement", function () {
    var measurementid = $(this).data('id1');
    $(".modal-body #measurementid").val(measurementid);
  });

</script>
</body>
</html>
