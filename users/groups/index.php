<!DOCTYPE html>
<html lang="en">
<head>
    <?php
  include('../../config.php');
  include('../../backend.php');
  include ('../../errors.php');
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  $faviconlogo = mysqli_query($conn, $logo);
  if (! $faviconlogo) {
    die('Logo does not exist: '.mysqli_error($conn));
  }
  while($favlog = mysqli_fetch_assoc($faviconlogo)) {?>
  <link rel="shortcut icon" href="../../img/<?php $falo = htmlspecialchars($favlog['Favicon']); echo $falo;?>" type="image/x-icon">
  <?php };

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

  $name = mysqli_query($conn, $sitename);
  if (! $name) {
    die('Could not load sitename: '.mysqli_error($conn));
  }
  while($row = mysqli_fetch_assoc($name)) {?>
  <title>Admin | <?php $site = htmlspecialchars($row['Sitename']); echo $site ;?></title>
  <?php }
  ?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../css/adminlte.min.css">
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
    <a href="../../index.php" class="brand-link">
      <img src="../../img/<?php echo $falo;?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
            <a href="./" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="./locations" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Locations
              </p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="./locations/cowcodes.php" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Cow-Codes
              </p>
            </a>
          </li>
          <li class="nav-item">
			<a href="./categories/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Categories
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="./brands/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Brands
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="./brands/contact/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Contacts
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="../../measurements/" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Measurements
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
            die('Could not fetch data: '.mysqi_error($conn));
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
			<a href="../" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Users
				</p>
			</a>
			</li>
      <li class="nav-item">
			<a href="./" class="nav-link active">
				<i class="nav-icon fas fa-users"></i>
				<p>
					Groups
				</p>
			</a>
			</li>
			<li class="nav-item">
			<a href="../../settings.php" class="nav-link">
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
              <li class="breadcrumb-item"><a href="../../">Admin</a></li>
              <li class="breadcrumb-item"><a href="../">Users</a></li>
              <li class="breadcrumb-item active">Groups</li>
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
          ?>
      	</h3>
      </div>
  	<?php endif ?>
    <div class="col-lg-6">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Index</th>
                      <th>Name</th>
                      <th>Edit</th>
                      <th>Permissions</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $getgroups = mysqli_query($conn, $groups);

                      if (! $getgroups) {
                        die('Could not fetch data: '.mysqli_error($conn));
                      }

                      while($row = mysqli_fetch_assoc($getgroups)) {
                        ?>
                        <tr class="align-middle">
                          <td class="text-center"><?php echo htmlspecialchars($row['groupID']);?></td>
                          <td class="text-center"><?php echo htmlspecialchars($row['name']);?></td>
                          <td>
                            <form name="groupedit" action="./edit.php" method="post">
                              <input type="hidden" name="groupedit" value="<?php echo htmlspecialchars($row['GroupID']);?>"/>
                              <input type="submit" value="edit group"/>
                            </form>
                          </td>
                          <td>
                            <form name="groupperms" action="./perms.php" method="post">
                              <input type="hidden" name="groupperms" value="<?php echo htmlspecialchars($row['GroupID']);?>"/>
                              <input type="submit" value="edit permissions"/>
                            </form>
                          </td>
                          <td>
                            <form name="groupremove" action="./index.php" method="post">
                              <input type="hidden" name="groupremove" value="<?php htmlspecialchars($row['GroupID']);?>"/>
                              <input type="submit" value="remove group"/>
                            </form>
                          </td>        
                     <?php };
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Group</h3>
              </div>
              <form name="measureadd" action="./index.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="measurename">Name</label>
                    <input type="text" class="form-control" id="measurename" placeholder="Enter Name">
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Add Group</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="open-editgroupname">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Groupname</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="./index.php" method="post">
                <input type="hidden" id="groupid" name="groupid" value="">
                <label for="newgroupname">New Groupname</label>
                <input type="text" class="form-control" id="newgroupname" name="newgroupname" placeholder="Enter new groupname"></input>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="submit" name="edit_groupname" class="btn btn-primary">Confirm</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
              </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- Default to the left -->
	<?php include('../../../footer.php'); ?>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../js/adminlte.min.js"></script>
<!-- Toaster -->
<script src="../../plugins/toastr/toastr.min.js"></script>
<!-- Sweetalert -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
  $(document).on("click", ".open-editgroupname", function () {
    var groupid = $(this).data('groupedit');
    $(".modal-body #groupid").val( groupid );
  });
</script>
</body>
</html>
